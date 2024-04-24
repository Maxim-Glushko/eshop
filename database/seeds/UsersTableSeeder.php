<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'megl@ya.ru',
                'email' => 'megl@ya.ru',
                'password' => '$2y$10$XKQbmTVX0YzX/bptNz1Rdu8Zjb/XRy78wRZSh1YOeUybQb75XQAJG',
                'remember_token' => '1TyqfT7OLSXUdr154YplDp2ZXgmQbSlUyo1h8MnxzvV1m8CEOHJuNQ153J3G',
                'created_at' => '2016-03-08 08:00:00',
                'updated_at' => '2016-03-08 08:00:01'
            ],
            [
                'name' => '7003443@gmail.com',
                'email' => '7003443@gmail.com',
                'password' => '$2y$10$Dk/NJf6kHYZvoBHcKOoKYO0gw052VYod8bJRus2h8n/pc3lvrP5Ma',
                'remember_token' => '6vuQpGMZoo17P1y57DMdhQd3EyZVcGLw8RY4wPBxgYtq1nRETSggGgB8r1zJ',
                'created_at' => '2016-03-08 08:00:02',
                'updated_at' => '2016-03-08 08:00:03'
            ]
        ]);
    }
}
