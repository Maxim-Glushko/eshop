<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Input;
use App\Picture;
use Illuminate\Support\Facades\Validator;
use Redirect;

class Product extends Eloquent {
    
    protected $table = 'products';
    
    protected $fillable = [
        'address','name','title','description','keywords','text','price',
        'discount','vendorcode','availability','main'
    ];
    
    
    protected static $forPage = 30; // сколько продуктов показывать на одной странице
    // какая-то неведомая хуйня происходит, когда хочу назвать $perPage
    // из-за того, что он существует в родительском классе
    
    
    public static function getContents($product_id)
    { // получить все категории, к которым привязан этот товар
      // первой будет идти главная категория
        $contents = \App\Content::join('product_content','contents.id','=','product_content.content_id')
            ->where('product_id', $product_id)
            ->orderBy('main','desc')
            ->select('content_id as id', 'name', 'title', 'keywords', 'description', 'parent', 'address')
            ->get();
        if (!$contents || !count($contents))
            return false;
        return $contents->toArray();
    }
    
    
    public function scopeChoiceProducts($query, $id, $parameters)
    {
        unset($parameters['page'], $parameters['orderby']);
        
        $query->join('product_content', 'products.id', '=', 'product_content.product_id')
            ->where('availability', 1)
            ->where('product_content.content_id', $id);
            //->whereRaw('availability=1')
            //->whereRaw('product_content.content_id ='. $id);
        
        $query->select('products.id as id', 'products.address as address', 'products.name as name',
            'products.description as description', 'products.price as price',
            'products.discount as discount', 'products.created_at as created_at');
        
        if (!count($parameters))
            return $query->groupBy('products.id');
        
        $query->join('product_value', 'products.id', '=', 'product_value.product_id')
            ->join('values', 'product_value.value_id', '=', 'values.id')
            ->join('parameters', 'values.parameter_id', '=', 'parameters.id');
        
        $query->where(function($query) use ($parameters) {
            $i = 0;
            foreach ($parameters as $k=>$v)
            {
                if (!$i)
                    $query->where(function($query) use($k, $v){
                        $query->where('parameters.address', $k)->whereIn('values.address', $v);
                        //$query->whereRaw('parameters.address = \'' . $k . '\' and values.address in (\'' . implode('\',\'', $v) . '\')');
                    });
                else
                    $query->orWhere(function($query) use($k, $v){
                        $query->where('parameters.address', $k)->whereIn('values.address', $v);
                        //$query->whereRaw('parameters.address = \'' . $k . '\' and values.address in (\'' . implode('\',\'', $v) . '\')');
                    });
                $i++;
            }
        });
        
        $query->groupBy('products.id', 'parameters.id');
        
        $query2 = \DB::table(\DB::raw(" ( {$query->toSql()} ) as `t` "))
            ->mergeBindings($query->getQuery())
            ->select(\DB::raw('t.*, count(t.id) as count'))
            ->groupBy('t.id')
            ->having('count', '=', count($parameters));
            //->having(\DB::raw('`count` = ' . count($parameters)));
        
        return $query2;
    }
    
    
    protected static $parametersArray = false;
    
