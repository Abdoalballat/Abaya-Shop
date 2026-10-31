<?php

use Illuminate\Support\Facades\Route;

// Frontend Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController as CustomerOrderController;
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
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

// سلة المشتريات والدفع (Checkout المدمجة)
Route::get('/cart', [CustomerOrderController::class, 'create'])->name('cart');
Route::get('/checkout', [CustomerOrderController::class, 'create'])->name('checkout.index');

// حفظ الطلب الجديد (قادم من صفحة الدفع)
Route::post('/orders', [CustomerOrderController::class, 'store'])->name('orders.store');

// مسارات المصادقة (تسجيل الدخول)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::view('/checkout/success', 'checkout.success')->name('checkout.success');
Route::post('/checkout/register', [PostCheckoutRegisterController::class, 'store'])->name('post.checkout.register');
// ==========================================
// 2. مسارات العميل المسجل (Customer Protected)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // صفحة نجاح الطلب
    
    // تسجيل حساب جديد بعد إتمام الطلب (اختياري)
    
    // تتبع الطلبات للعميل
    Route::get('/orders/track', [CustomerOrderController::class, 'trackForm'])->name('orders.track.form');
    Route::post('/orders/track', [CustomerOrderController::class, 'track'])->name('orders.track'); 

});


// ==========================================
// 3. مسارات لوحة التحكم (Admin Protected)
// ==========================================
// استخدام ميدل وير auth والتأكد من دور الأدمن
Route::middleware(['auth', 'role:admin']) 
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // الرئيسية للوحة التحكم (تم التعديل هنا لجلب البيانات من الداتابيز)
        Route::get('/', function () {
            // سحب البيانات من الموديلات وتمريرها للوحة التحكم
            $orders = \App\Models\Order::latest()->take(50)->get();
            $departments = \App\Models\Department::latest()->get();
            $governorates = \App\Models\Governorate::latest()->get();
            $products = \App\Models\Product::latest()->get();
            $colors = \App\Models\Color::latest()->get();
            $sizes = \App\Models\Size::latest()->get();

            return view('admin.dashboard', compact(
                'orders', 'departments', 'governorates', 'colors', 'sizes','products'
            ));
        })->name('dashboard');

        // إدارة البيانات الأساسية (CRUDs) - تعمل بناءً على الـ Controllers لديك
        Route::resource('departments', DepartmentController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('governorates', GovernorateController::class)->except(['show']);
        Route::resource('colors', ColorController::class)->except(['show']);
        Route::resource('sizes', SizeController::class)->except(['show']);

        Route::post('products/store',[ProductController::class,'store'])->name('products.store');
        // مسار حذف صور المنتجات
        Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');

        // إدارة الطلبات
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    });