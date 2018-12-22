<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
  public function discount() {
    return $this->morphMany('App\Offer','offer');
  }
}
