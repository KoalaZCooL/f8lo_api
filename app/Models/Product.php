<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
      "url", "title", "description", "images", "last_price"
    ];

    public function prices()
    {
      return $this->hasMany(Price::class);
    }
}
