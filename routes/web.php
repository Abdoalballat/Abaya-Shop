<?php

use Illuminate\Support\Facades\Route;

// Frontend Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController as CustomerOrderController;
use App\Http\Controllers\CheckoutAuthController; // إن وجد وله استخدام
use App\Http\Controllers\PostCheckoutRegisterController;

// Admin Controllers
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// ==========================================
// 1. مسارات المتجر العامة (Public Routes)
// ==========================================

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');

// سلة المشتريات والدفع (Checkout)
Route::view('/cart', 'shop.cart')->name('cart'); // تأكد إن الفيو ده موجود عندك، أو شيله لو بتكتفي بالـ checkout
Route::view('/checkout', 'checkout.index')->name('checkout.index'); 

// حفظ الطلب الجديد (قادم من صفحة الدفع)
Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');

// مسارات المصادقة (تسجيل الدخول)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// 2. مسارات العميل المسجل (Customer Protected)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // صفحة نجاح الطلب
    Route::view('/checkout/success', 'checkout.success')->name('checkout.success');
    
    // تسجيل حساب جديد بعد إتمام الطلب (لو العميل حابب يعمل حساب)
    Route::post('/checkout/register', [PostCheckoutRegisterController::class, 'store'])->name('post.checkout.register');
    
    // تتبع الطلبات للعميل
    Route::get('/orders/track', [CustomerOrderController::class, 'trackForm'])->name('orders.track.form');
    Route::post('/orders/track', [CustomerOrderController::class, 'track'])->name('orders.track'); 

});


// ==========================================
// 3. مسارات لوحة التحكم (Admin Protected)
// ==========================================
// نستخدم الميدل وير اللي أنت عملته CheckAdminRole واسمه المستعار (role)
Route::middleware(['auth', 'role:admin']) // افتراض أن دور المدير اسمه 'admin' في الداتابيز
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // الرئيسية للوحة التحكم
        Route::get('/', function () {
            return view('layouts.admin');
        })->name('dashboard');

        // إدارة البيانات الأساسية (CRUDs)
        Route::resource('departments', DepartmentController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('governorates', GovernorateController::class)->except(['show']);
        Route::resource('colors', ColorController::class)->except(['show']);
        Route::resource('sizes', SizeController::class)->except(['show']);

        // مسار حذف صور المنتجات من ImageKit (Custom Route)
        Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');

        // إدارة الطلبات (عرض وتحديث حالة فقط)
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    });