<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
  public function discount() {
    return $this->morphMany('App\Offer','offer');
  }

  public function food() {
    return $this->hasMany('App\Food');
  }
}
