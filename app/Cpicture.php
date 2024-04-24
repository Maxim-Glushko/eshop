<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Validator;
use App\Picture;


// прикреплённые картинки для contents
// полное наследование, другая таблица с такими же названиями полей
// специально для унификации переименовал в тех таблицах content_id и product_id в item_id
class Cpicture extends Picture {
    protected $table = 'cpictures';
}