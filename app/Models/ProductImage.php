<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
protected $fillable = [
        'product_id',
        'color_id', // موجود في قاعدة بياناتك
        'image_path', // تم التعديل
        'is_main',    // تم التعديل
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}