<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ConstantsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('constants')->insert([
            [
                'address' => 'dollar',
                'name' => 'доллар',
                'value' => '26.5'
            ]
        ]);
    }
}
