<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    protected $fillable = ['name', 'shipping_cost', 'estimated_time'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}