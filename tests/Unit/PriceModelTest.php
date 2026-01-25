<?php

use App\Enums\Utilities\ConversionCurrencyType;
use App\Models\Tour\TourPackage;
use App\Models\Utilities\ExchangeRate;
use App\Models\Utilities\Price;

uses()->group('price');

test('formats price correctly', function () {
    $price = Price::factory()->create([
        'amount' => 9999,
        'currency' => ConversionCurrencyType::USD,
    ]);

    expect($price->formatted_price)->toBe('$99.99');
});

test('formats price with currency code', function () {
    $price = Price::factory()->create([
        'amount' => 9999,
        'currency' => ConversionCurrencyType::USD,
    ]);

    expect($price->formatted_price_with_code)->toContain('USD');
});

test('creates price from decimal amount', function () {
    $price = Price::fromDecimal(99.99, ConversionCurrencyType::USD, [
        'priceable_id' => 1,
        'priceable_type' => TourPackage::class,
    ]);

    expect($price->amount)->toBe(9999)
        ->and($price->currency)->toBe(ConversionCurrencyType::USD);
});

test('checks if price is expired', function () {
    $expiredPrice = Price::factory()->create([
        'expired_at' => now()->subDay(),
    ]);

    $activePrice = Price::factory()->create([
        'expired_at' => now()->addDay(),
    ]);

    expect($expiredPrice->isExpired())->toBeTrue()
        ->and($activePrice->isExpired())->toBeFalse();
});

test('checks if price without expiry date is not expired', function () {
    $price = Price::factory()->create([
        'expired_at' => null,
    ]);

    expect($price->isExpired())->toBeFalse();
});

test('scope returns only active prices', function () {
    Price::factory()->create([
        'is_active' => false,
    ]);

    Price::factory()->create([
        'is_active' => true,
        'expired_at' => now()->subDay(),
    ]);

    $activePrice = Price::factory()->create([
        'is_active' => true,
        'expired_at' => now()->addDay(),
    ]);

    $activePrices = Price::active()->get();

    expect($activePrices)->toHaveCount(1)
        ->and($activePrices->first()->id)->toBe($activePrice->id);
});

test('converts price to another currency', function () {
    ExchangeRate::create([
        'from_currency' => 'USD',
        'to_currency' => 'EUR',
        'rate' => 0.85,
        'expires_at' => now()->addDay(),
    ]);

    $price = Price::factory()->create([
        'amount' => 10000,
        'currency' => ConversionCurrencyType::USD,
    ]);

    $converted = $price->convertTo(ConversionCurrencyType::EUR);

    expect($converted)
        ->toBeArray()
        ->toHaveKey('amount')
        ->toHaveKey('currency')
        ->toHaveKey('formatted')
        ->toHaveKey('exchange_rate')
        ->and($converted['amount'])->toBe(8500.0)
        ->and($converted['currency'])->toBe(ConversionCurrencyType::EUR)
        ->and($converted['exchange_rate'])->toBe(0.85);
});

test('gets price in multiple currencies', function () {
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

    $price = Price::factory()->create([
        'amount' => 10000,
        'currency' => ConversionCurrencyType::USD,
    ]);

    $converted = $price->inCurrencies([ConversionCurrencyType::EUR, ConversionCurrencyType::GBP]);

    expect($converted)
        ->toBeArray()
        ->toHaveKey('EUR')
        ->toHaveKey('GBP')
        ->and($converted['EUR']['amount'])->toBe(8500.0)
        ->and($converted['GBP']['amount'])->toBe(7300.0);
});

test('scope filters by currency', function () {
    Price::factory()->create(['currency' => ConversionCurrencyType::USD]);
    Price::factory()->create(['currency' => ConversionCurrencyType::EUR]);
    $madPrice = Price::factory()->create(['currency' => ConversionCurrencyType::MAD]);

    $madPrices = Price::currency(ConversionCurrencyType::MAD)->get();

    expect($madPrices)->toHaveCount(1)
        ->and($madPrices->first()->id)->toBe($madPrice->id);
});

test('gets decimal amount correctly', function () {
    $price = Price::factory()->create([
        'amount' => 9999,
        'currency' => ConversionCurrencyType::USD,
    ]);

    expect($price->decimal_amount)->toBe(99.99);
});

test('handles japanese yen without decimals', function () {
    $price = Price::factory()->create([
        'amount' => 1000,
        'currency' => ConversionCurrencyType::JPY,
    ]);

    expect($price->decimal_amount)->toBe(1000.0)
        ->and($price->formatted_price)->not()->toContain('.');
});

test('polymorphic relationship works correctly', function () {
    $product = TourPackage::factory()->create();
    
    $price = Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => TourPackage::class,
    ]);

    expect($price->priceable)->toBeInstanceOf(TourPackage::class)
        ->and($price->priceable->id)->toBe($product->id);
});
