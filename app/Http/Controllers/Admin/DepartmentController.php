<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // عرض قائمة الأقسام
    
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    // عرض شاشة إضافة قسم جديد
    public function create()
    {
        return view('admin.departments.create');
    }

    // حفظ القسم الجديد في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ], [
            'name.required' => 'اسم القسم مطلوب.',
            'name.unique' => 'هذا القسم موجود بالفعل.',
        ]);

        Department::create($request->only('name'));

                return redirect(route('admin.dashboard'). '#departments-section');
    
    }

    // حذف القسم

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect(route('admin.dashboard'). '#departments-section');
                        
    }
}