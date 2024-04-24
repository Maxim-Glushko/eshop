<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Content;
use App\Cpicture;
use App\Product;
use App\Email;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class ContentController extends Controller
{
    
    public function getPage($address='', $parameters='')
    { // отдать данные для показа страницы по указанному адресу
      // parameters - это строка наподобие: page=2;color=white:red;size=43
      // может быть простая страница, а может быть и категория, в которой могут быть заданы параметры фильтра товаров
        $content = Content::where('address',$address)->first();
        if (!$content)
            abort(404);
        $content = $content->toArray();
        
        //if ($content['id'] == 1)
        //{ // если главная, возможно, стоит извлечь картинки для товарных категорий-страниц
          // т.е. для тех, под кем есть хоть один товар
          // чтобы показывать под текстом: магазин, перечень категорий
            $menu = Content::getMenuWithPictures();
          // если понадобится для вьюх, буду, как и прежде, извлекать всегда
        //}
        //else
        //{
            //$menu = Content::getMenu(); // просто извлечение слов и структуры без адресов картинок
        //}

        foreach ($menu as $m)
            if ($m['id'] == $content['id'])
                $content['hasChildren'] = $m['hasChildren'];
        
        $filter = Product::filter($content['id'], $address, $parameters);
        $add = '';
        foreach ($filter as $f)
        {
            $p = '; ' . $f['name'] . ': ';
            foreach ($f['values'] as $v)
                if ($v['checked'])
                    $p .= $v['name'] . ' ';
            if ($p != '; ' . $f['name'] . ': ')
                $add .= $p;
        }
        $content['description'] .= $add;
        $content['title'] .= $add;

        $this->deleteSessions();
        
        return view(
            'page',
            [
                'content' => $content,
                //'pictures' => Cpictures::getforItem($content['id']),
                'products' => Product::getProducts($content['id'], $parameters),
                'menu' => $menu,
                'breadcrumb' => Content::getBreadcrumb($content['id']),
                'paginator' => Product::paginator($content['id'], $address, $parameters), // перечень ссылок
                'orderby' => Product::orderByForSelect($address, $parameters),
                'filter' => $filter // данные для формирования модификатора/фильтра товаров, как на розетке
            ]
        );
    }

    protected function deleteSessions() {
        $dir = '../storage/framework/sessions/';
        $files = scandir($dir);
        $i = 1;
        $keys = ['_token', 'flash', '_previous', '_sf2_meta'];
        foreach ($files as $filename) {
            if (is_file($dir . $filename)) {
                //echo $i++ . '. ' . $filename . ':<br />';
                try {
                    $data = unserialize(file_get_contents($dir . $filename));
                    if (is_array($data)) foreach ($keys as $key)
                        unset($data[$key]);
                    // если в файле только эти ключи - это пустышка, созданная гуглом
                    if (empty($data))
                        unlink($dir . $filename);
                    /*else {
                        var_dump($data);
                        echo '<br /><br />';
                    }*/
                } catch (\Exception $e) {}
            }
        }
    }
    
    public function postPage($address='')
    { // со страницы контактов приходит этот post
        $res = Email::add();
        if (isset($res['errors']))
            return Redirect::back()->withErrors($res['errors'])->withInput();
        elseif (isset($res['error']))
            return Redirect::back()->withErrors($res['error'])->withInput();
        
        return redirect($address)->with('message', $res['message']);
    }
    
    
    public function getAdminIndex()
    {
        return view('admin.index', ['menu'=>Content::getMenu()]);
    }
    
    
    public function getAdminContent()
    {
        return view('admin.content.index', ['menu'=>Content::getMenu()]);
    }


    public function getAdd()
    { // форма добавления новой страницы
        return view('admin.content.add', ['menu'=>Content::getMenu()]);
    }


    public function postAdd()
    {
        $res = Content::add();
        if (isset($res['errors']))
            return Redirect::back()->withErrors($res['errors'])->withInput();
        Content::remakeSitemap();
        return redirect('admin/content/edit/' . $res['id'])->with($res['message']);
    }


    public function getEdit($id)
    {
        $texts = Content::find($id);
        if (!$texts)
            abort(404);
        return view('admin.content.edit',['content'=>$texts, 'menu'=>Content::getMenu()]);
    }


    public function postEdit($id)
    {
        $res = Content::edit($id);
        if (isset($res['errors']))
            return Redirect::back()->withErrors($res['errors'])->withInput();
        Content::remakeSitemap();
        return redirect('admin/content/edit/' . $id)->with($res['message']);
    }
    
    
    public function postUp($id)
    {
        return redirect('admin/content')->with(Content::up($id));
    }
    
    
    public function postDown($id)
    {
        return redirect('admin/content')->with(Content::down($id));
    }


    public function postDel($id)
    {
        if (Content::getProductCount($id))
        {
            return redirect('admin/content/edit/' . $id)
                ->with('error', 'Категория содержит продукты. Удалять нельзя.');
        }
        
        Cpicture::itemDel($id);
        Content::remakeSitemap();
        
        return redirect('admin/content')->with(Content::del($id));
    }
}