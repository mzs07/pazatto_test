<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{


    public function user() {
      return $this->belongsTo('App\User');
    }

    public function food() {
      return $this->hasMany('App\Food');
    }
    //
    // public function vendor() {
    //   return $this->belongsToMany('App\Vendor');
    // }

    public function offer() {
      return $this->belongsToMany('App\Offer')
                  ->withTimestamps();
    }
}
