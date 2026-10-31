<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Department;

class HomeController extends Controller
{
    public function index(Request $request)
{
    $departments = Department::orderBy('id')->get();
    $activeDepartment = $departments->firstWhere('id', (int) $request->department);

    $products = Product::query()
        ->where('is_active', true)
        ->when($activeDepartment, fn ($q) => $q->where('department_id', $activeDepartment->id))
        ->with(['primaryImage', 'variances.color'])
        ->latest()
        ->paginate(12)
        ->withQueryString();

    return view('home', compact('departments', 'products', 'activeDepartment'));
}
    public function show($id)
    {
        // جلب المنتج مع كل تفاصيله (الصور، الألوان، المقاسات)
        $product =Product::with([
            'department',
            'primaryImage',
            'images', // لو عندك جدول لصور إضافية للمنتج
            'variances.color',
            'variances.size'
        ])->findOrFail($id);

        // توجيه العميل لصفحة تفاصيل المنتج
        return view('admin.products.show', compact('product'));
    }
}