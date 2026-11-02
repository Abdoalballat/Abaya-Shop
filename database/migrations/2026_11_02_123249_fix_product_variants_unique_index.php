<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) الـ unique الصح (موجود بالفعل في حالتك، بنتخطاه لو موجود)
        if (!Schema::hasIndex('product_variants', 'pv_product_color_size_unique')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->unique(['product_id', 'color_id', 'size_id'], 'pv_product_color_size_unique');
            });
        }

        // 2) امسح أي unique على عمود واحد من (product_id / color_id / size_id)
        foreach (Schema::getIndexes('product_variants') as $index) {
            $single = count($index['columns']) === 1
                && in_array($index['columns'][0], ['product_id', 'color_id', 'size_id']);

            if ($index['unique'] && !$index['primary'] && $single) {
                Schema::table('product_variants', function (Blueprint $table) use ($index) {
                    $table->dropIndex($index['name']);
                });
            }
        }
    }

    public function down(): void
    {
        // مفيش رجوع: الـ unique الفردي كان غلط من الأساس
    }
};