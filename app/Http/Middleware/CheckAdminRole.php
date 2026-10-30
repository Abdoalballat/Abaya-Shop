<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. لو مش مسجل دخول، حوله لصفحة تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. لو مسجل دخول بس مش معاه الصلاحية (مثلاً عميل بيحاول يدخل رابط الإدارة)
        if (!in_array(Auth::user()->role, $roles)) {
            // تسجيل خروج كامل وتدمير الجلسة لزيادة الأمان
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // تم تصحيح طريقة تمرير رسالة الخطأ هنا
            return redirect()->route('login')->with('error', 'Unauthorized access.'); 
        }

        // 3. لو أدمن ومعاه الصلاحية، خليه يكمل
        return $next($request);
    }
}