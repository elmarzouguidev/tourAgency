<?php

namespace App\Traits;

use App\Enums\Tools\AddressType;
use App\Models\Tools\Address;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait hasAddresses
{
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function manualAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('type', AddressType::MANUAL);
    }

    public function automaticAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where('type', AddressType::AUTOMATIC);
    }


    public function defaultAddress()
    {
        if ($this->automaticAddress()->exists()) {
            return $this->automaticAddress();
        }
        if ($this->manualAddress()->exists()) {
            return $this->manualAddress();
        }
        return 'no address';
    }
}
