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

    /**
 * يولّد كل توليفات (لون × مقاس) المختارة، ويضيف الناقص ويشيل الزايد.
 * التوليفات اللي اتشالت بتتعمل soft delete عشان الطلبات القديمة ما تبوظش.
 */
private function syncVariances(Product $product, array $colorIds, array $sizeIds): void
{
    $wanted = [];
    foreach (array_unique($colorIds) as $c) {
        foreach (array_unique($sizeIds) as $s) {
            $wanted[$c . '-' . $s] = ['color_id' => $c, 'size_id' => $s];
        }
    }

    $existing = $product->variances()->withTrashed()->get()
        ->keyBy(fn ($v) => $v->color_id . '-' . $v->size_id);

    foreach ($wanted as $key => $pair) {
        $variance = $existing->get($key);

        if (!$variance) {
            $product->variances()->create($pair);
        } elseif ($variance->trashed()) {
            $variance->restore();
        }
    }

    foreach ($existing->diffKeys($wanted) as $variance) {
        if (!$variance->trashed()) {
            $variance->delete();
        }
    }
}

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
    $data = $request->validate([
        'name'                => 'required|string|max:255',
        'code'                => 'required|string|max:255|unique:products,code',
        'price'               => 'required|numeric|min:0',
        'stock'               => 'required|integer|min:0',
        'department_id'       => 'required|exists:departments,id',
        'material_type'       => 'nullable|string|max:255',
        'description'         => 'nullable|string',
        'primary_image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        'additional_images.*' => 'nullable|image|max:4096',
        'colors'              => 'required|array|min:1',
        'colors.*'            => 'integer|exists:colors,id',
        'sizes'               => 'required|array|min:1',
        'sizes.*'             => 'integer|exists:sizes,id',
    ], [
        'primary_image.required' => 'الصورة الرئيسية للموديل مطلوبة.',
        'colors.required'        => 'اختاري لوناً واحداً على الأقل.',
        'sizes.required'         => 'اختاري مقاساً واحداً على الأقل.',
        'stock.required'         => 'اكتبي الكمية المتاحة من الموديل.',
    ]);

    // نرفع الصور الأول، لو فشلت ما يتعملش منتج ناقص
    $uploaded = $this->imageKit->upload($request->file('primary_image'), 'products');

    $extraUrls = [];
    foreach ($request->file('additional_images', []) as $file) {
        $extraUrls[] = $this->imageKit->upload($file, 'products')->url;
    }

    DB::transaction(function () use ($data, $uploaded, $extraUrls) {
        $product = Product::create([
            'name'          => $data['name'],
            'code'          => $data['code'],
            'price'         => $data['price'],
            'stock'         => $data['stock'],
            'department_id' => $data['department_id'],
            'material_type' => $data['material_type'] ?? null,
            'description'   => $data['description'] ?? null,
        ]);

        $this->syncVariances($product, $data['colors'], $data['sizes']);

        $product->images()->create([
            'image_path' => $uploaded->url,
            'is_main'    => true,
        ]);

        foreach ($extraUrls as $url) {
            $product->images()->create(['image_path' => $url, 'is_main' => false]);
        }
    });

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
    $data = $request->validate([
        'name'             => 'required|string|max:255',
        'code'             => 'required|string|max:255|unique:products,code,' . $product->id,
        'department_id'    => 'required|exists:departments,id',
        'price'            => 'required|numeric|min:0',
        'stock'            => 'required|integer|min:0',
        'description'      => 'nullable|string',
        'material_type'    => 'nullable|string|max:255',
        'main_image_id'    => 'nullable|exists:product_images,id',
        'gallery_images.*' => 'nullable|image|max:4096',
        'colors'           => 'required|array|min:1',
        'colors.*'         => 'integer|exists:colors,id',
        'sizes'            => 'required|array|min:1',
        'sizes.*'          => 'integer|exists:sizes,id',
    ]);

    // نرفع الصور الجديدة قبل الـ transaction
    $uploads = [];
    if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $file) {
            $uploads[] = $this->imageKit->upload($file, 'products')->url;
        }
    }

    DB::transaction(function () use ($request, $product, $data, $uploads) {
        $product->update([
            'name'          => $data['name'],
            'department_id' => $data['department_id'],
            'code'          => $data['code'],
            'description'   => $data['description'] ?? null,
            'material_type' => $data['material_type'] ?? null,
            'price'         => $data['price'],
            'stock'         => $data['stock'],
            'is_active'     => $request->has('is_active'),
        ]);

        if ($request->filled('main_image_id')) {
            $product->images()->update(['is_main' => false]);
            $product->images()->where('id', $request->main_image_id)->update(['is_main' => true]);
        }

        foreach ($uploads as $url) {
            $product->images()->create(['image_path' => $url, 'is_main' => false]);
        }

        $this->syncVariances($product, $data['colors'], $data['sizes']);
    });

    return redirect(route('admin.dashboard') . '#products-section')
        ->with('success', 'تم تحديث الموديل بنجاح!');
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