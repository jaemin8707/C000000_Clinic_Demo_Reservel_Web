<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetType extends Model
{
    protected $table = 'pet_type';
    protected $fillable = ['pet_type'];
    //
    public function reserves () {
        return $this->belongsTo(Reverse::class);
    }
}
