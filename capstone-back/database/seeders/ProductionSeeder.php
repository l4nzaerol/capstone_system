<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = ["Preparation", "Assembly", "Finishing", "Quality Control"];
        $statuses = ["Pending", "In Progress", "Completed", "Hold"];

        $sampleProducts = [
            "Wooden Chair",
            "Dining Table",
            "Bookshelf",
            "Cabinet",
            "Bed Frame",
            "Coffee Table",
            "Stool",
            "Wardrobe",
        ];

        $data = [];
        for ($i = 1; $i <= 20; $i++) {
            $stage = $stages[array_rand($stages)];
            $status = $statuses[array_rand($statuses)];
            $product = $sampleProducts[array_rand($sampleProducts)];

            $data[] = [
                'product_name'   => $product,
                'date'           => Carbon::now()->subDays(rand(0, 15))->format('Y-m-d'),
                'stage'          => $stage,
                'status'         => $status,
                'quantity'       => rand(5, 50),
                'resources_used' => json_encode([
                    'wood'   => rand(5, 20) . " pcs",
                    'nails'  => rand(10, 100) . " pcs",
                    'paint'  => rand(1, 5) . " liters",
                ]),
                'notes'          => rand(0, 1) ? "Urgent order" : "Standard priority",
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        DB::table('productions')->insert($data);
    }
}
