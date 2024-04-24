<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Input;
use Illuminate\Support\Facades\Validator;


class Parameter extends Eloquent {
    
    protected $table = 'parameters';
    
    public $timestamps = false;
    
    protected $fillable = ['address', 'name', 'for_order', 'sequence'];
    
    public static function add()
    {
        $data = Input::all();
        
        $validation = Validator::make($data, [
            'address'   => ['required', 'unique:parameters,address', 'regex:/^[-_0-9a-z]+$/', 'max:255', 'min:1'],
            'name'      => ['unique:parameters,name', 'max:255', 'min:1']
        ]);
        if ($validation->fails())
            return ['errors' => $validation->messages()];
        
        $sequence = self::max('sequence') + 1;
        
        self::create([
            'address' => $data['address'],
            'name' => $data['name'],
            'sequence' => $sequence,
            'for_order' => isset($data['for_order']) ? 1 : 0
        ]);
        
        return ['message' => 'Параметр успешно создан'];
    }
    
    public static function edit($id)
    {
        $data = Input::all();
        
        $validation = Validator::make($data, [
            'address' => ['unique:parameters,address,' . $id, 'regex:/^[-_0-9a-z]+$/', 'max:255', 'min:1'],
            'name'    => ['unique:parameters,name,' . $id, 'max:255', 'min:1']
        ]);
        if ($validation->fails())
            return ['errors' => $validation->messages()];
        
        self::whereId($id)->update([
            'address' => $data['address'],
            'name' => $data['name'],
            'for_order' => isset($data['for_order']) ? 1 : 0
        ]);
        
        return ['message' => 'Изменено.'];
    }
    
    public static function up($id)
    {
        $row = self::whereId($id)->select('sequence')->first();
        if (!$row)
            return ['error' => 'Нет такого.'];
        if ($row['sequence'] < 2)
            return ['error' => 'Уже первый.'];
        self::whereSequence($row['sequence']-1)->increment('sequence');
        self::whereId($id)->decrement('sequence');
        return ['message' => 'Пункт повышен!'];
    }
    
    public static function down($id)
    {
        $row = self::whereId($id)->select('sequence')->first();
        if (!$row)
            return ['error' => 'Нет такого.'];
        $maxSequence = self::max('sequence');
        if ($row['sequence'] >= $maxSequence)
            return ['error' => 'Уже последний.'];
        self::whereSequence($row['sequence']+1)->decrement('sequence');
        self::whereId($id)->increment('sequence');
        return ['message' => 'Пункт понижен!'];
    }
    
    public static function del($id)
    {
        $row = self::whereId($id)->select('name','sequence')->first();
        if (!$row)
            return ['error' => 'Не найден. Видимо, удалён ранее.'];
        
        self::where('sequence','>',$row['sequence'])->decrement('sequence');
        self::whereId($id)->delete();
        
        return ['message' => '"' . $row['name'] . '" удалён!'];
    }
    
    public static function getAll()
    { // для селекта
        $rows = self::select('*')->orderBy('sequence', 'asc')->get();
        if (!$rows)
            return false;
        $parameters = [];
        foreach ($rows as $r)
            $parameters[$r['id']] = $r['address'] . ' / ' . $r['name'];
        return $parameters;
    }
    
    public static function getJoinForItem($product_id)
    { // получение уже существующих связок значений параметров с продуктом
      // это для вывода вне форм в админке: цвет: красный, цвет:белый, фирма: Адидас
        $rows = self::join('values','parameters.id', '=', 'values.parameter_id')
            ->join('product_value', 'values.id', '=', 'product_value.value_id')
            ->where('product_id',$product_id)
            ->orderBy('parameters.sequence','asc')
            ->orderBy('values.sequence', 'asc')
            ->select('product_value.id as id', 'parameters.address as address', 'parameters.name as name',
                    'values.address as valueaddress', 'values.name as valuename')
            ->get();
        if (!$rows)
            return false;
       $parameters = [];
       foreach ($rows as $r)
           $parameters[] = [
               'id' => $r['id'],
               'name' => $r['address'] . ' / ' . $r['name'] . ' = ' . $r['valueaddress'] . ' / ' . $r['valuename']
           ];
       return $parameters;
    }
}