<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function yatra() { return $this->belongsTo(Yatra::class); }

    public function fleet() { return $this->belongsTo(Fleet::class); }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }
}