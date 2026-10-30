<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Department;

class HomeController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        // التعديل هنا: استخدام variances.color بدلاً من colors مباشرة
        $products = Product::with([
            'department', 
            'variances.color', 
            'variances.size', 
            'primaryImage' // ضفتلك الصورة الأساسية بناءً على كودك القديم
        ])
        ->where('is_active', true) // لو الكولوم ده موجود عندك
        ->latest()
        ->get(); // أو paginate(12) لو عايز تعمل صفحات

        return view('home', compact('departments', 'products'));
    }
}