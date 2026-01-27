<?php

use App\Models\Tour\TourPackage;
use App\Models\Utilities\Country;
use App\Models\Utilities\City;
use App\Models\Utilities\Category;

test('can search tours by country', function () {
    $country = Country::factory()->create(['name' => 'Morocco', 'slug' => 'morocco']);
    $city = City::factory()->create(['country_id' => $country->id]);
    
    $tourInMorocco = TourPackage::factory()->create();
    $tourInMorocco->cities()->attach($city->id);

    // Create a tour in another country
    $span = Country::factory()->create(['name' => 'Spain', 'slug' => 'spain']);
    $spanishCity = City::factory()->create(['country_id' => $span->id]);
    $tourInSpain = TourPackage::factory()->create();
    $tourInSpain->cities()->attach($spanishCity->id);
    
    // Check if we can search by country slug
    $response = $this->getJson(route('api.search.index', ['filter[country]' => 'morocco']));
    
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $tourInMorocco->id);
});

test('can search tours by category', function () {
    $category = Category::factory()->create(['name' => 'Hiking', 'slug' => 'hiking']);
    $hikingTour = TourPackage::factory()->create();
    $hikingTour->categories()->attach($category->id);

    $otherTour = TourPackage::factory()->create(); // No category

    $response = $this->getJson(route('api.search.index', ['filter[category]' => 'hiking']));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $hikingTour->id);
});
