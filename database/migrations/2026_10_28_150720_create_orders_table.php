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
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('governorate_id')->nullable()->constrained()->nullOnDelete();
    
    $table->string('order_serial')->unique();
    $table->enum('status', ['new', 'processing', 'shipped', 'delivered', 'cancelled'])->default('new')->index(); // Index
    $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending'); // حالة الإيصال
    
    // بيانات العميل (Snapshot)
    $table->string('customer_name');
    $table->string('customer_phone')->index(); // Index للبحث السريع
    $table->string('governorate_name'); 
    $table->text('address_details');
    
    // الماليات
    $table->decimal('subtotal', 10, 2);
    $table->decimal('shipping_cost', 8, 2);
    $table->decimal('total_amount', 10, 2);
    
    // الدفع والشحن
    $table->string('pay_method');
    $table->string('payment_photo')->nullable();
    $table->string('tracking_number')->nullable(); // تتبع شركة الشحن
    
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
