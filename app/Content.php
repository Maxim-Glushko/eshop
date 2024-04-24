<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Input;
use App\Picture;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Redirect;

class Content extends Eloquent {
    
    protected $table = 'contents';
    
    protected $fillable = ['address', 'name', 'title', 'description', 'keywords', 'text', 'parent', 'sequence'];
    
    
    protected static $menu = NULL;
    
    public static function getMenu()
    {
        if (!self::$menu)
        {
//            self::$menu = self::select('id','address','name','description','parent','sequence')
//                ->orderBy('parent', 'asc')
//                ->orderBy('sequence', 'asc')
//                ->get();
            
            self::$menu = Content::leftJoin('product_content', 'contents.id', '=', 'product_content.content_id')
                ->select(\DB::raw('count(product_id) as count, contents.id as id, name, address, description, parent, contents.sequence'))
                //->select('id','name','address','parent','sequence')
                ->groupBy('contents.id')
                ->orderBy('parent', 'asc')
                ->orderBy('contents.sequence', 'asc')
                ->get()
                ->toArray();
        
            foreach (self::$menu as $k=>$v)
                self::$menu[$k]['hasChildren'] = false;
        
            foreach (self::$menu as $k=>$v)
                foreach (self::$menu as $k1=>$v1)
                    if ($v['parent'] == $v1['id'])
                        self::$menu[$k1]['hasChildren'] = true;
        }
        return self::$menu;
    }
    
    public static function getMenuWithPictures()
    { // выловить данные для формирования менюшки
      // + лицевые картинки
        
        if (!self::$menu)
            self::$menu = self::getMenu();

        $pictures = Cpicture::getMenuFaces();
        $ids = [];
        foreach ($pictures as $p)
            for ($i=0; $i<count(self::$menu); $i++)
                if ($p['item_id'] == self::$menu[$i]['id'])
                    self::$menu[$i]['picture'] = $p['sizes'];
            
        for ($i=0; $i<count(self::$menu); $i++)
            if (!isset(self::$menu[$i]['picture']))
            {
                $p = Picture::checkFiles(['src'=>Picture::$defaultPicture, 'type'=>'deform']);
                self::$menu[$i]['picture'] = $p['sizes'];
            }
                
        return self::$menu;
    }
    
    
    
    protected static $breadcrumb = null;
    
    public static function getBreadcrumb($id)
    {//не забыть, что изначально у меня было всего 2-уровневое меню
        if (!self::$breadcrumb)
        {
            self::$breadcrumb = false;
            
            $menu = self::getMenu();
            
            foreach ($menu as $m)
                if ($m['id'] == $id)
                {
                    self::$breadcrumb[0]['name'] = $m['name'];
                    self::$breadcrumb[0]['address'] = $m['address'];
                    
                    if ($m['parent'])
                    {
                        foreach ($menu as $m2)
                        {
                            if ($m2['id'] == $m['parent'])
                            {
                                self::$breadcrumb[1]['name'] = $m2['name'];
                                self::$breadcrumb[1]['address'] = $m2['address'];
                            }
                        }
                    }
                }
        }
        return self::$breadcrumb;
    }
    
    
    
    protected static $parents = NULL;
    
    public static function getParents()
    { // получение списка родителей старшего уровня для выбора родителя в админку
        if (!self::$parents)
        {
            self::$parents[0] = '-- корень --';
            $menu = self::getMenu();
            if ($menu)
                foreach ($menu as $m)
                    if (!$m['parent'])
                        self::$parents[$m['id']] = $m['name'];
        }
        return self::$parents;
    }
    
    
    protected static $cats = NULL;
    
    public static function getCats()
    { // получение двух уровней меню для выбора главной категории в редактировании продукта
        if (!self::$cats)
        {
            $menu = self::getMenu();
            if ($menu)
            {
                foreach ($menu as $m)
                {
                    if (!$m['parent'])
                    {
                        self::$cats[$m['id']] = $m['name'];
                        foreach ($menu as $m1)
                        {
                            if ($m1['parent'] == $m['id'])
                            {
                                self::$cats[$m1['id']] = '-- ' . $m1['name'];
                            }
                        }
                    }
                }
            }
        }
        return self::$cats;
    }
    
    
    protected static $mycats = NULL;
    
    public static function getMyCats($id)
    { // получение id категорий, к которым прикреплён продукт - это нужно для форм add/edit
        if (!self::$mycats)
        {
            $mycats = \DB::table('product_content')
                ->select('content_id')
                ->where('product_id', $id)
                ->get();
            self::$mycats = $mycats ? array_fetch($mycats, 'content_id') : false;
        }
        return self::$mycats;
    }
    
    
    
    
    public static $messages = [
        'parent.not_in' => 'Страница не может быть родителем самой себе.'
    ];
    
    public static function getValidationRules($id=0)
    { // $id нужен, чтобы игнорировать сравнение с адресом своей же статьи
        $id = $id ? (','.$id) : '';
        $not = $id ? ('|not_in:'.$id) : '';
        
        return [
            'address'       => ['unique:contents,address'.$id, 'regex:/^[-_0-9a-z]+$/', 'max:255'],
            'name'          => 'required|min:1|max:255',
            'title'         => 'max:64000',
            'description'   => 'max:64000',
            'keywords'      => 'max:64000',
            'text'          => 'max:64000',
            'parent'        => 'integer|min:0'.$not // не является ли страница родителем самой себя
        ];
    }
    
    
    
    private static $sequence = NULL;
    
    public static function getSequence($parent)
    { // получение актуального для новой статьи порядкового номера
        if (!self::$sequence)
            self::$sequence = self::where('parent',$parent)->max('sequence') + 1;
        return self::$sequence;
    }
    
    
    