    public static function remakeParameters($parameters)
    { // преобразование строки 'page=2;color=white:red;size=43'
      // в массив ['page'=>['2'], 'color'=>['white','red'], 'size'=>['43']]
        if (self::$parametersArray)
            return self::$parametersArray;
        if (!$parameters)
            return ['page'=>[1], 'orderby'=>['date', 'desc']];
        
        $parameters = explode(';', $parameters);
        $p = [];
        for ($i=0; $i<count($parameters); $i++)
        {
            $part = explode('=', $parameters[$i]);
            if ($part[0] && $part[1])
                $p[$part[0]] = explode(':', $part[1]);
        }
        if (!isset($p['page']))
        {
            $p['page'] = [];
            $p['page'][] = 1;
        }
            
        $p['page'][0] = intval($p['page'][0]);
        
        if (!isset($p['orderby']) || !is_array($p['orderby']))
            $p['orderby'] = ['date','desc'];
        
        if (!in_array($p['orderby'][0], ['date', 'price']))
            $p['orderby'][0] = 'date';
        
        if (!in_array($p['orderby'][1], ['asc', 'desc']))
            $p['orderby'][1] = 'desc';

        return self::$parametersArray = $p;
    }
    
    
    public static function getProducts($id, $strparameters='')
    { // получение всех минимальных данных продуктов на одну страницу заданной категории
        $parameters = self::remakeParameters($strparameters);
        
        $orderby = $parameters['orderby'];
        if ($orderby[0] == 'date')
            $orderby[0] = 'created_at';

        $products = self::choiceProducts($id, $parameters)
            ->orderBy($orderby[0], $orderby[1])
            ->skip(self::$forPage * ($parameters['page'][0] - 1))
            ->take(self::$forPage)
            ->get();

        if ($products)
            $products = json_decode(json_encode($products), true);

        foreach ($products as $k=>$v)
            $products[$k]['price'] = \App\Constant::convertPrice($products[$k]['price']);

        // + получить картинки; одним запросом не получить - и так наворочено было
        $ids = [];
        foreach ($products as $p)
            $ids[] = $p['id'];
        $pictures = Picture::getForItems($ids);
        for ($i=0; $i<count($products); $i++)
            foreach ($pictures as $p)
                if ($p['item_id'] == $products[$i]['id'])
                    $products[$i]['picture'] = $p['sizes'];
                    // или можно и вовсе один размер выдать; пока даю все - чтобы выбор был
        //dd($products);
        return $products;
    }
    
    
//    public static function getParameters($product_id)
//    { // узнать параметры и значения для продукта - просто слова
//        $rows = self::join('product_value','products.id','=', 'product_value.product_id')
//            ->join('values', 'product_value.value_id', '=', 'values.id')
//            ->join('parameters', 'values.parameter_id', '=', 'parameters.id')
//            ->where('product_id', $product_id)
//            ->orderBy('parameter_id', 'asc')
//            ->orderBy('value_id', 'asc')
//            ->select('parameters.name as parameter', 'values.name as value')
//            ->get();
//        if (!$rows || !count($rows))
//            return false;
//        
//        return $rows->toArray();
//    }
    
    
    public static function getParameters($product_id)
    { // узнать параметры и значения для продукта - просто слова
        $rows = self::join('product_value','products.id','=', 'product_value.product_id')
            ->join('values', 'product_value.value_id', '=', 'values.id')
            ->join('parameters', 'values.parameter_id', '=', 'parameters.id')
            ->where('product_id', $product_id)
            ->orderBy('parameter_id', 'asc')
            ->orderBy('value_id', 'asc')
            ->select('parameters.id as id', 'parameters.name as name', 'parameters.address as address',
                'values.id as value_id', 'values.name as value_name', 'values.address as value_address',
                'for_order')
            ->get();
        if (!$rows || !count($rows))
            return false;
        $rows = $rows->toArray();
        $parameters = [];
        foreach ($rows as $r)
        {
            if (!isset($parameters[$r['id']]))
            {
                $parameters[$r['id']] = [
                    'id' => $r['id'],
                    'address' => $r['address'],
                    'name' => $r['name'],
                    'for_order' => $r['for_order']
                ];
            }
            $parameters[$r['id']]['values'][] = [
                'id' => $r['value_id'],
                'address' => $r['value_address'],
                'name' => $r['value_name']
            ];
        }
        return $parameters;
    }
    
    
    public static function getProductsForIds(array $ids)
    { // получение всех минимальных данных продуктов для корзины
      // + нужны адреса категорий

        $products = self::join('product_content', 'products.id', '=', 'product_content.product_id')
            ->join('contents', 'product_content.content_id', '=', 'contents.id')
            ->leftJoin('pictures', 'products.id', '=', 'pictures.item_id')
            ->whereIn('products.id', $ids)
            ->where(['availability'=>1, 'main'=>1])
            ->select('product_id as id', 'products.address as address', 'contents.address as contentaddress',
                'products.name as name', 'products.description as description', 'price', 'discount',
                'src', 'type', 'vendorcode', 'pictures.id as picture_id')
            ->orderby('price', 'desc')
            //->orderBy(\DB::raw('FIELD(products.id, ' . implode(',', $ids) . ')'))
            ->orderBy('pictures.sequence', 'asc')
            ->groupBy('products.id')
            ->get();
            //->toSql();
        if (!$products)
            return false;
        $products = $products->toArray();

        for ($i=0; $i<count($products); $i++)
        $products[$i]['price'] = \App\Constant::convertPrice($products[$i]['price']);
 
        return $products;
    }
    
    
    public static function paginator($id, $address, $parameters)
    { // отдаёт массив ссылок на другие страницы такого же набора модификаторов
        $links = [];

        $parameters = self::remakeParameters($parameters);

        //$total = self::choiceProducts($id, $parameters)->count();
        // какая-то неведомая хуйня выходит, не могу исправить
        $total = self::choiceProducts($id, $parameters)->get();
        if (!$total || !count($total))
            $total = 0;
        $total = ($total && count($total)) ? count($total) : 0;
        //dd($total);
        $lastPage = ceil($total / self::$forPage);

        if ($lastPage < 2)
            return false; // если продуктов менее, чем на 2 страницы, пагинатор не нужен

        for ($i=0; $i<$lastPage; $i++)
        {
            $p = self::plusParameter($parameters, 'page', $i+1);
            $links[$i] = [
                'num' => $i+1,
                'current' => ($parameters['page'][0] == ($i+1)) ? true : false,
                'url' => self::parametersToUrl($address, $p)
            ];
        }
        return $links;
    }
    
    
    public static function filter($id, $address, $parametersStr)
    { // нужно отдать массив ссылок - заготовку под модификатор слева, как на розетке
      // каждый элемент массива содержит ссылку с набором параметров
        $rows = self::join('product_content', 'products.id', '=', 'product_content.product_id')
            ->join('product_value', 'products.id', '=', 'product_value.product_id')
            ->join('values', 'product_value.value_id', '=', 'values.id')
            ->join('parameters', 'values.parameter_id', '=', 'parameters.id')
            ->where('content_id', $id)
            ->orderBy('parameters.sequence', 'asc')
            ->orderBy('values.sequence', 'asc')
            ->select(/*\DB::raw('count(values.address) as count'),*/
                'parameters.address as parameter_address', 'parameters.name as parameter_name',
                'values.address as value_address', 'values.name as value_name')
            ->groupBy('parameter_address', 'value_address')
            ->get();
        if ($rows)
            $rows = $rows->toArray();

        $parameters = self::remakeParameters($parametersStr);
        if (isset($parameters['page']))
            unset($parameters['page']);

        $filter = [];
        $params = [];
        $i = 0;
        foreach ($rows as $r)
        {
            if (!in_array($r['parameter_address'], $params))
            {
                $params[] = $r['parameter_address'];
                $filter[$i] = [
                    'address' => $r['parameter_address'],
                    'name' => $r['parameter_name'],
                    'values' => []
                ];
                $i++;
            }
        }
        unset($params);
        
        foreach ($rows as $r)
        {
            for ($i=0; $i<count($filter); $i++)
            {
                if ($r['parameter_address'] == $filter[$i]['address'])
                {
                    $p = self::plusParameter($parameters, $r['parameter_address'], $r['value_address']);
                    $checked = (isset($parameters[$r['parameter_address']]) && in_array($r['value_address'], $parameters[$r['parameter_address']])) ? true : false;
                    $filter[$i]['values'][] = [
                        'address' => $r['value_address'],
                        'name' => $r['value_name'],
                        'url' => self::parametersToUrl($address, $p), // куда переходить при нажатии на галку
                        'checked' => $checked // показывать галку?
                    ];
                }
            }
        }
        return $filter;
    }
    
    
    public static function orderByForSelect($address, $parametersStr)
    { // данные для селекта вверху страницы: сортировать по...
      // нужная инфа: выден ли уже, и адрес, по которому уходим, если сеектед
        $orderBy = [
            'select' => [
                'date-desc' => 'сначала новые',
                'date-asc'  => 'сначала старые',
                'price-desc'=> 'сначала дорогие',
                'price-asc' => 'сначала дешёвые'
            ],
            'selected' => 'date-desc',
            'url' => []
        ];
        $parameters = self::remakeParameters($parametersStr);
        $key = $parameters['orderby'][0].'-'.$parameters['orderby'][1];
        $orderBy['selected'] = $key;
        foreach ($orderBy['select'] as $k=>$v)
        {
            $p = self::plusParameter($parameters, 'orderby', explode('-', $k));
            $orderBy['url'][$k] = self::parametersToUrl($address, $p);
        }
        return $orderBy;
    }
    
    
    public static function plusParameter($parameters, $param, $value)
    { // добавляет или отнимает параметр со значением
      // входит массив параметров, адрес параметра, адрес значения
      // возвращает массив параметров
      // входящий $value лишь у 'orderby' может быть массивом
        if ($param == 'page')
        {
            $parameters['page'] = [];
            $parameters['page'][] = $value;
        }
        elseif ($param == 'orderby')
        {
            $parameters['orderby'] = $value;
            // эта добавка единственная может прийти в виде массива ['price','desc']
        }
        elseif (isset($parameters[$param]))
        {
            if(($key = array_search($value, $parameters[$param])) !== FALSE)
            {
                unset($parameters[$param][$key]);
                if (!count($parameters[$param]))
                    unset($parameters[$param]);
                // если удалить из 'size'=>[43] единственный размер, нет смысла в пустом параметре
            }
            else
            {
                $parameters[$param][] = $value;
            }
        }
        else
        {
            $parameters[$param] = [];
            $parameters[$param][] = $value;
        }
        return $parameters;
    }
    
    
    public static function parametersToUrl($address, $parameters)
    { // принимает адрес категории 'kolesa'
      // и массив параметров ['color'=>['red','white'],'size'=>[43],'page'=>[3],'orderby'=>['price','asc']]
      // отдаёт строку /kolesa/color=red:white;size=43;page=3;orderby=price:asc
        
        if (($parameters['orderby'][0] == 'date') && ($parameters['orderby'][1] == 'desc'))
            unset($parameters['orderby']);
        if (isset($parameters['page']) && $parameters['page'][0] == 1)
            unset($parameters['page']);

        $url = '/' . $address ;
        if (count($parameters))
        {
            $str = [];
            foreach ($parameters as $k=>$v)
                $str[] = $k . '=' . implode(':',$v);
            $url .= '/' . implode(';' , $str);
        }
        return $url;
    }
    
    
    public static function getValidationRules($id = 0)
    { // $id нужен, чтобы игнорировать сравнение с адресом своей же статьи
        $id = $id ? (',' . $id) : '';
        return [
            'address'       => ['required', 'unique:products,address' . $id, 'regex:/^[-_0-9a-z]+$/', 'min:1', 'max:255'],
            'name'          => 'required|min:1|max:255',
            'title'         => 'max:64000',
            'description'   => 'max:64000',
            'keywords'      => 'max:64000',
            'text'          => 'max:64000',
            'price'         => 'digits_between:0.00,99999999.99',
            'discount'      => 'digits_between:0.00,99.99',
            'vendorcode'    => 'max:255',
            'availability'  => 'sometimes|boolean',
            'main'          => 'required|exists:contents,id|not_in:1', // основная категория этого продукта
            'content_ids'   => 'sometimes|array|not_in:1|exists:contents,id', // другие категории
        ];
    }
    
    
    public static function getAdminContent($content_id)
    { // вывод всех продуктов данной категории для админки,
      //начиная с последних, вне зависимости от актуальности (availability)
        $products = self::join('product_content', 'products.id', '=', 'product_content.product_id')
            ->where('content_id', $content_id)
            ->orderBy('created_at', 'desc')
            ->select('products.id as id', 'name', 'availability')
            ->get();
        if ($products)
            $products = $products->toArray();
        return $products;
    }
    
    
    public static function add()
    {
        $data = Input::all();
        
        $validation = Validator::make($data, self::getValidationRules());
        if ($validation->fails())
            return ['errors' => $validation->messages()];
            //return Redirect::back()->withErrors($validation)->withInput();
        
        $product = self::create([
            'address'       => $data['address'],
            'name'          => $data['name'],
            'title'         => $data['title'],
            'description'   => $data['description'],
            'keywords'      => $data['keywords'],
            'text'          => $data['text'],
            'price'         => $data['price'],
            'discount'      => $data['discount'],
            'vendorcode'    => $data['vendorcode'],
            'availability'  => 1
        ]);
        
        $d = [];
        $d[] = [
            'product_id'    => $product['id'],
            'content_id'    => $data['main'],
            'main'          => 1
        ];
        if (isset($data['content_ids']))
        {
            if(($key = array_search($data['main'], $data['content_ids'])) !== FALSE)
                unset($data['content_ids'][$key]);
            foreach ($data['content_ids'] as $content_id)
            {
                $d[] = [
                    'product_id'    => $product['id'],
                    'content_id'    => $content_id,
                    'main'          => 0
                ];
            }
        }
        \DB::table('product_content')->insert($d);
        
        return ['id' => $product['id'], 'message' => 'Продукт создан!'];
    }
    
    
    
