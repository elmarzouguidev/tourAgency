<?php

namespace App\Support\Spatie;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator as BasePathGenerator;


class MediaPathGenerator implements BasePathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->prifxer($media) .  '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->prifxer($media) . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->prifxer($media) . '/responsive/';
    }

    private function prifxer($media)
    {
        return now()->format('m-Y') . '-' . $media->id . '-' . $media->uuid;
    }
}
