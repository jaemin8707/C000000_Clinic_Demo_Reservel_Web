<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PetType extends Model
{
    //
    public function reserves () {
        return $this->hasMany('Reserve', 'reserve_id');
    }
}
