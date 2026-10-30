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
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'                => 'required|string|max:255|unique:products,code',
            'department_id'       => 'required|exists:departments,id',
            'price'               => 'required|numeric|min:0',
            'description'         => 'nullable|string',
            'material_type'       => 'nullable|string|max:255',
            'main_image'          => 'required|image|max:4096',
            'gallery_images.*'    => 'nullable|image|max:4096',
            'variance'            => 'required|array|min:1',
            'variance.*.color_id' => 'required|exists:colors,id',
            'variance.*.size_id'  => 'required|exists:sizes,id',
            'variance.*.stock'    => 'required|integer|min:0',
            ]);
            
            // dd(["here".$request]);
        DB::transaction(function () use ($request) {
            // 1. إنشاء المنتج
            $product = Product::create([
                'department_id' => $request->department_id,
                'code'          => $request->code,
                'name'  => $request->name,
                'description'   => $request->description,
                'material_type' => $request->material_type,
                'price'         => $request->price,
                'is_active'     => $request->has('is_active'),
            ]);

            // 2. رفع الصورة الرئيسية (is_head = true) إلى ImageKit
            if ($request->hasFile('main_image')) {
                $mainUploaded = $this->imageKit->upload($request->file('main_image'), 'products');
                $product->images()->create([
                    'image_path'     => $mainUploaded->url,
                    'is_head' => true,
                ]);
            }

            // 3. رفع الصور الإضافية (is_head = false)
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $imageFile) {
                    $uploaded = $this->imageKit->upload($imageFile, 'products');
                    $product->images()->create([
                        'image_path'     => $uploaded->url,
                        'is_head' => false,
                    ]);
                }
            }

            // 4. حفظ المتغيرات والمخزون في جدول product_variance
            foreach ($request->variance as $varData) {
                $product->variances()->create([
                    'color_id' => $varData['color_id'],
                    'size_id'  => $varData['size_id'],
                    'stock'    => $varData['stock'],
                ]);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'تم حفظ المنتج ومخزونه بنجاح.');
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
                $product->images()->update(['is_main' => false]);
                $product->images()->where('id', $request->main_image_id)->update(['is_main' => true]);
            }

            // 4. رفع أي صور إضافية للمعرض (باستخدام url و is_main)
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $imageFile) {
                    $uploaded = $this->imageKit->upload($imageFile, 'products');
                    $product->images()->create([
                        'url'     => $uploaded->url,
                        'is_main' => false,
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
        return redirect()->route('admin.products.index')->with('success', 'تم حذف العباية بنجاح.');
    }

    public function destroyImage(ProductImage $image)
    {
        $image->delete();
        return back()->with('success', 'تم حذف الصورة من قاعدة البيانات بنجاح.');
    }
}