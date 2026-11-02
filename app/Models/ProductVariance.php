<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariance extends Model
{
    protected $table = 'product_variants';
    protected $fillable = ['product_id', 'color_id', 'size_id', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function color()
    {
        return $this->belongsTo(Color::class)->withTrashed();
    }

    public function size()
    {
        return $this->belongsTo(Size::class)->withTrashed();
    }
}