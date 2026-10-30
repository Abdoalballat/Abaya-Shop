<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // عرض جدول كل الطلبات في لوحة التحكم
    public function index()
    {
        $orders = Order::with(['user', 'governorate', 'items'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    // عرض تفاصيل طلب محدد وإيصال الدفع
    public function show(Order $order)
    {
        $order->load(['user', 'governorate', 'items']);
        return view('admin.orders.show', compact('order'));
    }

    // تحديث حالة الطلب وحالة الدفع من لوحة التحكم
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'         => 'nullable|string|in:new,pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|string|in:pending,paid,failed',
        ]);

        $order->update($request->only(['status', 'payment_status']));

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب والدفع بنجاح.');
    }
}