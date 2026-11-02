<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors=[
            ['name'=>'كافيه','id'=>'30005','hex_code'=>'#b5895e'],
            ['name'=>'كشمير','id'=>'30006','hex_code'=>'#b76e79'],
            ['name'=>'بترولى','id'=>'30007','hex_code'=>'#005f69'],
            ['name'=>'تيست حذف','id'=>'30008','hex_code'=>'#ff1f1f'],
        ];

        foreach ($colors as $col)
            {
                Color::create($col);
            } 
    }
}
