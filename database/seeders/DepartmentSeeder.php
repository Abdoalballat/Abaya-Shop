<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $departments=[
            ['name'=>'شتوى','id'=>'30005'],
            ['name'=>'تيست الحذف','id'=>'30006'],

        ];

        foreach ($departments as $dept)
            {
            Department::create($dept);
            }
}
}