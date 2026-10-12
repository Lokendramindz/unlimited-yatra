<?php

namespace App\Concerns;

use Illuminate\Support\Facades\Storage;

trait HasImage
{
    public function getImageUrlAttribute(): string
    {
        $img = $this->image;
        if (! $img) {
            return 'https://picsum.photos/seed/'.class_basename($this).$this->id.'/800/600';
        }
        return str_starts_with($img, 'http') ? $img : Storage::url($img);
    }
}
