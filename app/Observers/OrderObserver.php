<?php

namespace App\Observers;
use App\Models\Product;
use App\Models\ProductVariance;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class OrderObserver
{
    /**
     * يتم تنفيذ هذه الدالة في كل مرة يتم فيها تحديث الطلب
     */
    public function updated(Order $order): void
{
    if (!$order->isDirty('status') || !in_array($order->status, ['rejected', 'cancelled'])) {
        return;
    }

    // الحالة القديمة كانت ملغية أصلاً؟ ما نرجّعش مرتين
    if (in_array($order->getOriginal('status'), ['rejected', 'cancelled'])) {
        return;
    }

    DB::transaction(function () use ($order) {
        $variantIds = $order->items->pluck('product_variance_id')->filter()->unique();

        $productByVariant = ProductVariance::withTrashed()
            ->whereIn('id', $variantIds)
            ->pluck('product_id', 'id');

        $perProduct = [];
        foreach ($order->items as $item) {
            $pid = $productByVariant[$item->product_variance_id] ?? null;
            if ($pid) {
                $perProduct[$pid] = ($perProduct[$pid] ?? 0) + $item->quantity;
            }
        }

        foreach ($perProduct as $pid => $qty) {
            Product::where('id', $pid)->increment('stock', $qty);
        }
    });
}
}