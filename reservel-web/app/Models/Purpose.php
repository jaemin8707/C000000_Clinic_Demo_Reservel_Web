<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    protected $table = 'purpose';
    protected $fillable = ['purpose'];
    //
    public function reserves () {
        return $this->belongsTo(Reverse::class);
    }
}
