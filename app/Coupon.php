<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public function discount() {
      return $this->morphMany('App\Offer','offer');
    }
}
