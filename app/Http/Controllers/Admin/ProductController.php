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
            // كود الرفع الخاص بك هنا
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
        // 1. الفاليديشن بأسماء الجداول والأعمدة الصحيحة
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'                => 'required|string|max:255|unique:products,code,' . $product->id,
            
            // رجعناها بالجمع عشان تطابق الداتابيز الفعلية
            'department_id'       => 'required|exists:departments,id', 
            
            'price'               => 'required|numeric|min:0',
            'description'         => 'nullable|string',
            'material_type'       => 'nullable|string|max:255',
            
            // رجعناها بالجمع
            'main_image_id'       => 'nullable|exists:product_images,id', 
            
            'gallery_images.*'    => 'nullable|image|max:4096',
            'variance'            => 'required|array|min:1',
            
            // رجعناها بالجمع
            'variance.*.color_id' => 'required|exists:colors,id',
            
            // رجعناها بالجمع
            'variance.*.size_id'  => 'required|exists:sizes,id',
            
            'variance.*.stock'    => 'required|integer|min:0',
            //

        ]);
        // dd([$request]);

        DB::transaction(function () use ($request, $product) {
            // 2. تحديث المنتج بـ name
            $product->update([
                'department_id' => $request->department_id,
                'code'          => $request->code,
                'name'  => $request->name,
                'description'   => $request->description,
                'material_type' => $request->material_type,
                'price'         => $request->price,
                'is_active'     => $request->has('is_active'),
            ]);

            // 3. تحديد الصورة الرئيسية الجديدة (باستخدام is_head)
            if ($request->filled('main_image_id')) {
                $product->images()->update(['is_head' => false]);
                $product->images()->where('id', $request->main_image_id)->update(['is_head' => true]);
            }

            // 4. رفع أي صور إضافية للمعرض (باستخدام url و is_head)
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $imageFile) {
                    $uploaded = $this->imageKit->upload($imageFile, 'products');
                    $product->images()->create([
                        'url'     => $uploaded->url,
                        'is_head' => false,
                    ]);
                }
            }

            // 5. تحديث المخزون (باستخدام variances في المسح والإنشاء)
            $product->variances()->delete();
            foreach ($request->variance as $varData) {
                $product->variances()->create([
                    'color_id' => $varData['color_id'],
                    'size_id'  => $varData['size_id'],
                    'stock'    => $varData['stock'],
                ]);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'تم تعديل بيانات العباية بنجاح.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
return redirect(route('admin.dashboard') . '#products-section')
                ->with('success', 'تم إضافة الموديل بنجاح!');    }

    public function destroyImage(ProductImage $image)
    {
        $image->delete();
        return back()->with('success', 'تم حذف الصورة من قاعدة البيانات بنجاح.');
    }
}