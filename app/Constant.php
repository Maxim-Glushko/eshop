<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Input;
use Illuminate\Support\Facades\Validator;

class Constant extends Eloquent
{ // пока редактирую курс доллара, но по идее можно сюда засунуть и другие константы, если понадобится
    
    protected $table = 'constants';
    
    public $timestamps = false;
    
    protected $fillable = ['value'];
    
    public static function convertPrice($price)
    {
        $row = self::whereAddress('dollar')->select('value')->first();
        return round($row['value'] * $price);
    }
    
    public static function getValidationRules()
    { // $id нужен, чтобы игнорировать сравнение с адресом своей же статьи
        return [
            'value' => 'required|numeric|min:1|max:255'
        ];
    }
    
    public static function edit()
    {
        $data = Input::all();
        
        $validation = Validator::make($data, self::getValidationRules());
        if ($validation->fails())
        {return ['errors' => $validation->messages()];}
            //return \Redirect::back()->withErrors($validation)->withInput();
        
        self::whereAddress('dollar')->update([
            'value' => $data['value']
        ]);
        return ['message' => 'Доллар отредактирован!'];
    }
}