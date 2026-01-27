<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Yoga', 'slug' => 'yoga', 'is_active' => true, 'is_valid' => true],
            ['name' => 'Hiking', 'slug' => 'hiking', 'is_active' => true, 'is_valid' => true],
            ['name' => 'Surf', 'slug' => 'surf', 'is_active' => true, 'is_valid' => true],
            ['name' => 'Cultural', 'slug' => 'cultural', 'is_active' => true, 'is_valid' => true],
            ['name' => 'Adventure', 'slug' => 'adventure', 'is_active' => true, 'is_valid' => true],
            ['name' => 'Wellness', 'slug' => 'wellness', 'is_active' => true, 'is_valid' => true],
        ];

        foreach ($categories as $category) {
            \App\Models\Utilities\Category::create($category);
        }
    }
}
