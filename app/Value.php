<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Input;
use Illuminate\Support\Facades\Validator;

class Value extends Eloquent {
    protected $table = 'values';
    
    public $timestamps = false;
    
    protected $fillable = ['address', 'name' ,'sequence', 'parameter_id'];
    
    public static function add()
    {
        $data = Input::all();
        
        $validation = Validator::make($data, [
            'name'          => 'required|max:255|min:1',
            'parameter_id'  => 'required|exists:parameters,id'
        ]);
        if ($validation->fails())
            return ['errors' => $validation->messages()];
        
        $parameter_id = $data['parameter_id'];
        $validation2 = Validator::make($data, [
            'address' => ['required', 'unique:values,address,NULL,id,parameter_id,'.$parameter_id, 'regex:/^[-_0-9a-z]+$/', 'max:255', 'min:1']
        ]);
        if ($validation2->fails())
            return ['errors' => $validation2->messages()];
        
        $sequence = self::where('parameter_id', $data['parameter_id'])->max('sequence') + 1;
        
        self::create([
            'address' => $data['address'],
            'name' => $data['name'],
            'parameter_id' => $data['parameter_id'],
            'sequence' => $sequence
        ]);
        
        return ['message' => 'Параметр успешно создан', 'parameter_id'=>$data['parameter_id']];
    }
    
    public static function edit($id)
    {
        $data = Input::all();
        
        $value = self::where('id', $id)->select('parameter_id')->first();
        if (!$value)
        {return ['errors' => 'Не существует такого значения. Оно было удалено ранее.'];}
        $parameter_id = $value['parameter_id'];
        
        $validation = Validator::make($data, [
            'address' => ['required', 'unique:values,address,'.$id.',id,parameter_id,'.$parameter_id, 'regex:/^[-_0-9a-z]+$/', 'max:255', 'min:1'],
            'name'    => ['required', 'unique:values,name,' . $id, 'max:255', 'min:1']
        ]);
        if ($validation->fails())
            return ['errors' => $validation->messages()];
        
        self::whereId($id)->update([
            'address' => $data['address'],
            'name' => $data['name']
        ]);
        
        return ['message' => 'Изменено.', 'parameter_id' => $parameter_id];
    }
    
    public static function up($id)
    {
        $row = self::whereId($id)->select('parameter_id', 'sequence')->first();
        if (!$row)
            return ['error' => 'Нет такого.'];
        if ($row['sequence'] < 2)
            return ['error' => 'Уже первый.'];
        self::where('parameter_id',$row['parameter_id'])
            ->whereSequence($row['sequence']-1)
            ->increment('sequence');
        self::whereId($id)->decrement('sequence');
        return ['message' => 'Пункт повышен!', 'parameter_id' => $row['parameter_id']];
    }
    
    public static function down($id)
    {
        $row = self::whereId($id)->select('parameter_id', 'sequence')->first();
        if (!$row)
            return ['error' => 'Нет такого.'];
        $maxSequence = self::where('parameter_id',$row['parameter_id'])->max('sequence');
        if ($row['sequence'] >= $maxSequence)
            return ['error' => 'Уже последний.'];
        self::where('parameter_id',$row['parameter_id'])
            ->whereSequence($row['sequence']+1)
            ->decrement('sequence');
        self::whereId($id)->increment('sequence');
        return ['message' => 'Пункт понижен!', 'parameter_id' => $row['parameter_id']];
    }
    
    public static function del($id)
    {
        $row = self::whereId($id)->select('name','parameter_id','sequence')->first();
        if (!$row)
            return ['error' => 'Не найден. Видимо, удалён ранее.'];
        
        self::where('parameter_id',$row['parameter_id'])
            ->where('sequence','>',$row['sequence'])
            ->decrement('sequence');
        self::whereId($id)->delete();
        
        return ['message' => '"' . $row['name'] . '" удалён!', 'parameter_id' => $row['parameter_id']];
    }

    
    public static function getAllFor($product_id, $parameter_id)
    { // получение связки не занятых  значений для product_id и parameter_id
      // это для селекта в админке - прикрепление нового параметра к продукту:
      // если уже есть жёлтые и красные мокасины, а цвет бывает ещё и зелёным, то в селекте вернётся только зелёный
        $rows = \DB::table('product_value')
            ->where('product_id', $product_id)
            ->select('value_id')
            ->get();
        $not = $rows ? array_fetch($rows, 'value_id') : [];
        
        $rows = self::where('parameter_id', $parameter_id)
            ->whereNotIn('id', $not)
            ->select('*')
            ->orderBy('sequence', 'asc')
            ->get();
        
        if (!$rows)
            return false;
        
        $values = [];
        foreach ($rows as $r)
            $values[$r['id']] = $r['address'] . ' / ' . $r['name'];
       
        return $values;
    }
    
    
    public static function getForParameter($parameter_id)
    { // получение всех значений параметра (цвет: жёлтый, красный...)
        $values = self::where('parameter_id', $parameter_id)
            ->select('*')
            ->orderBy('sequence','asc')
            ->get();
        if (!$values)
            return false;
        return  $values->toArray();
    }
}