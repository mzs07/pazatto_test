<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{


    public function customer() {
      return $this->belongsTo('App\Customer');
    }

    public function ordertable() {
      return $this->hasMany('App\OrderTable');
    }
}