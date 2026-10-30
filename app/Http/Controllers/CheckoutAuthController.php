<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAfterCheckoutRequest;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CheckoutAuthController extends Controller
{
    public function register(RegisterAfterCheckoutRequest $request)
    {
        $order = Order::where('order_serial', $request->order_serial)->firstOrFail();

        $user = User::create([
            'name'     => $order->customer_name,
            'phone'    => $request->customer_phone,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        // ربط جميع طلبات هذا الهاتف بحسابه الجديد
        Order::where('customer_phone', $request->customer_phone)
            ->update(['user_id' => $user->id]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'تم إنشاء وتفعيل حسابك بنجاح!');
    }
}