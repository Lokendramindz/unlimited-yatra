<?php

namespace App\Models;

use App\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasImage;

    protected $guarded = [];

    protected $casts = ['sort_order' => 'integer'];

    public function yatras() { return $this->hasMany(Yatra::class); }

}
