<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductVariance;
use App\Models\Governorate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OrderService
{
    protected ImageKitService $imageKitService;

    public function __construct(ImageKitService $imageKitService)
    {
        $this->imageKitService = $imageKitService;
    }

    /**
     * معالجة وإنشاء الطلب بالكامل داخل Database Transaction
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $subtotal = 0;
            $orderItemsData = [];

            // 1. جلب المحافظة لحساب تكلفة الشحن
            $governorate = Governorate::findOrFail($data['governorate_id']);
            $shippingCost = $governorate->shipping_cost;

            // 2. معالجة عناصر السلة
            foreach ($data['items'] as $item) {
                // استخدام lockForUpdate يمنع أي عملية شراء أخرى لنفس القطعة حتى تنتهي هذه العملية
                $variant = ProductVariance::with(['product', 'color', 'size'])
                    ->where('id', $item['product_variance_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // قراءة اسم المنتج بشكل صحيح لتجنب Null
                $productName = $variant->product->name ?? 'عباية';

                // التحقق من المخزون
                if ($variant->stock < $item['quantity']) {
                    $colorName = $variant->color ? $variant->color->name : 'بدون لون';
                    $sizeName = $variant->size ? $variant->size->name : 'بدون مقاس';
                    throw new Exception("عذراً، المخزون غير كافٍ للمنتج: {$productName} (اللون: {$colorName}، المقاس: {$sizeName}).");
                }

                // تنفيذ الخصم مباشرة
                ProductVariance::where('id', $item['product_variance_id'])
                    ->decrement('stock', $item['quantity']);

                $unitPrice = $variant->product->price;
                
                // حساب السعر (تم إصلاح السطر المكسور)
                $totalPrice = $unitPrice * $item['quantity']; 
                $subtotal += $totalPrice;

                // تجهيز الـ Snapshot لعناصر الطلب
                $orderItemsData[] = [
                    'product_id'   => $variant->product_id,
                    'product_name' => $productName,
                    'product_variance_id' => $item['product_variance_id'], // تم الإصلاح هنا
                    'color'        => $variant->color ? $variant->color->name : 'بدون لون',
                    'size'         => $variant->size ? $variant->size->name : 'بدون مقاس',
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $unitPrice,
                    'total_price'  => $totalPrice,
                ];
            }

            $totalAmount = $subtotal + $shippingCost;

            // 3. معالجة ورفع صورة الإيصال
            $paymentPhotoUrl = null;
            if (isset($data['payment_photo']) && $data['payment_photo'] instanceof \Illuminate\Http\UploadedFile) {
                $uploadResult = $this->imageKitService->upload($data['payment_photo'], 'receipts');
                $paymentPhotoUrl = $uploadResult->url;
            }

            // 4. إنشاء الطلب الأساسي
            $order = Order::create([
                'user_id'          => auth()->check() ? auth()->id() : null, 
                'order_serial'     => 'HLM-' . strtoupper(Str::random(6)),
                'status'           => 'new',
                'customer_name'    => $data['customer_name'],
                'customer_phone'   => $data['customer_phone'],
                'governorate_id'   => $governorate->id, 
                'governorate_name' => $governorate->name, 
                'address_details'  => $data['address_details'],
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shippingCost,
                'total_amount'     => $totalAmount,
                'pay_method'       => $data['pay_method'],
                'payment_photo'    => $paymentPhotoUrl,
            ]);

            // 5. حفظ تفاصيل المنتجات داخل الطلب
            $order->items()->createMany($orderItemsData);

            return $order;
        });
    }
}