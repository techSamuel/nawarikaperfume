<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+880 1700-000000',
            'address' => '123 Admin Street',
            'city' => 'Dhaka',
            'state' => 'Dhaka',
            'zip' => '1000',
        ]);

        // Customer User
        $customer = User::create([
            'name' => 'John Doe',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+880 1800-111111',
            'address' => '456 Customer Road',
            'city' => 'Chittagong',
            'state' => 'Chittagong',
            'zip' => '4000',
        ]);

        // Categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Latest gadgets, devices, and tech accessories.', 'is_active' => true],
            ['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Trendy clothing, shoes, and accessories for all.', 'is_active' => true],
            ['name' => 'Home & Living', 'slug' => 'home-living', 'description' => 'Beautiful decor, furniture, and home essentials.', 'is_active' => true],
            ['name' => 'Books', 'slug' => 'books', 'description' => 'Bestsellers, classics, and educational books.', 'is_active' => true],
            ['name' => 'Sports & Fitness', 'slug' => 'sports-fitness', 'description' => 'Equipment and gear for active lifestyles.', 'is_active' => true],
            ['name' => 'Beauty & Health', 'slug' => 'beauty-health', 'description' => 'Skincare, wellness, and personal care products.', 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Products
        $products = [
            // Electronics
            ['category_id' => 1, 'name' => 'Wireless Bluetooth Headphones', 'slug' => 'wireless-bluetooth-headphones', 'description' => 'Premium noise-cancelling wireless headphones with 30-hour battery life. Experience crystal-clear audio with deep bass and comfortable over-ear design. Perfect for music lovers and professionals.', 'price' => 2999.00, 'sale_price' => 2499.00, 'stock' => 25, 'is_active' => true, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Smart Watch Pro', 'slug' => 'smart-watch-pro', 'description' => 'Advanced smartwatch with heart rate monitoring, GPS tracking, and water resistance up to 50m. Stay connected with notifications and track your fitness goals.', 'price' => 4500.00, 'sale_price' => null, 'stock' => 15, 'is_active' => true, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Portable Power Bank 20000mAh', 'slug' => 'portable-power-bank', 'description' => 'High-capacity portable charger with fast charging support. Charge multiple devices simultaneously with dual USB ports and USB-C input.', 'price' => 1299.00, 'sale_price' => 999.00, 'stock' => 50, 'is_active' => true, 'is_featured' => false],
            ['category_id' => 1, 'name' => 'USB-C Hub Adapter', 'slug' => 'usb-c-hub-adapter', 'description' => 'Multi-port USB-C hub with HDMI, USB 3.0, SD card reader, and ethernet port. Compatible with all USB-C laptops and tablets.', 'price' => 1899.00, 'sale_price' => null, 'stock' => 30, 'is_active' => true, 'is_featured' => false],

            // Fashion
            ['category_id' => 2, 'name' => 'Premium Cotton T-Shirt', 'slug' => 'premium-cotton-tshirt', 'description' => 'Soft 100% organic cotton t-shirt with a modern fit. Available in multiple colors. Machine washable and maintains shape after multiple washes.', 'price' => 799.00, 'sale_price' => 599.00, 'stock' => 100, 'is_active' => true, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Classic Denim Jacket', 'slug' => 'classic-denim-jacket', 'description' => 'Timeless denim jacket crafted from premium cotton denim. Features button-front closure, chest pockets, and a relaxed fit perfect for layering.', 'price' => 3499.00, 'sale_price' => null, 'stock' => 20, 'is_active' => true, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Running Sneakers', 'slug' => 'running-sneakers', 'description' => 'Lightweight and breathable running shoes with cushioned soles for maximum comfort. Designed for both casual wear and intense workouts.', 'price' => 2799.00, 'sale_price' => 2199.00, 'stock' => 35, 'is_active' => true, 'is_featured' => false],
            ['category_id' => 2, 'name' => 'Leather Belt', 'slug' => 'leather-belt', 'description' => 'Genuine leather belt with brushed metal buckle. Classic design that complements both formal and casual outfits.', 'price' => 899.00, 'sale_price' => null, 'stock' => 60, 'is_active' => true, 'is_featured' => false],

            // Home & Living
            ['category_id' => 3, 'name' => 'Minimalist Desk Lamp', 'slug' => 'minimalist-desk-lamp', 'description' => 'Elegant LED desk lamp with adjustable brightness and color temperature. USB charging port built-in. Perfect for study and work environments.', 'price' => 1599.00, 'sale_price' => 1299.00, 'stock' => 40, 'is_active' => true, 'is_featured' => true],
            ['category_id' => 3, 'name' => 'Scented Candle Set', 'slug' => 'scented-candle-set', 'description' => 'Set of 3 premium soy wax candles with natural essential oils. Fragrances include lavender, vanilla, and sandalwood. Burns for up to 40 hours each.', 'price' => 999.00, 'sale_price' => null, 'stock' => 45, 'is_active' => true, 'is_featured' => false],
            ['category_id' => 3, 'name' => 'Ceramic Plant Pot', 'slug' => 'ceramic-plant-pot', 'description' => 'Handcrafted ceramic pot with drainage hole and bamboo saucer. Modern matte finish in neutral tones. Perfect for indoor plants.', 'price' => 699.00, 'sale_price' => null, 'stock' => 55, 'is_active' => true, 'is_featured' => false],

            // Books
            ['category_id' => 4, 'name' => 'The Art of Thinking Clearly', 'slug' => 'art-of-thinking-clearly', 'description' => 'A fascinating look at human psychology and the cognitive biases that lead us astray. Bestselling book with practical insights for better decision-making.', 'price' => 450.00, 'sale_price' => 350.00, 'stock' => 70, 'is_active' => true, 'is_featured' => true],
            ['category_id' => 4, 'name' => 'JavaScript: The Good Parts', 'slug' => 'javascript-good-parts', 'description' => 'Essential guide to JavaScript programming focusing on the elegant and useful features. A must-read for web developers.', 'price' => 550.00, 'sale_price' => null, 'stock' => 30, 'is_active' => true, 'is_featured' => false],
            ['category_id' => 4, 'name' => 'Design Patterns', 'slug' => 'design-patterns', 'description' => 'Classic software engineering book covering essential design patterns. With practical examples and explanations for building robust applications.', 'price' => 750.00, 'sale_price' => 599.00, 'stock' => 25, 'is_active' => true, 'is_featured' => false],

            // Sports
            ['category_id' => 5, 'name' => 'Yoga Mat Premium', 'slug' => 'yoga-mat-premium', 'description' => 'Non-slip yoga mat made from eco-friendly TPE material. Extra thick 6mm cushioning for joint protection. Comes with carrying strap.', 'price' => 1499.00, 'sale_price' => null, 'stock' => 40, 'is_active' => true, 'is_featured' => false],
            ['category_id' => 5, 'name' => 'Resistance Band Set', 'slug' => 'resistance-band-set', 'description' => 'Set of 5 resistance bands with varying tension levels. Perfect for home workouts, physical therapy, and strength training.', 'price' => 799.00, 'sale_price' => 649.00, 'stock' => 60, 'is_active' => true, 'is_featured' => true],
            ['category_id' => 5, 'name' => 'Stainless Steel Water Bottle', 'slug' => 'stainless-steel-water-bottle', 'description' => 'Double-wall insulated water bottle that keeps drinks cold for 24 hours or hot for 12 hours. BPA-free, leak-proof, 750ml capacity.', 'price' => 599.00, 'sale_price' => null, 'stock' => 80, 'is_active' => true, 'is_featured' => false],

            // Beauty & Health
            ['category_id' => 6, 'name' => 'Natural Face Serum', 'slug' => 'natural-face-serum', 'description' => 'Vitamin C and hyaluronic acid face serum for bright, hydrated skin. Made with natural ingredients. Suitable for all skin types.', 'price' => 1299.00, 'sale_price' => 999.00, 'stock' => 35, 'is_active' => true, 'is_featured' => true],
            ['category_id' => 6, 'name' => 'Essential Oil Diffuser', 'slug' => 'essential-oil-diffuser', 'description' => 'Ultrasonic aromatherapy diffuser with LED mood lighting. 300ml capacity, whisper-quiet operation, auto shut-off safety feature.', 'price' => 1799.00, 'sale_price' => null, 'stock' => 25, 'is_active' => true, 'is_featured' => false],
            ['category_id' => 6, 'name' => 'Bamboo Toothbrush Set', 'slug' => 'bamboo-toothbrush-set', 'description' => 'Pack of 4 eco-friendly bamboo toothbrushes with BPA-free soft bristles. Biodegradable handles, individually packaged.', 'price' => 349.00, 'sale_price' => null, 'stock' => 3, 'is_active' => true, 'is_featured' => false],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }

        // Sample Orders
        $order1 = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'ORD-SAMPLE001',
            'name' => 'John Doe',
            'email' => 'customer@test.com',
            'phone' => '+880 1800-111111',
            'address' => '456 Customer Road',
            'city' => 'Chittagong',
            'state' => 'Chittagong',
            'zip' => '4000',
            'subtotal' => 5498.00,
            'shipping' => 0,
            'total' => 5498.00,
            'status' => 'processing',
            'payment_method' => 'cod',
        ]);

        OrderItem::create(['order_id' => $order1->id, 'product_id' => 1, 'product_name' => 'Wireless Bluetooth Headphones', 'price' => 2499.00, 'quantity' => 1]);
        OrderItem::create(['order_id' => $order1->id, 'product_id' => 5, 'product_name' => 'Premium Cotton T-Shirt', 'price' => 599.00, 'quantity' => 2]);
        OrderItem::create(['order_id' => $order1->id, 'product_id' => 9, 'product_name' => 'Minimalist Desk Lamp', 'price' => 1299.00, 'quantity' => 1]);

        $order2 = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'ORD-SAMPLE002',
            'name' => 'John Doe',
            'email' => 'customer@test.com',
            'phone' => '+880 1800-111111',
            'address' => '456 Customer Road',
            'city' => 'Chittagong',
            'state' => 'Chittagong',
            'zip' => '4000',
            'subtotal' => 350.00,
            'shipping' => 50.00,
            'total' => 400.00,
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);

        OrderItem::create(['order_id' => $order2->id, 'product_id' => 12, 'product_name' => 'The Art of Thinking Clearly', 'price' => 350.00, 'quantity' => 1]);

        $order3 = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'ORD-SAMPLE003',
            'name' => 'John Doe',
            'email' => 'customer@test.com',
            'phone' => '+880 1800-111111',
            'address' => '456 Customer Road',
            'city' => 'Chittagong',
            'state' => 'Chittagong',
            'zip' => '4000',
            'subtotal' => 4500.00,
            'shipping' => 0,
            'total' => 4500.00,
            'status' => 'delivered',
            'payment_method' => 'cod',
        ]);

        OrderItem::create(['order_id' => $order3->id, 'product_id' => 2, 'product_name' => 'Smart Watch Pro', 'price' => 4500.00, 'quantity' => 1]);
    }
}
