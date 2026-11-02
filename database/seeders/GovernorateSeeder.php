<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorate=[
        ['name'=>'المنصوره','shipping_cost'=>'100']
        ];
    
    foreach ($governorate as $dept) {
            Governorate::create($dept);
}
}
}