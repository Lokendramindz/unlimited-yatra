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

    public function reviews() { return $this->hasMany(Review::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }

    public function scopeWithApprovedReviewSummary($q)
    {
        return $q->withAvg(['reviews as approved_reviews_avg_rating' => fn ($reviews) => $reviews->approved()], 'rating')
            ->withCount(['reviews as approved_reviews_count' => fn ($reviews) => $reviews->approved()]);
    }

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

    public function getItineraryDaysAttribute(): array
    {
        $itinerary = trim((string) $this->getAttribute('itinerary'));
        if ($itinerary === '') {
            return [];
        }

        $decoded = json_decode($itinerary, true);
        if (is_array($decoded) && ($decoded === [] || is_array($decoded[0] ?? null))) {
            return array_values(array_filter($decoded, fn ($day) => is_array($day)));
        }

        return array_values(array_map(function ($line, $index) {
            if (preg_match('/^Day\s*(\d+)\s*:\s*(.*)$/i', $line, $matches)) {
                return ['title' => 'Day '.$matches[1], 'content' => $matches[2]];
            }

            return ['title' => 'Day '.($index + 1), 'content' => $line];
        }, $this->lines($itinerary), array_keys($this->lines($itinerary))));
    }

    public function getItineraryListAttribute(): array
    {
        return array_map(fn ($day) => trim(($day['title'] ?? '').': '.($day['content'] ?? '')), $this->itinerary_days);
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
