<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:sizes,name',
        ]);

        Size::create($validated);

        return redirect(route('admin.dashboard'). '#sizes-section');
    }


    public function destroy(Size $size)
    {
        $size->delete();
        return redirect(route('admin.dashboard'). '#sizes-section');
    }
}