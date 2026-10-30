<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
protected $fillable = [
    'user_id', 'order_serial', 'status', 'customer_name', 'customer_phone',
    'governorate_id', 'governorate_name', // تأكد إن دول مكتوبين هنا
    'address_details', 'subtotal', 'shipping_cost', 'total_amount', 
    'pay_method', 'payment_photo'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}