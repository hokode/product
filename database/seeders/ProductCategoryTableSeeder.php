<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::create([
            'category' => 'Autos & Vehicles',
            'description' => 'Autos & Vehicles',
            'created_by' => 1
        ]);

        ProductCategory::create([
            'category' => 'Mobile Phones',
            'description' => 'Mobile Phones',
            'created_by' => 1
        ]);
    }
}
