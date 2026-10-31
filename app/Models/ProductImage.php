<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'color_id',
        'image_path', // العمود اللي في الداتابيز
        'is_main',    // العمود اللي في الداتابيز
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // 1. دي عشان صفحة الأدمن (لما تكتب $image->url بدون أقواس)
    public function getUrlAttribute(): string
    {
        return $this->url(); // بتنادي الدالة اللي تحت
    }

    // 2. دي عشان الرئيسية وصفحة المنتج (لما تكتب $image->url('w-500') بالأقواس)
    public function url(string $transform = 'w-600,q-80,f-auto'): string
    {
        $path = $this->image_path;

        if (empty($path)) {
            return '';
        }

        $base = Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : rtrim(config('services.imagekit.endpoint'), '/') . '/' . ltrim($path, '/');

        return $base . (str_contains($base, '?') ? '&' : '?') . 'tr=' . $transform;
    }
}