    public static function changeSequence($id, $parent)
    { // изменение sequence себя и прежних младших сестёр, если переписали под другого родителя
        $old = self::whereId($id)->select('parent','sequence')->first();
        if ($old['parent'] != $parent)
        {
            self::where('sequence','>',$old['sequence'])->whereParent($old['parent'])->decrement('sequence');
            $sequence = self::getSequence($parent);
            self::whereId($id)->update(['sequence'=>$sequence]);
        }
    }
    
    
    
    public static function add()
    {
        $data = Input::all();
        
        $validation = Validator::make($data, self::getValidationRules(), self::$messages);
        if ($validation->fails())
            return ['errors' => $validation->messages()];
            //return Redirect::back()->withErrors($validation)->withInput();
        
        if (!$data['address'])
            return ['errors' => ['Только главная может быть без адреса.']];
        
        $content = Content::create([
            'address'       => $data['address'],
            'name'          => $data['name'],
            'title'         => $data['title'],
            'description'   => $data['description'],
            'keywords'      => $data['keywords'],
            'text'          => $data['text'],
            'parent'        => $data['parent'],
            'sequence'      => self::getSequence($data['parent'])
        ]);
        
        return ['id'=>$content['id'], 'message'=>'Страница создана!'];
    }
    
    
    
    public static function edit($id)
    {
        $data = Input::all();
        //unset($data['_token']);
        
        $validation = Validator::make($data, self::getValidationRules($id), self::$messages);
        if ($validation->fails())
            return ['errors' => $validation->messages()];
            //return Redirect::back()->withErrors($validation)->withInput();
        
        if ($id == 1 && $data['address'])
            return ['errors' => ['Главная страница не должна иметь адрес.']];
        
        if ($id > 1 && !$data['address'])
            return ['errors' => ['Только главная может быть без адреса.']];
        
        self::changeSequence($id, $data['parent']);
 
        self::whereId($id)->update([
            'address'       => $data['address'],
            'name'          => $data['name'],
            'title'         => $data['title'],
            'description'   => $data['description'],
            'keywords'      => $data['keywords'],
            'text'          => $data['text'],
            'parent'        => $data['parent']
        ]);
        return ['message' => 'Страница отредактирована!'];
    }
    
    
    
    public static function up($id)
    {
        $row = self::whereId($id)->select('parent','sequence')->first();
        if (!$row)
            return ['error' => 'Нет такой статьи.'];
        if ($row['sequence'] < 2)
            return ['error' => 'Эта статья уже первая под своим родителем.'];
        self::whereParent($row['parent'])->whereSequence($row['sequence']-1)->increment('sequence');
        self::whereId($id)->decrement('sequence');
        return ['message' => 'Пункт повышен!'];
    }
    
    
    
    public static function down($id)
    {
        $row = self::whereId($id)->select('parent','sequence')->first();
        if (!$row)
            return ['error' => 'Нет такой статьи.'];
        $maxSequence = self::whereParent($row['parent'])->max('sequence');
        if ($row['sequence'] >= $maxSequence)
            return ['error' => 'Эта статья уже последняя под своим родителем.'];
        self::whereParent($row['parent'])->whereSequence($row['sequence']+1)->decrement('sequence');
        self::whereId($id)->increment('sequence');
        return ['message' => 'Пункт понижен!'];
    }
    
    
    
    public static function del($id)
    {
        $row = self::whereId($id)->select('name','parent','sequence')->first();
        if (!$row)
            return ['error' => 'Страница не найдена. Видимо, она была удалена ранее.'];
        
        self::whereParent($row['parent'])->where('sequence','>',$row['sequence'])->decrement('sequence');
        self::whereId($id)->delete();
        
        // удалить связанные рисунки из т. pictures
        //Cpicture::where('content_id', $id)->delete();
        // лучше написать в Cpicture отдельный метод, чтобы удалять и файлы миниатюр
        // если случайно удалится нужное - оно всё равно потом автоматом создастся
        // и, наверное, обращение к Cpicture сделать в контроллере?
        
        return ['message' => 'Страница "' . $row['name'] . '" удалена!'];
    }
    
//    public function products()
//    {
//        return $this->belongsToMany(Product::class, 'product_content');
//    }
    
    private static $productCount;
    
    public static function getProductCount($id)
    { // сколько продуктов прикреплено к категории
        if (!self::$productCount || !self::$productCount[$id])
        {
            self::$productCount[$id] = self::join('product_content', 'contents.id', '=', 'product_content.content_id')
                ->where('content_id',$id)
                ->count();
        }
        return self::$productCount[$id];
    }
    
    
    private static $name = NULL;
    
    public static function getName($id)
    {
        if (!self::$name)
        {
            $menu = self::getMenu();
            foreach ($menu as $m)
                if ($m['id'] == $id)
                    self::$name = $m['name'];
        }
        return self::$name;
    }
    
    
    public static function remakeSitemap()
    {
        $contents = self::select('address')
            ->orderBy('parent', 'asc')
            ->orderBy('sequence', 'asc')
            ->get()->toArray();
        
        $products = Product::join('product_content', 'products.id', '=', 'product_content.product_id')
            ->join('contents', 'product_content.content_id', '=', 'contents.id')
            ->select('products.address as address', 'contents.address as contentaddress')
            ->where('main', 1)
            ->orderBy('products.created_at', 'asc')
            ->get()->toArray();
        
        $sitemap = '';
        foreach ($contents as $c)
            {$sitemap .= 'http://' . $_SERVER['HTTP_HOST'] . '/' . $c['address'] . "\n";}
        foreach ($products as $p)
            {$sitemap .= 'http://' . $_SERVER['HTTP_HOST'] . '/' . $p['contentaddress'] . '/' . $p['address'] . "\n";}
        
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/sitemap.txt' , $sitemap);
    }
    
}