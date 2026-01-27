<?php

use App\Support\Helpers\Not;
use App\Support\Helpers\Type;
if (! function_exists('type')) {
    /**
     * Create a new type instance.
     *
     * @template TVariable
     *
     * @param  TVariable  $variable
     * @return Type<TVariable>
     */
    function type(mixed $variable): Type
    {
        return new Type($variable);
    }
}

if (! function_exists('not')) {
    /**
     * Create a new not instance.
     *
     * @template TVariable
     *
     * @param  TVariable  $variable
     * @return Not<TVariable>
     */
    function not(mixed $variable): Not
    {
        return new Not($variable);
    }
}