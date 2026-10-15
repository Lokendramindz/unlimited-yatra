<?php

namespace App\Models;

use App\Concerns\HasImage;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasImage;

    protected $guarded = [];

    public function yatra() { return $this->belongsTo(Yatra::class); }
    public function fleet() { return $this->belongsTo(Fleet::class); }

}
