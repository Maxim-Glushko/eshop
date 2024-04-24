<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ContentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contents')->insert([
            [
                'address' => '',
                'name' => 'Спортивные товары',
                'title' => 'Спортивные товары',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 0,
                'sequence' => 1
            ],
            [
                'address' => 'contacts',
                'name' => 'Контакты',
                'title' => 'Контакты',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 0,
                'sequence' => 5
            ],
            [
                'address' => 'football',
                'name' => 'Футбол',
                'title' => 'Футбол',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 0,
                'sequence' => 2
            ],
            [
                'address' => 'volleyball',
                'name' => 'Волейбол',
                'title' => 'Волейбол',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 0,
                'sequence' => 3
            ],
            [
                'address' => 'basketball',
                'name' => 'Баскетбол',
                'title' => 'Баскетбол',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 0,
                'sequence' => 4
            ],
            [
                'address' => 'balls',
                'name' => 'Мячи',
                'title' => 'Мячи',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 1,
                'sequence' => 1
            ],
            [
                'address' => 'sports-wear',
                'name' => 'Спортивная форма',
                'title' => 'Спортивная форма',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 1,
                'sequence' => 2
            ],
            [
                'address' => 'sports-shoes',
                'name' => 'Спортивная обувь',
                'title' => 'Спортивная обувь',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 1,
                'sequence' => 3
            ],
            [
                'address' => 'football-balls',
                'name' => 'Футбольные мячи',
                'title' => 'Футбольные мячи',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 3,
                'sequence' => 1
            ],
            [
                'address' => 'football-boots',
                'name' => 'Бутсы',
                'title' => 'Бутсы',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 3,
                'sequence' => 2
            ],
            [
                'address' => 'football-form',
                'name' => 'Футбольная форма',
                'title' => 'Футбольная форма',
                'description' => '',
                'keywords' => '',
                'text' => '',
                'parent' => 3,
                'sequence' => 3
            ]
        ]);
    }
}
