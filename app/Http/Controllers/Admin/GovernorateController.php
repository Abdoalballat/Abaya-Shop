<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    public function index()
    {
        $governorates = Governorate::latest()->paginate(15);
        return view('admin.governorates.index', compact('governorates'));
    }

    public function create()
    {
        return view('admin.governorates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255|unique:governorates,name',
            'shipping_cost'  => 'required|numeric|min:0',
            'estimated_time' => 'nullable|string|max:255',
        ]);

        Governorate::create($validated);

        return redirect()->route('admin.governorates.index')->with('success', 'تمت إضافة المحافظة بنجاح.');
    }

    public function edit(Governorate $governorate)
    {
        return view('admin.governorates.edit', compact('governorate'));
    }

    public function update(Request $request, Governorate $governorate)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255|unique:governorates,name,' . $governorate->id,
            'shipping_cost'  => 'required|numeric|min:0',
            'estimated_time' => 'nullable|string|max:255',
        ]);

        $governorate->update($validated);

        return redirect()->route('admin.governorates.index')->with('success', 'تم تعديل بيانات المحافظة بنجاح.');
    }

    public function destroy(Governorate $governorate)
    {
        $governorate->delete();
        return redirect()->route('admin.governorates.index')->with('success', 'تم حذف المحافظة بنجاح.');
    }
}