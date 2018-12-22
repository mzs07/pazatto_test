<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{


    public function food() {
      return $this->belongsTo('App\Food');
    }

    public function order() {
      return $this->belongsTo('App\Order');
    }
}