    public static function edit($id)
    {
        $data = Input::all();
        
        $validation = Validator::make($data, self::getValidationRules($id));
        if ($validation->fails())
            return ['errors' => $validation->messages()];
            //return \Redirect::back()->withErrors($validation)->withInput();
        
        self::whereId($id)->update([
            'address'       => $data['address'],
            'name'          => $data['name'],
            'title'         => $data['title'],
            'description'   => $data['description'],
            'keywords'      => $data['keywords'],
            'text'          => $data['text'],
            'price'         => $data['price'],
            'discount'      => $data['discount'],
            'vendorcode'    => $data['vendorcode'],
            'availability'  => isset($data['availability']) ? 1 : 0
        ]);
        
        \DB::table('product_content')->where('product_id', $id)->delete();
        
        $d = [];
        $d[] = [
            'product_id'    => $id,
            'content_id'    => $data['main'],
            'main'          => 1
        ];
        if (isset($data['content_ids']))
        {
            if(($key = array_search($data['main'], $data['content_ids'])) !== FALSE)
                unset($data['content_ids'][$key]);
            foreach ($data['content_ids'] as $content_id)
            {
                $d[] = [
                    'product_id'    => $id,
                    'content_id'    => $content_id,
                    'main'          => 0
                ];
            }
        }
        \DB::table('product_content')->insert($d);
        return ['message' => 'Продукт отредактирован!'];
    }
    
    
    public static function del($id)
    {
        $row = self::whereId($id)->select('name')->first();
        if (!$row)
            return ['error' => 'Продукт не найден. Видимо, он был удалён ранее.'];
        
        Picture::itemDel($id);// удалить файлы миниатюр, чтобы не засорялось
        self::whereId($id)->delete();
        
        return ['message' => 'Продукт "' . $row['name'] . '" удалён!'];
    }
}