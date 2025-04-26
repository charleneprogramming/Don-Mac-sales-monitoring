<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;  // Import Str class for generating random strings

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'product_image' => 'path_to_image.jpg',  // Replace with actual image path
                'product_name' => 'Brown Spanish Latte',
                'product_price' => 39.00,
                'product_stock' => 0,
                'description' => 'A delicious brown spanish latte.',
            ],
            [
                'product_image' => 'path_to_image.jpg',  // Replace with actual image path
                'product_name' => 'Oreo Coffee',
                'product_price' => 39.00,
                'product_stock' => 0,
                'description' => 'Oreo flavored coffee.',
            ],
            [
                'product_image' => 'path_to_image.jpg',  // Replace with actual image path
                'product_name' => 'Black Forest',
                'product_price' => 39.00,
                'product_stock' => 0,
                'description' => 'A chocolatey Black Forest dessert.',
            ],
            [
                'product_image' => 'path_to_image.jpg',  // Replace with actual image path
                'product_name' => 'Don Darko',
                'product_price' => 39.00,
                'product_stock' => 0,
                'description' => 'A dark, rich coffee experience.',
            ],
            [
                'product_image' => 'path_to_image.jpg',  // Replace with actual image path
                'product_name' => 'Donya Berry',
                'product_price' => 39.00,
                'product_stock' => 0,
                'description' => 'A berry-infused coffee blend.',
            ],
            [
                'product_image' => 'path_to_image.jpg',  // Replace with actual image path
                'product_name' => 'Iced Caramel',
                'product_price' => 39.00,
                'product_stock' => 0,
                'description' => 'Refreshing iced caramel drink.',
            ],
            [
                'product_image' => 'path_to_image.jpg',  // Replace with actual image path
                'product_name' => 'Macha Berry',
                'product_price' => 39.00,
                'product_stock' => 0,
                'description' => 'Matcha and berry combined in one drink.',
            ],
            [
                'product_image' => 'path_to_image.jpg',  // Replace with actual image path
                'product_name' => 'Matcha',
                'product_price' => 39.00,
                'product_stock' => 0,
                'description' => 'Refreshing matcha green tea.',
            ],
        ];

        // Loop through each product and assign a unique product_id
        foreach ($products as &$product) {
            $product['product_id'] = strtoupper(Str::random(15)); // Generate 15-character alphanumeric ID
            $product['created_at'] = now();
            $product['updated_at'] = now();
        }

        // Insert the product data into the database
        DB::table('product')->insert($products);
    }
}
