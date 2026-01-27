<?php

namespace App\Traits;

use App\Models\Utilities\Review;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait hasReviews
{
    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
