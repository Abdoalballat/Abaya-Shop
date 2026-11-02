<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user=[
        ['name'=>'halim','role'=>'admin','email'=>'moh702750@gmail.com','password'=>'$2y$12$tt2OI1SKoEUmQInaDuzngOjJqQqMA2h1vLzMkM.FwcnHiOJVGbxVy']
        ] ;
    }
}
