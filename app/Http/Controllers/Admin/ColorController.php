<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|unique:colors,name',
            'hex_code' => 'required|string|max:7',
        ]);

        Color::create($validated);

        return redirect(route('admin.dashboard'). '#colors-section');
    }


    public function destroy(Color $color)
    {
        $color->delete();
        return redirect(route('admin.dashboard'). '#colors-section');
    }
}