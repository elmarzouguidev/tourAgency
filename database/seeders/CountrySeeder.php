<?php

namespace Database\Seeders;


use App\Models\Utilities\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Maroc', 'code' => 'MA', 'slug' => 'morocco'],
            ['name' => 'Spain', 'code' => 'ES', 'slug' => 'spain'],
            ['name' => 'Portugal', 'code' => 'PT', 'slug' => 'portugal'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
