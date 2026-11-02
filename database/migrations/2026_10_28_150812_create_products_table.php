<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('department_id')->constrained()->restrictOnDelete();
    $table->string('code')->unique(); // String للحفاظ على الأصفار
    $table->string('name'); // Varchar 255
    $table->text('description')->nullable();
    $table->string('material_type')->nullable();
    $table->decimal('price', 10, 2);
    $table->bigInteger('stock');
    $table->boolean('is_active')->default(true)->index();
    $table->softDeletes(); // Index لتسريع الفلترة
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
