<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
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
                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // التحقق من المخزون الإجمالي للمنتج
                if ($product->stock < $item['quantity']) {
                    throw new Exception("عذراً، المخزون غير كافٍ للمنتج: {$product->name}. الكمية المتاحة: {$product->stock}");
                }

                // تنفيذ الخصم مباشرة من جدول المنتجات
                $product->decrement('stock', $item['quantity']);

                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $item['quantity'];
                $subtotal += $totalPrice;

                // استخراج أسماء اللون والمقاس للحفظ في الفاتورة
                $colorName = 'الأساسي';
                if (!empty($item['color_id'])) {
                    $color = Color::find($item['color_id']);
                    $colorName = $color ? $color->name : 'الأساسي';
                }

                $sizeName = 'الأساسي';
                if (!empty($item['size_id'])) {
                    $size = Size::find($item['size_id']);
                    $sizeName = $size ? $size->name : 'الأساسي';
                }

                // تجهيز الـ Snapshot لعناصر الطلب
                $orderItemsData[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'color'        => $colorName,
                    'size'         => $sizeName,
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