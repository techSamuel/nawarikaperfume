<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Get the configured API Key.
     */
    public function getApiKey(?string $override = null): ?string
    {
        return $override ?: (Setting::get('gemini_api_key') ?: config('services.gemini.api_key'));
    }

    /**
     * Get the configured Gemini Model.
     */
    public function getModel(?string $override = null): string
    {
        $model = $override ?: (Setting::get('gemini_model') ?: config('services.gemini.model'));
        return !empty($model) ? trim($model) : 'gemini-2.5-flash';
    }

    /**
     * Call the Gemini API using the Interactions endpoint, with graceful fallback.
     *
     * @param string $prompt
     * @param string|null $apiKey
     * @param string|null $model
     * @return string
     * @throws \Exception
     */
    public function callGemini(string $prompt, ?string $apiKey = null, ?string $model = null): string
    {
        $key = $this->getApiKey($apiKey);
        $mdl = $this->getModel($model);

        if (empty($key)) {
            throw new \Exception('Gemini API key is not configured. Please add your API key in Admin Settings > AI Settings.');
        }

        // Primary: Google Gemini Interactions API (as specified in user requirements)
        $interactionsUrl = 'https://generativelanguage.googleapis.com/v1beta/interactions';

        $response = null;
        $primaryError = null;

        try {
            $response = Http::withHeaders([
                'x-goog-api-key' => $key,
                'Content-Type' => 'application/json',
            ])->timeout(45)->post($interactionsUrl, [
                'model' => $mdl,
                'input' => $prompt,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $extracted = $this->extractTextFromInteractionsResponse($data);
                if ($extracted) {
                    return $extracted;
                }
            } else {
                $primaryError = $response->json()['error']['message'] ?? $response->body();
            }
        } catch (\Exception $e) {
            $primaryError = $e->getMessage();
        }

        // Fallback: models.generateContent endpoint (for legacy or alternative model formats)
        try {
            $generateUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$mdl}:generateContent";

            $fallbackResponse = Http::withHeaders([
                'x-goog-api-key' => $key,
                'Content-Type' => 'application/json',
            ])->timeout(45)->post($generateUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($fallbackResponse->successful()) {
                $fbData = $fallbackResponse->json();
                $extracted = $fbData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($extracted) {
                    return $extracted;
                }
            } else {
                $fbError = $fallbackResponse->json()['error']['message'] ?? $fallbackResponse->body();
                throw new \Exception("Gemini API Error: " . ($primaryError ?: $fbError));
            }
        } catch (\Exception $e) {
            Log::error('Gemini API Exception', [
                'model' => $mdl,
                'primary_error' => $primaryError,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception("Gemini API request failed: " . ($primaryError ?: $e->getMessage()));
        }

        throw new \Exception("Gemini API responded without readable content: " . ($primaryError ?? 'Unknown error'));
    }

    /**
     * Extract generated text from Gemini Interactions API response.
     */
    protected function extractTextFromInteractionsResponse(array $data): ?string
    {
        // 1. Check for steps array
        if (!empty($data['steps']) && is_array($data['steps'])) {
            foreach (array_reverse($data['steps']) as $step) {
                if (!empty($step['content']['parts']) && is_array($step['content']['parts'])) {
                    foreach ($step['content']['parts'] as $part) {
                        if (isset($part['text']) && trim($part['text']) !== '') {
                            return $part['text'];
                        }
                    }
                }
            }
        }

        // 2. Check for output_text or text property
        if (!empty($data['output_text'])) {
            return $data['output_text'];
        }
        if (!empty($data['text'])) {
            return $data['text'];
        }

        // 3. Check candidates structure
        if (!empty($data['candidates'][0]['content']['parts'][0]['text'])) {
            return $data['candidates'][0]['content']['parts'][0]['text'];
        }

        return null;
    }

    /**
     * Test the Gemini connection with a lightweight prompt.
     */
    public function testConnection(?string $apiKey = null, ?string $model = null): array
    {
        try {
            $key = $this->getApiKey($apiKey);
            $mdl = $this->getModel($model);

            if (empty($key)) {
                return [
                    'success' => false,
                    'message' => 'Please enter an API Key first.',
                ];
            }

            $testPrompt = "Reply with exactly: Connection successful! Gemini is ready.";
            $reply = $this->callGemini($testPrompt, $key, $mdl);

            return [
                'success' => true,
                'message' => "Success! Model {$mdl} responded: " . trim($reply),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate authentic reviews for a product using Google Gemini.
     *
     * @param Product $product
     * @param int $count
     * @param array $options
     * @return array Array of reviews: [['customer_name' => '', 'rating' => 5, 'review_text' => '']]
     * @throws \Exception
     */
    public function generateReviews(Product $product, int $count = 3, array $options = []): array
    {
        $count = max(1, min(20, (int)$count));
        $ratingPreference = $options['rating_style'] ?? 'mostly_5';
        $language = $options['language'] ?? Setting::get('gemini_default_review_lang', 'mixed');
        $customNotes = trim($options['custom_notes'] ?? '');

        // Rating instructions
        $ratingInstruction = match ($ratingPreference) {
            'all_5' => 'All generated reviews must have a rating of 5 stars.',
            'mostly_5' => 'Generate predominantly 5-star reviews (around 80%) and some 4-star reviews (around 20%). Rating must be an integer between 4 and 5.',
            'mixed' => 'Generate realistic varied ratings between 3 and 5 stars (mostly 4 and 5, with occasional 3). Rating must be an integer between 3 and 5.',
            default => 'Rating must be an integer between 4 and 5.'
        };

        // Language instructions
        $langInstruction = match ($language) {
            'bangla' => 'Write all customer reviews in authentic Bengali (বাংলা). Use natural, everyday Bangladeshi phrasing (e.g., পারফিউমটির ঘ্রাণ অসাধারণ, লং লাস্টিং, দ্রুত ডেলিভারি পেয়েছি, প্যাকেজিং চমৎকার). Use common Bangladeshi customer names (e.g., তানভীর আহমেদ, নুসরাত জাহান, ফারজানা হক, আরিফুর রহমান, শাকিব আল হাসান).',
            'banglish' => 'Write customer reviews in Banglish (Bengali written in English letters, very popular among online shoppers in Bangladesh, e.g., "Anek shundor fragrance, easily 6-7 hours thake", "Packaging khub valo chilo, thank you Luxe"). Use Bangladeshi names in English (e.g., Tanvir Hasan, Nusrat Jahan, Shakil Ahmed, Mim Akter).',
            'english' => 'Write customer reviews in natural, fluent English. Mention details like scent longevity, sillage, compliments, and bottle presentation. Use varied international and South Asian customer names.',
            default => 'Write a natural realistic mix suitable for a Bangladeshi online perfume / luxury store: some reviews in Bangla (বাংলা), some in natural English, and some in Banglish. Use realistic Bangladeshi customer names (written in English or Bangla matching the review language).'
        };

        $productTitle = $product->name;
        $categoryName = $product->category ? $product->category->name : 'Fragrance & Perfume';
        $price = $product->sale_price ?? $product->price;
        $description = !empty($product->description) ? strip_tags($product->description) : 'Premium luxury perfume with long-lasting scent notes.';

        $prompt = <<<PROMPT
You are an expert e-commerce copywriter generating authentic, realistic customer reviews for an online store.
The product details are:
- Title: {$productTitle}
- Category: {$categoryName}
- Price: ৳{$price}
- Description: {$description}

Requirements:
1. Generate exactly {$count} unique customer reviews.
2. Review text must specifically reflect this product's title and description (e.g. mention its scent notes, performance, longevity, bottle, packaging, value for money).
3. Do NOT make them sound robotic or like AI clichés. Make each review sound like a real, distinct customer sharing their personal experience. Keep review lengths varied (some short & punchy 1-2 sentences, some detailed 2-4 sentences).
4. Rating Guidelines: {$ratingInstruction}
5. Language & Customer Names: {$langInstruction}
PROMPT;

        if (!empty($customNotes)) {
            $prompt .= "\n6. Special Admin Instructions: " . $customNotes;
        }

        $prompt .= <<<FORMAT

CRITICAL: You must return ONLY a valid JSON array of objects. Do not include markdown codeblocks or explanation.
Format:
[
  {
    "customer_name": "Full Customer Name",
    "rating": 5,
    "review_text": "Review text here..."
  }
]
FORMAT;

        $rawResponse = $this->callGemini($prompt);

        return $this->parseReviewsJson($rawResponse, $count);
    }

    /**
     * Parse and validate reviews JSON from Gemini output.
     */
    protected function parseReviewsJson(string $raw, int $expectedCount): array
    {
        // Strip markdown code fences if present (```json ... ```)
        $clean = preg_replace('/^```(?:json)?\s*/i', '', trim($raw));
        $clean = preg_replace('/\s*```$/i', '', $clean);

        // Find JSON array in text if surrounding text exists
        if (preg_match('/\[\s*\{.*\}\s*\]/s', $clean, $matches)) {
            $jsonString = $matches[0];
        } elseif (preg_match('/\{\s*"customer_name".*\}/s', $clean, $matches)) {
            // Single object wrapped
            $jsonString = '[' . $matches[0] . ']';
        } else {
            $jsonString = $clean;
        }

        $decoded = json_decode($jsonString, true);

        if (!is_array($decoded)) {
            // Attempt secondary clean up of any trailing commas
            $jsonString = preg_replace('/,\s*([\]}])/m', '$1', $jsonString);
            $decoded = json_decode($jsonString, true);
        }

        if (!is_array($decoded)) {
            Log::warning('Gemini returned unparseable JSON for reviews', ['raw' => $raw]);
            throw new \Exception("Could not parse AI reviews as JSON. Raw response: " . mb_substr($raw, 0, 200) . '...');
        }

        $reviews = [];
        foreach ($decoded as $item) {
            if (!is_array($item)) {
                continue;
            }

            $name = trim($item['customer_name'] ?? $item['name'] ?? 'Verified Buyer');
            $rating = (int)($item['rating'] ?? 5);
            $rating = max(1, min(5, $rating));
            $text = trim($item['review_text'] ?? $item['review'] ?? $item['text'] ?? '');

            if (!empty($text)) {
                $reviews[] = [
                    'customer_name' => $name,
                    'rating' => $rating,
                    'review_text' => $text,
                ];
            }
        }

        if (empty($reviews)) {
            throw new \Exception("AI generated response did not contain valid reviews. Please try again.");
        }

        return $reviews;
    }
}
