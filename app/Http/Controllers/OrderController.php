<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\TrackOrderRequest;
use App\Services\OrderService;
use App\Models\Order;
use App\Models\Governorate; // <-- ضفنا الموديل هنا
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    // الدالة دي المسؤولة عن فتح صفحة إتمام الطلب (Checkout)
    public function create(): View
    {
        // سحب المحافظات من الداتابيز
        $governorates = Governorate::all();

        // تمريرها لصفحة الدفع (غير 'checkout' لاسم ملف الـ Blade بتاعك لو مختلف)
        return view('checkout.index', compact('governorates'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('payment_photo')) {
                $data['payment_photo'] = $request->file('payment_photo');
            }

            $order = $this->orderService->createOrder($data);

            return redirect()->route('checkout.success')->with([
                'success'      => 'تم استلام طلبك بنجاح، وجاري مراجعة الإيصال.',
                'order_serial' => $order->order_serial,
                'order_id'     => $order->id, 
            ]);
        } catch (Exception $e) {
            dd('Error Happen: ' . $e->getMessage(), 'Line: ' . $e->getLine(), 'File: ' . $e->getFile());
        }
    }

    public function trackForm(): View
    {
        return view('orders.track');
    }

    public function track(TrackOrderRequest $request)
    {
        $order = Order::with('items')
            ->where('order_serial', $request->order_serial)
            ->where('customer_phone', $request->customer_phone)
            ->first();

        if (!$order) {
            return back()->withInput()->withErrors(['error' => 'لا يوجد طلب متطابق مع هذه البيانات.']);
        }

        return view('orders.track', compact('order'));
    }
}