<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Governorate extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'shipping_cost', 'estimated_time'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}