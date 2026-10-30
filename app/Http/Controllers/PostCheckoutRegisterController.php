<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PostCheckoutRegisterController extends Controller
{
    public function store(Request $request)
    {
        // 1. التحقق من صحة كلمة المرور ورقم الطلب
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'password' => 'required|min:8|confirmed',
        ]);

        $order = Order::findOrFail($request->order_id);

        // 2. إذا كان الطلب مربوطاً بحساب بالفعل، نتجاهل العملية
        if ($order->user_id) {
            return redirect()->route('home')->with('info', 'هذا الطلب مربوط بحساب بالفعل.');
        }

        // 3. التحقق مما إذا كان رقم الهاتف مسجلاً مسبقاً في الداتابيز
        $existingUser = User::where('phone', $order->customer_phone)->first();
        if ($existingUser) {
            $order->update(['user_id' => $existingUser->id]);
            return redirect()->route('login')->with('info', 'لديك حساب سابق بهذا الرقم. تم ربط الطلب بحسابك، برجاء تسجيل الدخول.');
        }

        // 4. إنشاء حساب العميل الجديد وإعطائه الصلاحية مباشرة
        $user = User::create([
            'name'     => $order->customer_name,
            'phone'    => $order->customer_phone,
            'password' => Hash::make($request->password),
            'role'     => 'customer', // إعطاء الصلاحية هنا بناءً على العمود الجديد في الداتابيز
        ]);

        // 5. ربط الطلب بالعميل الجديد
        $order->update(['user_id' => $user->id]);

        // 6. تسجيل الدخول مباشرة للعميل
        Auth::login($user);

        return redirect()->route('home')->with('success', 'تم إنشاء حسابك وتسجيل الدخول بنجاح!');
    }
}