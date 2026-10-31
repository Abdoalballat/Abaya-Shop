<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'color_id',
        'image_path',
        'is_main',
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

    // رابط ImageKit مع تحويل (حجم/جودة/فورمات تلقائي)
    public function url(string $transform = 'w-600,q-80,f-auto'): string
    {
        $path = $this->image_path;

        $base = Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : rtrim(config('services.imagekit.endpoint'), '/') . '/' . ltrim($path, '/');

        return $base . (str_contains($base, '?') ? '&' : '?') . 'tr=' . $transform;
    }
}