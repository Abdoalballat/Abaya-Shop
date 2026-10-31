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
        return view('login');
    }

    // 2. تسجيل أدمن جديد
    public function registerAdmin(Request $request)
    {
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

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), 
            'role'     => 'admin', 
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'تم إنشاء حساب الإدارة بنجاح',
            'data'    => [
                'admin' => $admin
            ]
        ], 201);
    }

    // 3. معالجة طلب تسجيل الدخول (تم التعديل هنا)
    public function login(Request $request)
    {
        // اسم الحقل القادم من الفورم هو phone (حسب كودك القديم)
        $request->validate([
            'phone'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'phone.required' => 'البريد الإلكتروني أو رقم الهاتف مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        // تحديد نوع المدخل: هل هو إيميل أم رقم هاتف؟
        $loginValue = $request->input('phone');
        $fieldType = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // محاولة تسجيل الدخول بناءً على النوع الذي تم اكتشافه
        if (Auth::attempt([$fieldType => $loginValue, 'password' => $request->input('password')], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // التحقق من الصلاحية بناءً على عمود role كما تم حفظه في التسجيل
            if (Auth::user()->role === 'admin') { 
                return redirect()->route('admin.dashboard');
            }

            // لو عميل عادي، وجهه للصفحة الرئيسية
            return redirect()->route('home');
        }

        // لو البيانات غلط
        return back()->withErrors([
            'phone' => 'بيانات الدخول غير صحيحة، يرجى التأكد من الإيميل/رقم الهاتف وكلمة المرور.',
        ])->onlyInput('phone');
    }

    // 4. تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}