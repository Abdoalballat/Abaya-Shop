<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
        use SoftDeletes;

    protected $fillable = [
        'department_id', 'code', 'name', 
        'description', 'material_type', 'price', 'is_active'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class)->withTrashed();
    }

public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // إضافة هذه الدالة لجلب الصورة الرئيسية
public function primaryImage()
{
    return $this->hasOne(ProductImage::class)
        ->orderByDesc('is_main')
        ->orderBy('id');
}

public function variances()
{
    return $this->hasMany(ProductVariance::class);
     // أو ProductVariance::class حسب تسميتك للملف
}

    // جلب الصورة الرئيسية مباشرة
    public function getMainImageAttribute()
    {
        return $this->images()->where('is_main', true)->first();
    }

    // حساب إجمالي المخزون أوتوماتيكياً من جدول المتغيرات
    public function getTotalStockAttribute()
    {
        return $this->variances()->sum('stock');
    }
}