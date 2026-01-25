<?php

namespace Database\Factories;

use App\Enums\Utilities\CurrencyType;
use App\Models\Tour\TourPackage;
use App\Models\Utilities\Price;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceFactory extends Factory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
            'priceable_id' => TourPackage::factory(),
            'priceable_type' => TourPackage::class,
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug(),
            'amount' => $this->faker->numberBetween(1000, 100000),
            'currency' => $this->faker->randomElement(CurrencyType::cases()),
            'expired_at' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'options' => null,
            'is_active' => true,
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function expired(): self
    {
        return $this->state(fn(array $attributes) => [
            'expired_at' => now()->subDay(),
        ]);
    }

    public function inUSD(): self
    {
        return $this->state(fn(array $attributes) => [
            'currency' => CurrencyType::USD,
        ]);
    }
}
