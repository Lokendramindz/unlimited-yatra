<?php

namespace App\Models;

use App\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;

class Yatra extends Model
{
    use HasImage;

    protected $guarded = [];

    protected $casts = ['is_featured' => 'boolean', 'is_special' => 'boolean', 'is_active' => 'boolean', 'gallery' => 'array'];

    public function category() { return $this->belongsTo(Category::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }

    public function getDurationLabelAttribute(): string
    {
        return $this->duration_nights
            ? "{$this->duration_days} Days / {$this->duration_nights} Nights"
            : "{$this->duration_days} Days";
    }

    public function getHighlightListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->highlights))));
    }

    public function getItineraryListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->itinerary))));
    }


    public function getGalleryUrlsAttribute(): array
    {
        return collect($this->gallery ?? [])
            ->map(fn ($g) => str_starts_with($g, 'http') ? $g : \Illuminate\Support\Facades\Storage::url($g))
            ->all();
    }

    private function lines(?string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\\R/', (string) $text))));
    }

    public function getInclusionListAttribute(): array { return $this->lines($this->inclusions); }
    public function getExclusionListAttribute(): array { return $this->lines($this->exclusions); }
}
