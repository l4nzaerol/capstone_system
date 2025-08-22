<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        Product::insert([
            [
                'name' => 'Ipon Challenge 2025',
                'description' => '',
                'price' => 259.00,
                'stock' => 5,
                'image' => 'storage/products/Ipon2025.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REUSABLE | FOR DREAM HOUSE |',
                'description' => '', 
                'price' => 289.00,
                'stock' => 3,
                'image' => 'storage/products/Ipon2025.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REUSABLE | KEEP SAVING|',
                'description' => '',
                'price' => 3199.00,
                'stock' => 10,
                'image' => 'storage/products/Ipon2025.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}