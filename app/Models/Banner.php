<?php

namespace App\Models;

use App\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasImage;

    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean'];
}
