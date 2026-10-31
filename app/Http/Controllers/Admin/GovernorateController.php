<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255|unique:governorates,name',
            'shipping_cost'  => 'required|numeric|min:0',
            'estimated_time' => 'nullable|string|max:255',
        ]);

        Governorate::create($validated);

        return redirect(route('admin.dashboard'). '#governorates-section');
    }



    public function destroy(Governorate $governorate)
    {
        $governorate->delete();
        return redirect(route('admin.dashboard'). '#governorates-section');
    }
}