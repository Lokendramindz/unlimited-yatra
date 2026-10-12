<?php

namespace App\Models;

use App\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasImage;

    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean'];
}
