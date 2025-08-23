<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Production;
use Carbon\Carbon;

class AlkansyaProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Production::create([
            'product_name' => 'Alkansya',
            'date' => Carbon::now()->format('Y-m-d'),
            'stage' => 'Preparation',
            'status' => 'Pending',
            'quantity' => 50,
            'resources_used' => [
                'tin' => '50 pcs',
                'paint' => '2 liters',
                'labels' => '50 pcs',
            ],
            'notes' => 'Initial Alkansya production batch',
        ]);
    }
}