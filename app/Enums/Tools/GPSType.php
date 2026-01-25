<?php

namespace App\Enums\Tools;

enum GPSType: string
{
    case HTML = 'HTML'; // exemple : google maps iframe
    case AUTO = 'AUTO'; // auto detect from address

    public function getName(): string
    {
        return match ($this) {

            self::HTML => 'HTML',
            self::AUTO => 'AUTO',
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
