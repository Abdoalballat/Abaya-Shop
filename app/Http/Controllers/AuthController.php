<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 1. عرض صفحة تسجيل الدخول
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function registerAdmin(Request $request)
    {
        // 1. التحقق من البيانات (استخدام الإيميل)
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. إنشاء حساب الأدمن
        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin', // تعيين صلاحية الأدمن
        ]);

        // 3. إرجاع استجابة JSON
        return response()->json([
            'status'  => 'success',
            'message' => 'تم إنشاء حساب الإدارة بنجاح',
            'data'    => [
                'admin' => $admin
            ]
        ], 201);
    }

    // 2. معالجة طلب تسجيل الدخول
    public function login(Request $request)
    {
        // التحقق من صحة البيانات (Validation)
        $credentials = $request->validate([
            'phone'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'phone.required' => 'رقم الهاتف مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        // محاولة تسجيل الدخول
        if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // لو اليوزر ده أدمن (عنده صلاحية الإدارة)، وجهه للوحة التحكم
            if (Auth::user()->is_admin) { // تأكد إن اسم الكولوم ده مطابق للي في الداتابيز عندك
                return redirect()->route('admin.dashboard');
            }

            // لو عميل عادي، وجهه للصفحة الرئيسية
            return redirect()->route('home');
        }

        // لو البيانات غلط، رجعه لنفس الصفحة مع رسالة خطأ
        return back()->withErrors([
            'phone' => 'بيانات الدخول غير صحيحة، يرجى التأكد من رقم الهاتف وكلمة المرور.',
        ])->onlyInput('phone');
    }

    // 3. تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}