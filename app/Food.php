<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{


    public function vendor() {
      return $this->belongsTo('App\Vendor');
    }

    public function cart() {
      return $this->hasMany('App\Cart');
    }


    public function ordertable() {
      return $this->hasMany('App\OrderTable');
    }

    public function discount() {
      return $this->belongsTo('App\Discount');
    }
}
