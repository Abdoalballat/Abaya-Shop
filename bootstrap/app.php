<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // حل مشكلة الـ HTTPS والـ Logout على Vercel
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e, Request $request) {
            // 1. إظهار الأخطاء بالتفصيل للأدمن فقط
            if ($request->is('admin*')) {
                return response(
                    get_class($e) . "\n" . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine(),
                    500,
                    ['Content-Type' => 'text/plain; charset=utf-8']
                );
            }

            // 2. إخفاء الأخطاء البرمجية (500) عن المستخدم العادي
            // نستثني أخطاء الـ Validation، والـ 404، والـ Auth عشان لارافيل يتعامل معاهم بشكل طبيعي
            if (! $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && 
                ! $e instanceof \Illuminate\Validation\ValidationException &&
                ! $e instanceof \Illuminate\Auth\AuthenticationException) {
                
                // إذا كان الطلب قادم من API أو Ajax
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'عفواً، حدث خطأ في النظام. يرجى المحاولة لاحقاً.'], 500);
                }

                // واجهة خطأ بسيطة للعميل بألوان المتجر
                return response(
                    '<div style="text-align:center; padding: 100px 20px; font-family: tahoma, sans-serif; direction: rtl; background-color: #FDEEF0; min-height: 100vh; margin: -8px;">
                        <h2 style="color: #C2577A; margin-bottom: 10px;">عفواً، حدث خطأ فني بالموقع</h2>
                        <p style="color: #8A6E78; margin-bottom: 30px;">جاري العمل على حل المشكلة، يرجى المحاولة بعد قليل.</p>
                        <a href="/" style="background-color: #C2577A; color: white; padding: 12px 25px; border-radius: 12px; text-decoration: none; font-weight: bold;">العودة للرئيسية</a>
                    </div>',
                    500,
                    ['Content-Type' => 'text/html; charset=utf-8']
                );
            }
        });
    })->create();