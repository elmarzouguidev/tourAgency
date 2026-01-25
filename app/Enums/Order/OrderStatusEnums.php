<?php

namespace App\Enums\Order;

enum OrderStatusEnums: string
{
    case PENDING = 'PENDING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case CONFIRMED = 'CONFIRMED';
    case CANCELLED = 'CANCELLED';
    case LIVRED = 'LIVRED';


    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'En attente',
            self::IN_PROGRESS => 'En cours',
            self::CONFIRMED => 'Confirmée',
            self::CANCELLED => 'Annulée',
            self::LIVRED => 'Livré',
        };
    }

    public static function options()
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $type) => [
                $type->value => $type->label(),
            ])
            ->toArray();
    }
}
