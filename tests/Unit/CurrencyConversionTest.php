<?php

use App\Enums\Utilities\ConversionCurrencyType;
use App\Models\Utilities\ExchangeRate;
use App\Services\CurrencyConversionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses()->group('currency');

beforeEach(function () {
    $this->service = app(CurrencyConversionService::class);
});

test('converts same currency returns same amount', function () {
    $result = $this->service->convert(100, ConversionCurrencyType::USD, ConversionCurrencyType::USD);
    
    expect($result)->toBe(100.0);
});

test('converts between currencies using cached rate', function () {
    ExchangeRate::create([
        'from_currency' => 'USD',
        'to_currency' => 'EUR',
        'rate' => 0.85,
        'expires_at' => now()->addDay(),
    ]);

    $result = $this->service->convert(100, ConversionCurrencyType::USD, ConversionCurrencyType::EUR);
    
    expect($result)->toBe(85.0);
});

test('fetches rate from api when not cached', function () {
    Http::fake([
        '*' => Http::response([
            'rates' => [
                'EUR' => 0.85,
                'GBP' => 0.73,
            ],
        ], 200),
    ]);

    $result = $this->service->convert(100, ConversionCurrencyType::USD, ConversionCurrencyType::EUR);
    
    expect($result)->toBe(85.0)
        ->and(ExchangeRate::where('from_currency', 'USD')
            ->where('to_currency', 'EUR')
            ->where('rate', 0.85)
            ->exists()
        )->toBeTrue();
});

test('gets all rates for base currency', function () {
    ExchangeRate::create([
        'from_currency' => 'USD',
        'to_currency' => 'EUR',
        'rate' => 0.85,
        'expires_at' => now()->addDay(),
    ]);

    ExchangeRate::create([
        'from_currency' => 'USD',
        'to_currency' => 'GBP',
        'rate' => 0.73,
        'expires_at' => now()->addDay(),
    ]);

    $rates = $this->service->getAllRates(ConversionCurrencyType::USD);
    
    expect($rates)
        ->toBeArray()
        ->toHaveKey('EUR')
        ->toHaveKey('GBP')
        ->and($rates['EUR'])->toBe(0.85)
        ->and($rates['GBP'])->toBe(0.73);
});

test('throws exception when rate cannot be fetched', function () {
    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $this->service->convert(100, ConversionCurrencyType::USD, ConversionCurrencyType::EUR);
})->throws(\Exception::class);

test('uses fallback rate when api fails', function () {
    // Create an old rate
    ExchangeRate::create([
        'from_currency' => 'USD',
        'to_currency' => 'EUR',
        'rate' => 0.80,
        'expires_at' => now()->subDay(),
    ]);

    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $result = $this->service->convert(100, ConversionCurrencyType::USD, ConversionCurrencyType::EUR);
    
    expect($result)->toBe(80.0);
});

