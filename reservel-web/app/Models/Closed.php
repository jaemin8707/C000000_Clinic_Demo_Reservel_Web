<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Closed extends Model
{
  protected $table = 'closed';
  protected $fillable = ['closed_day', 'closed_type'];
    //
  public function getClosedDay() {
    Carbon::now();
  }
}
