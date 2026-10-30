<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\ProductVariance;

class OrderObserver
{
    /**
     * يتم تنفيذ هذه الدالة في كل مرة يتم فيها تحديث الطلب
     */
    public function updated(Order $order): void
    {
        // 1. نتأكد إن حالة الطلب اتغيرت، وبقت إما مرفوض أو ملغي
        if ($order->isDirty('status') && in_array($order->status, ['rejected', 'cancelled'])) {
            
            // 2. نتأكد إن الحالة القديمة مكنتش ملغية أصلاً (عشان منرجعش المخزون مرتين بالغلط)
            if (!in_array($order->getOriginal('status'), ['rejected', 'cancelled'])) {
                
                // 3. نلف على منتجات الطلب ونرجع المخزون
                foreach ($order->items as $item) {
                    if ($item->product_variance_id) {
                        ProductVariance::where('id', $item->product_variance_id)
                            ->increment('stock', $item->quantity);
                    }
                }
            }
        }
    }
}