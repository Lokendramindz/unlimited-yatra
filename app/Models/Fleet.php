<?php

namespace App\Models;

use App\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasImage;

    protected $guarded = [];
    protected $casts = [
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'air_conditioning' => 'boolean',
        'driver_included' => 'boolean',
    ];

    public function reviews() { return $this->hasMany(Review::class); }

    public function getFeatureListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->features))));
    }

    public function getFaqListAttribute(): array
    {
        $items = json_decode((string) $this->faqs, true);

        return is_array($items) ? array_values(array_filter($items, fn ($item) => ! empty($item['question']) && ! empty($item['answer']))) : [];
    }
}
