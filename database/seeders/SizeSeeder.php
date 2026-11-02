<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes=[
            ['name'=>'3xl'],
            ['name'=>'4xl'],
            ['name'=>'5xl'],
            ['name'=>'2xl'],
            ['name'=>'1xl'],
        ];

        foreach ($sizes as $dept) {
            Size::create($dept);
    }
}
}