<?php

namespace App\Models;

use App\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasImage;

    protected $guarded = [];

}
