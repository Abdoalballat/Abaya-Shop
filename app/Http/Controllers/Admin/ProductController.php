<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Department;
use App\Models\Color;
use App\Models\Size;
use App\Services\ImageKitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected ImageKitService $imageKit;

    public function __construct(ImageKitService $imageKit)
    {
        $this->imageKit = $imageKit;
    }

public function index()
    {
        // عدلناها هنا إلى variances.color و variances.size
        $products = Product::with(['department', 'variances.color', 'variances.size', 'primaryImage'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $departments = Department::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('admin.products.create', compact('departments', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        // 1. تحديث الـ Validation ليطابق أسماء الحقول في الفورم
        // dd('here');
        $request->validate([
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|max:255',
            'price'         => 'required|numeric',
            'department_id' => 'required|exists:departments,id',
            'primary_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // تم تعديل الاسم هنا
            'variances'     => 'required|array', // تم تعديل الاسم هنا للجمع
            'variances.*.color_id' => 'required|exists:colors,id',
            'variances.*.size_id'  => 'required|exists:sizes,id',
            'variances.*.stock'    => 'required|integer|min:0',
        ], [
            // رسائل خطأ مخصصة بالعربي (اختياري)
            'primary_image.required' => 'الصورة الرئيسية للموديل مطلوبة.',
            'variances.required'     => 'يجب إضافة لون ومقاس واحد على الأقل للموديل.',
        ]);

        // 2. كود الحفظ (استخدم variances بدل variance)
        $product = Product::create([
            'name'          => $request->name,
            'code'          => $request->code,
            'price'         => $request->price,
            'department_id' => $request->department_id,
            'material'      => $request->material,
            'description'   => $request->description,
        ]);

        // 3. حفظ الألوان والمقاسات
        if ($request->has('variances')) {
            foreach ($request->variances as $variance) {
                if (isset($variance['color_id']) && isset($variance['size_id']) && isset($variance['stock'])) {
                    $product->variances()->create([
                        'color_id' => $variance['color_id'],
                        'size_id'  => $variance['size_id'],
                        'stock'    => $variance['stock'],
                    ]);
                }
            }
        }

// 4. كود حفظ الصورة الرئيسية (باستخدام primary_image)
        if ($request->hasFile('primary_image')) {
            // الرفع على ImageKit
            $uploaded = $this->imageKit->upload($request->file('primary_image'), 'products');
            
            // حفظ الرابط في الداتابيز كصورة رئيسية
            $product->images()->create([
                'image_path' => $uploaded->url, // بيحفظ الرابط السحابي المباشر
                'is_main'    => true,
            ]);
        }

        // توجيه الأدمن لنفس القسم مع رسالة نجاح
        return redirect(route('admin.dashboard') . '#add-product-section')
               ->with('success', 'تم إضافة الموديل بنجاح!');
    }

public function edit(Product $product)
    {
        // عدلناها هنا إلى variances
        $product->load(['variances', 'images']);
        $departments = Department::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('admin.products.edit', compact('product', 'departments', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product)
{
    $request->validate([
        'name'               => 'required|string|max:255',
        'code'               => 'required|string|max:255|unique:products,code,' . $product->id,
        'department_id'      => 'required|exists:departments,id',
        'price'              => 'required|numeric|min:0',
        'description'        => 'nullable|string',
        'material_type'      => 'nullable|string|max:255',
        'main_image_id'      => 'nullable|exists:product_images,id',
        'gallery_images.*'   => 'nullable|image|max:4096',
        'variance'           => 'required|array|min:1',
        'variance.*.color_id' => 'required|exists:colors,id',
        'variance.*.size_id'  => 'required|exists:sizes,id',
        'variance.*.stock'    => 'required|integer|min:0',
    ]);

    DB::transaction(function () use ($request, $product) {

        // 1) تحديث بيانات المنتج
        $product->update([
            'department_id' => $request->department_id,
            'code'          => $request->code,
            'description'   => $request->description,
            'material_type' => $request->material_type,
            'price'         => $request->price,
            'is_active'     => $request->has('is_active'),
        ]);

        // 2) الصورة الرئيسية (بتتنفذ بس لو المستخدم اختار)
        if ($request->filled('main_image_id')) {
            $product->images()->update(['is_main' => false]);
            $product->images()
                ->where('id', $request->main_image_id)
                ->update(['is_main' => true]);
        }

        // 3) رفع صور جديدة (لو موجودة) — الصور القديمة مش بتتمسح
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $imageFile) {
                $uploaded = $this->imageKit->upload($imageFile, 'products');
                $product->images()->create([
                    'image_path'     => $uploaded->url,
                    'is_main' => false,
                ]);
            }
        }

        // ===================================================
        // 4) تحديث الـ variants بشكل آمن (بدون حذف المربوط بالطلبات)
        // ===================================================

        // اللي جاي من الفورم متجمّع بالمفتاح "color_id-size_id"
        $submitted = collect($request->variance)
            ->keyBy(fn($v) => $v['color_id'] . '-' . $v['size_id']);

        // الموجود حالياً في الداتابيز بنفس المفتاح
        $existing = $product->variances()->get()->keyBy(
            fn($v) => $v->color_id . '-' . $v->size_id
        );

        // (أ) تحديث أو إنشاء
        foreach ($submitted as $key => $data) {
            if ($existing->has($key)) {
                $variance = $existing->get($key);
                if ((int) $variance->stock !== (int) $data['stock']) {
                    $variance->update(['stock' => $data['stock']]);
                }
                $existing->forget($key); // هنشيله من القائمة عشان مايتحسبش "محذوف"
            } else {
                $product->variances()->create([
                    'color_id' => $data['color_id'],
                    'size_id'  => $data['size_id'],
                    'stock'    => $data['stock'],
                ]);
            }
        }

        // (ب) اللي فضل في $existing = اتحذف من الفورم
        foreach ($existing as $variance) {
            $usedInOrders = DB::table('order_items')
                ->where('product_variance_id', $variance->id)
                ->exists();

            if ($usedInOrders) {
                // مربوط بطلب قديم → مينفعش نمسحه، نصفّر مخزونه
                $variance->update(['stock' => 0]);
            } else {
                $variance->delete();
            }
        }
    });

    return redirect(route('admin.dashboard') . '#products-section')
                ;
}

    public function destroy(Product $product)
    {
        // التأكد إن المنتج مش مستخدم في أي طلب قديم
        $hasOrders = DB::table('order_items')
            ->whereIn('product_variance_id', $product->variances()->pluck('id'))
            ->exists();

        if ($hasOrders) {
            // لو ليه طلبات، منع الحذف واظهار رسالة
$product->update(['is_active' => false]);
return back()->with('success', 'تم إخفاء الموديل من المتجر لوجود طلبات سابقة مرتبطة به.');        }

        // لو مفيش طلبات، امسحه عادي
        $product->delete();
        
        return redirect(route('admin.dashboard') . '#products-section')
                ->with('success', 'تم حذف الموديل بنجاح!');
    }

    public function destroyImage(ProductImage $image)
    {
        $image->delete();
        return back()->with('success', 'تم حذف الصورة من قاعدة البيانات بنجاح.');
    }
}