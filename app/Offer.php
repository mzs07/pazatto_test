<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([
  'amount' => 'App\Amount',
  'percentage' => 'App\Percentage',
  'discount' => 'App\Discount',
]);

class Offer extends Model
{
    public function offer() {
      return $this->morphTo();
    }

    public function customer() {
      return $this->belongsToMany('App\Customer')
                  ->withPivot('limit')
                  ->withTimestamps();
    }

    public function vendor() {
      return $this->belongsToMany('App\Vendor')
                  ->withTimestamps();
    }
}
