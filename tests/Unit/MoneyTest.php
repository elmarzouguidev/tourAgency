<?php

use App\Enums\Utilities\ConversionCurrencyType;
use App\Models\Utilities\ExchangeRate;
use App\ValueObjects\Money;
uses()->group('money');

test('adds money objects', function () {
    $money1 = new Money(5000, ConversionCurrencyType::USD);
    $money2 = new Money(3000, ConversionCurrencyType::USD);

    $result = $money1->add($money2);

    expect($result->amount)->toBe(8000)
        ->and($result->currency)->toBe(ConversionCurrencyType::USD);
});

test('subtracts money objects', function () {
    $money1 = new Money(5000, ConversionCurrencyType::USD);
    $money2 = new Money(3000, ConversionCurrencyType::USD);

    $result = $money1->subtract($money2);

    expect($result->amount)->toBe(2000);
});

test('multiplies money', function () {
    $money = new Money(5000, ConversionCurrencyType::USD);
    $result = $money->multiply(2);

    expect($result->amount)->toBe(10000);
});

test('divides money', function () {
    $money = new Money(10000, ConversionCurrencyType::USD);
    $result = $money->divide(2);

    expect($result->amount)->toBe(5000);
});

test('throws exception when dividing by zero', function () {
    $money = new Money(10000, ConversionCurrencyType::USD);
    $money->divide(0);
})->throws(\InvalidArgumentException::class, 'Cannot divide by zero');

test('compares money objects - greater than', function () {
    $money1 = new Money(10000, ConversionCurrencyType::USD);
    $money2 = new Money(5000, ConversionCurrencyType::USD);

    expect($money1->isGreaterThan($money2))->toBeTrue()
        ->and($money2->isGreaterThan($money1))->toBeFalse();
});

test('compares money objects - less than', function () {
    $money1 = new Money(5000, ConversionCurrencyType::USD);
    $money2 = new Money(10000, ConversionCurrencyType::USD);

    expect($money1->isLessThan($money2))->toBeTrue()
        ->and($money2->isLessThan($money1))->toBeFalse();
});

test('compares money objects - equals', function () {
    $money1 = new Money(10000, ConversionCurrencyType::USD);
    $money2 = new Money(10000, ConversionCurrencyType::USD);
    $money3 = new Money(10001, ConversionCurrencyType::USD);

    expect($money1->equals($money2))->toBeTrue()
        ->and($money1->equals($money3))->toBeFalse();
});

test('formats money as string', function () {
    $money = new Money(9999, ConversionCurrencyType::USD);
    
    expect((string) $money)->toBe('$99.99')
        ->and($money->format())->toBe('$99.99');
});

test('converts money to array', function () {
    $money = new Money(9999, ConversionCurrencyType::USD);
    $array = $money->toArray();
    
    expect($array)
        ->toBeArray()
        ->toHaveKey('amount')
        ->toHaveKey('currency')
        ->toHaveKey('formatted')
        ->toHaveKey('symbol')
        ->and($array['amount'])->toBe(9999)
        ->and($array['currency'])->toBe('USD')
        ->and($array['symbol'])->toBe('$');
});

test('converts money to different currency', function () {
    ExchangeRate::create([
        'from_currency' => 'USD',
        'to_currency' => 'EUR',
        'rate' => 0.85,
        'expires_at' => now()->addDay(),
    ]);

    $usdMoney = new Money(10000, ConversionCurrencyType::USD);
    $eurMoney = $usdMoney->convertTo(ConversionCurrencyType::EUR);

    expect($eurMoney->currency)->toBe(ConversionCurrencyType::EUR)
        ->and($eurMoney->amount)->toBe(8500.0);
});

test('adds money with different currencies by converting', function () {
    ExchangeRate::create([
        'from_currency' => 'EUR',
        'to_currency' => 'USD',
        'rate' => 1.18,
        'expires_at' => now()->addDay(),
    ]);

    $usdMoney = new Money(10000, ConversionCurrencyType::USD);
    $eurMoney = new Money(8500, ConversionCurrencyType::EUR);
    
    $result = $usdMoney->add($eurMoney);

    expect($result->currency)->toBe(ConversionCurrencyType::USD)
        ->and($result->amount)->toBeGreaterThan(10000);
});
