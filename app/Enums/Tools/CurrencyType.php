<?php

namespace App\Enums\Tools;

enum CurrencyType: string
{
    case DOLLAR = 'DOLLAR';
    case EURO = 'EURO';
    case MAD = 'MAD';

    public function getName(): string
    {
        return match ($this) {

            self::DOLLAR => 'dollar',
            self::EURO => 'euro',
            self::MAD => 'MAD',
        };
    }

    public function getSymbole(): string
    {
        return match ($this) {

            self::DOLLAR => '$',
            self::EURO => '€',
            self::MAD => 'dh',
        };
    }

    public function getCode(): string
    {
        return match ($this) {

            self::DOLLAR => 'usd',
            self::EURO => 'eur',
            self::MAD => 'mad',
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $type) => [
                $type->value => $type->getName(),
            ])
            ->toArray();
    }
}
