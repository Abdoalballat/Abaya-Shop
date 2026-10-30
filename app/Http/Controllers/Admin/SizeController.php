<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:sizes,name',
        ]);

        Size::create($validated);

        return redirect()->route('admin.sizes.index')->with('success', 'تمت إضافة المقاس بنجاح.');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:sizes,name,' . $size->id,
        ]);

        $size->update($validated);

        return redirect()->route('admin.sizes.index')->with('success', 'تم تعديل المقاس بنجاح.');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'تم حذف المقاس بنجاح.');
    }
}