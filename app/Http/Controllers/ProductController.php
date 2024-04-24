<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Product;
use App\Picture;
use App\Content;
use Redirect;

class ProductController extends Controller {
  
//    public function getMenu()
//    {
//        $menu = Content::select('id','name','address','parent','sequence')
//            ->orderBy('parent', 'asc')
//            ->orderBy('sequence', 'asc')
//            ->get();
//    }
    
    
    public function getPage($address, $productaddress)
    {
        $product = Product::where('address', $productaddress)->first();
        if (!$product)
            return abort(404);
        $product['price'] = \App\Constant::convertPrice($product['price']);
        
        $contents = Product::getContents($product['id']);
        // на странице можно показать: этот продукт также входит в такие категории...
        
        if ($contents[0]['address'] != $address)
            return \Redirect::to($contents[0]['address'] . '/' . $productaddress, 301);
        // в целях SEO: чтобы у одного продукта был один адрес, даже если он входит в несколько категорий
        // переадресация с /мётлы/баба-яга-101 на /веники/баба-яга-101
        // т.к. веники - основная категория продукта баба-яга-101
        
        $breadcrumb = Content::getBreadcrumb($contents[0]['id']);
        array_unshift($breadcrumb, ['name'=>$product['name'], 'address'=>$product['address']]);
        
        return view(
            'product',
            [
                'content'       => $contents[0], // главная категория этого продукта
                'product'       => $product,
                'pictures'      => Picture::getForItem($product['id']),
                //'movies'        => Movies::getForItem($product['id']),
                //'swift3ds'      => Swift3ds::getForItem($product['id']),
                'contents'      => $contents, // в какие ещё категории входит
                'parameters'    => Product::getParameters($product['id']),
                'menu'          => Content::getMenu(),
                'breadcrumb'    =>  $breadcrumb
            ]
        );
    }
    
    
    public function getAdminIndex()
    {
        return view('admin.product.index',['menu'=>Content::getMenu()]);
    }
    
    
    public function getAdminContent($content_id)
    {// вывод всех продуктов данной категории для админки
        if (!$content = Content::whereId($content_id)->select('name')->first())
        {return abort(404);}
        return view('admin.product.content',[
            'contentname' => $content['name'],
            'products' => Product::getAdminContent($content_id)
        ]);
    }


    public function getAdd()
    { // форма добавления новой страницы
        return view('admin.product.add',['menu'=>Content::getMenu()]);
    }


    public function postAdd()
    { // приём данных, запись в базу и переадресация на /admin/product/edit/{id}
        $res = Product::add();
        if (isset($res['errors']))
        {return Redirect::back()->withErrors($res['errors'])->withInput();}
        Content::remakeSitemap();
        return redirect('admin/product/edit/' . $res['id'])->with($res['message']);
    }


    public function getEdit($id)
    { // дать форму для редактироания статьи
        $product = Product::whereId($id)->first();
        if (!$product)
        {abort(404);}
        $contents = Product::getContents($id);
        $product['main'] = $contents[0]['id'];
        return view('admin.product.edit',['product'=>$product]);
    }


    public function postEdit($id)
    { // приём данных, запись в базу и перезагрузка
        $res = Product::edit($id);
        // работа над картинками происходит в аяксе, не здесь
        if (isset($res['errors']))
        {return Redirect::back()->withErrors($res['errors'])->withInput();}
        Content::remakeSitemap();
        return redirect('admin/product/edit/' . $id)->with($res['message']);
    }


    public function postDel($id)
    {
        Content::remakeSitemap();
        return redirect('admin/product')->with(Product::del($id));
    }
    
    
    
    
    public function getEditDollar()
    {
        $constant = \App\Constant::select('value')->whereAddress('dollar')->first();
        if (!$constant)
        {abort(404);}
        
        return view('admin.product.dollar',['value'=>$constant['value']]);
    }


    public function postEditDollar()
    {
        $res = \App\Constant::edit();
        if (isset($res['errors']))
        {return Redirect::back()->withErrors($res['errors'])->withInput();}
        return redirect('admin/product/dollar')->with('message', $res['message']);
    }
}