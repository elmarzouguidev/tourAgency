<?php

namespace App\Traits;

use Spatie\Sluggable\HasSlug as SpatieSlug;
use Spatie\Sluggable\SlugOptions;

trait HasSlug
{
    use SpatieSlug;

    public function getSlugName(): string
    {
        return property_exists($this, 'slugName') ? $this->slugName : 'name';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSlugName())
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate()
            ->skipGenerateWhen(fn() => $this->shoudGenerateSlug()) // I use this because in some cases the $this->getSlugName() got null and the slug property get values like : -1 ,-2 ,-3 ,...
            ->slugsShouldBeNoLongerThan(250);
    }

    private function shoudGenerateSlug(): bool
    {
        return !array_key_exists($this->getSlugName(), $this->attributes);
    }
}
