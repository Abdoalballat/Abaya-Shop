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

        return redirect()->route('admin.departments.index')
                         ->with('success', 'تم إضافة القسم بنجاح.');
    }

    // عرض شاشة تعديل قسم
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    // حفظ التعديلات
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ], [
            'name.required' => 'اسم القسم مطلوب.',
            'name.unique' => 'هذا القسم موجود بالفعل.',
        ]);

        $department->update($request->only('name'));

        return redirect()->route('admin.departments.index')
                         ->with('success', 'تم تعديل القسم بنجاح.');
    }

    // حذف القسم
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
                         ->with('success', 'تم حذف القسم بنجاح.');
    }
}