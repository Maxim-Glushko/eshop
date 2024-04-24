<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Input;
use Illuminate\Support\Facades\Validator;
use Redirect;

class Order extends Eloquent {
    
    protected $table = 'orders';
    
    protected $fillable = ['name','email','phone','address','text','processed'];
    
    public static $forPage = 30;

    public static function getGeneralAmount()
    { // получить общее количество товара в корзине для показа вверху страницы

        $amount = 0;
        $cart = \Session::get('cart', []);
        foreach ($cart as $c)
            $amount += $c['amount'];
        return $amount;
    }
    
    
    public static function getCart()
    { // получить данные корзины
      // id продуктов и их количество и параметры из сессии
      // остальную инфу из базы данных
        $ids = [];
        $products = \Session::get('cart', []);
        foreach ($products as $p)
            $ids[] = $p['product_id'];
        if (!$ids || !count($ids))
            return false;

        $rows = Product::getProductsForIds($ids);
        if (!$rows || !count($rows))
            return false;
        for($i=0; $i<count($rows); $i++)
        {
            $picture = Picture::checkFiles(['src'=>$rows[$i]['src'], 'type'=>$rows[$i]['type']]);
            $rows[$i]['picture'] = $picture['sizes'][1];
            $rows[$i]['parameters_names'] = '';
            $rows[$i]['parameter_value'] = '';
        }
        
        for($i=0; $i<count($products); $i++)
            foreach ($rows as $r)
                if ($products[$i]['product_id'] == $r['id'])
                    $products[$i] += $r;
                    //foreach ($r as $k=>$v)
                        //$products[$i][$k] = $v;
        $par_ids = [];
        $val_ids = [];
        foreach ($products as $c)
            if (isset($c['parameters']) && $c['parameters'])
                foreach ($c['parameters'] as $p=>$v)
                {
                    $par_ids[] = $p;
                    $val_ids[] = $v;
                }
        if (count($par_ids))
        {
            $parameters = \App\Parameter::select('*')->whereIn('id',$par_ids)->get();
            $values = \App\Value::select('*')->whereIn('id',$val_ids)->get();
            
            if ($parameters && $values)
            for($i=0; $i<count($products); $i++)
            {
                if ($products[$i]['parameters'])
                {
                    $products[$i]['parameters_names'] = '';
                    $products[$i]['parameter_value'] = '';
                    foreach ($parameters as $p)
                        if (isset($products[$i]['parameters'][$p['id']]))
                        {
                            $products[$i]['parameters_names'] .= $p['name'];
                            $products[$i]['parameter_value'] .= $p['id'];
                            $id = $p['id'];
                            foreach ($values as $v)
                                if ($products[$i]['parameters'][$id] == $v['id'])
                                {
                                    $products[$i]['parameters_names'] .= ': ' . $v['name'] . '<br />';
                                    $products[$i]['parameter_value'] .= '=' . $v['id'] . ';';
                                }
                        }
                }
            }
        }
        return $products;
    }
    
    
    public static function getCustomer()
    { // ккладём из формы в сессию данные о юзере,
      // т.к. со страницы корзины юзер может и уйти, а потом пришлось бы ему заново их вводить
        $data = Input::all();
        $array = ['name', 'email', 'phone', 'address', 'text'];
        foreach ($array as $a)
            if (isset($data[$a]))
                \Session::put('customer.'.$a, $data[$a]);
            else
                \Session::get('customer.'.$a, '');
        return \Session::get('customer');
    }
    
    
    public static function changeAmount()
    {
        $data = Input::all();
        $type = $data['type'];

        if ($type!='show')
        {
            $id = $data['id'];

            $cart = array_values(\Session::get('cart', []));
            //dd($cart);

            for($i=0; $i<count($cart); $i++)
            {
                if ($cart[$i]['product_id'] == $id)
                {
                    if (isset($data['parameter_value']) && $data['parameter_value'])
                    {
                        $cart[$i]['parameter_value'] = '';
                        foreach ($cart[$i]['parameters'] as $p=>$v)
                            $cart[$i]['parameter_value'] .= $p . '=' . $v . ';';
                        if ($cart[$i]['parameter_value'] == $data['parameter_value'])
                        {
                            switch ($type)
                            {
                                case 'del': unset($cart[$i]); break;
                                case 'change': $cart[$i]['amount'] = intval($data['amount']); break;
                                default: break;
                            }
                        }
                    }
                    else
                    {
                        switch ($type)
                        {
                            case 'del': unset($cart[$i]); break;
                            case 'change': $cart[$i]['amount'] = intval($data['amount']); break;
                            default: break;
                        }
                    }
                    if (isset($cart[$i]) && ($cart[$i]['amount'] < 1))
                        unset($cart[$i]);
                }
            }
            
            $cart = array_values($cart);
            
            \Session::put('cart', $cart);
            
        }
    }
    
    
    public static function addProduct($id, $amount)
    { // добавить заказ в корзину
        $data = Input::all();
        if (intval($amount)<1)
            return 'Возникла ошибка. Невразумительное количество.';
        $id = intval($id);
        $product = Product::find($id)->select('id');
        if (!$product)
            return 'Возникла ошибка. Нет такого товара.';
        
        if (isset($data['parameters']))
            foreach ($data['parameters'] as $k=>$v)
                if ($v == "0")
                {
                    $row = \App\Parameter::select('name')->whereId($k)->first();
                    return 'Пожалуйста, выберите параметр: ' . $row['name'];
                }
        
        $amount = intval($amount);
        $flag = false;

        $cart = \Session::get('cart', []);
        
        function array_compare($array1, $array2)
        { // одинаковы ли два одноуровневых масива
            if (count($array1) != count($array2)) return false;
            foreach ($array1 as $k=>$v)
            {
                if (!isset($array2[$k])) return false;
                if ($v != $array2[$k])   return false;
            }
            return true; // одинаковы
        }
        
        $flag = false; // по умолчанию такого товара в корзине не было, дальше проверка обратного
        for ($i=0; $i<count($cart); $i++)
        {
            if ($cart[$i]['product_id'] == $id)
                if (isset($cart[$i]['parameters']) && $cart[$i]['parameters'])
                {
                    if (isset($data['parameters']))
                        if (array_compare($data['parameters'], $cart[$i]['parameters']))
                        {
                            $flag = true;
                            $cart[$i]['amount'] += $amount;
                            // если такой товар с такии же параметрами уже был в корзине,
                            // прибавляем количество
                        }
                }
                else
                {
                    $flag = true;
                    $cart[$i]['amount'] += $amount;
                    // если такой товар без обязательных параметров уже был в корзине,
                    // прибавляем количество
                }
        }
        
        if (!$flag)
        { // такого товара не было в корзине - добавляем новый член массива
            $cart[] = [
                'product_id' => $id,
                'amount' => $amount,
                'parameters' => isset($data['parameters']) ? $data['parameters'] : false
            ];
        }
        
        \Session::put('cart', $cart);
        return '';
    }
    
    
    public static function paginator($page)
    {
        $total = self::count();
        
        $lastPage = ceil($total / self::$forPage);
        
        if ($lastPage < 2)
            return false; // если менее, чем на 2 страницы, пагинатор не нужен
        
        $links = [];
        for ($i=1; $i<($lastPage+1); $i++)
        {
            $links[] = [
                'num' => $i,
                'current' => ($page == $i) ? true : false,
                'url' => '/admin/order/' . $i
            ];
        }
        return $links;
    }
}