<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Mail\AdminNewOrderMail;
use App\Mail\OrderStatusChangedMail;
use App\Mail\TestSmtpMail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailConfigService
{
    /**
     * Apply stored or custom SMTP settings to Laravel runtime config.
     */
    public static function applyConfig(?array $override = null): void
    {
        $settings = Setting::whereIn('key', [
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username',
            'mail_password', 'mail_encryption', 'mail_from_address',
            'mail_from_name', 'admin_order_notification_email',
            'customer_order_email_enabled'
        ])->pluck('value', 'key')->toArray();

        if ($override) {
            $settings = array_merge($settings, array_filter($override, fn($v) => !is_null($v)));
        }

        $host = $settings['mail_host'] ?? config('mail.mailers.smtp.host') ?: 'smtp.hostinger.com';
        $port = (int) ($settings['mail_port'] ?? config('mail.mailers.smtp.port', 587));
        $username = $settings['mail_username'] ?? config('mail.mailers.smtp.username');
        $password = $settings['mail_password'] ?? config('mail.mailers.smtp.password');
        $encryption = $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption', 'tls');
        $fromAddress = $settings['mail_from_address'] ?? config('mail.from.address', 'admin@nawarika.store');
        $fromName = $settings['mail_from_name'] ?? config('mail.from.name', config('app.name', 'Nawarika Perfume'));

        // Normalize encryption
        if ($encryption === 'none' || empty($encryption)) {
            $encryption = null;
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', $host);
        Config::set('mail.mailers.smtp.port', $port);
        Config::set('mail.mailers.smtp.encryption', $encryption);
        Config::set('mail.mailers.smtp.username', $username);
        Config::set('mail.mailers.smtp.password', $password);
        Config::set('mail.mailers.smtp.verify_peer', false);
        Config::set('mail.mailers.smtp.scheme', ($port === 465 || $encryption === 'ssl') ? 'smtps' : null);

        Config::set('mail.from.address', $fromAddress);
        Config::set('mail.from.name', $fromName);

        // Reset any cached mailer instance in memory
        Mail::purge('smtp');
        Mail::purge();
    }

    /**
     * Notify Admin when a new order is placed.
     */
    public function sendAdminNewOrderNotification(Order $order): bool
    {
        try {
            self::applyConfig();

            $adminEmail = Setting::where('key', 'admin_order_notification_email')->value('value');
            if (empty($adminEmail)) {
                $adminUser = User::where('role', 'admin')->first();
                $adminEmail = $adminUser?->email ?: env('MAIL_ADMIN_NOTIFICATION_EMAIL', 'rodalsoft@gmail.com');
            }

            if (empty($adminEmail)) {
                Log::warning("Cannot send admin new order alert: No admin notification email configured.");
                return false;
            }

            $order->loadMissing('items.product');

            // Support comma-separated emails
            $emails = array_map('trim', explode(',', $adminEmail));
            Mail::mailer('smtp')->to($emails)->send(new AdminNewOrderMail($order));

            Log::info("Admin new order notification email sent successfully for Order #{$order->order_number} to " . implode(', ', $emails));
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send admin order notification for #{$order->order_number}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Notify customer (logged in user or order email) when order details/status change.
     */
    public function sendOrderStatusChangedNotification(Order $order, string $oldStatus, string $newStatus, array $changes = []): bool
    {
        try {
            self::applyConfig();

            $customerEmailEnabled = Setting::where('key', 'customer_order_email_enabled')->value('value');
            if ($customerEmailEnabled === '0') {
                return false;
            }

            // Customer email from order or associated user account
            $recipients = array_filter(array_unique([
                $order->email,
                $order->user?->email,
            ]));

            if (empty($recipients)) {
                Log::info("Skipping order status email: Order #{$order->order_number} has no customer email.");
                return false;
            }

            $order->loadMissing('items.product', 'user');

            Mail::mailer('smtp')->to($recipients)->send(new OrderStatusChangedMail($order, $oldStatus, $newStatus, $changes));

            Log::info("Order status changed email sent for Order #{$order->order_number} to " . implode(', ', $recipients) . " (Status: {$oldStatus} -> {$newStatus})");
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send order status changed email for #{$order->order_number}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Test SMTP Connection by sending a test email.
     */
    public function testConnection(string $toEmail, ?array $override = null): array
    {
        try {
            self::applyConfig($override);

            Mail::mailer('smtp')->to($toEmail)->send(new TestSmtpMail());

            return [
                'success' => true,
                'message' => "Test email dispatched successfully to {$toEmail}! Your SMTP settings are working perfectly."
            ];
        } catch (\Throwable $e) {
            Log::error("SMTP Test failed: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "SMTP Error: " . $e->getMessage()
            ];
        }
    }
}
