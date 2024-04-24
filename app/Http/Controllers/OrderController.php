<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Content;
use App\Order;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller {
    
    public function getPageCart()
    { // получить данные корзины
      // id продуктов и их количество из сессии
      // остальную инфу из базы данных

        return view('cart', [
            'content' => [
                'id' => 9999,
                'address' => 'cart',
                'parent' => 0,
                'title' => 'Ваши заказы - корзина',
                'keywords' => 'заказ, онлайн-магазин, спортивные товары, корзина',
                'description' => ''
             ],
            'products' => Order::getCart(),
            'customer' => Order::getCustomer(),
            'menu' => Content::getMenu(),
            'breadcrumb' => false
        ]);
    }
    
    
    public function postAmount()
    { // узнать число товаров в корзине
        return Order::getGeneralAmount();
    }
    
    
    public function postAdd($id, $amount)
    { // нажатие на кнопке добавить в корзину на странице товара
        $error = Order::addProduct($id, $amount);
        return ['amount' => Order::getGeneralAmount(), 'error' => $error];
    }
    
    
    protected static function postChange()
    { // на странице корзины нажатие на кнопках, изменяющих количество того или иного товара
      // возвращает html-код для перерисовки корзины без перезагрузки страницы
        Order::changeAmount();
        return view('cartajax',['products' => Order::getCart()]);
    }
    
    
    public function postPageCart()
    { // оформить заказ: если ошибки - вывести ещё раз недозаполненные данные
      // если ОК - вывести сообщение об успехе + отметить в базе даннных + письмо админу
        $customer = Order::getCustomer();
        if (!$customer)
            return redirect('cart',['error' => 'Не введены данные заказчика']);
                
        $products = Order::getCart();
        if (!$products)
            return Redirect::back()->with(['error' => 'Не выбрано ни одного товара для заказа'])->withInput();

        $validation = Validator::make(\Session::get('customer'), [
            'name'      => 'max:255',
            'email'     => ['required_without:phone', 'email', 'max:255'],
            'phone'     => ['required_without:email', 'max:20', 'regex:/^[-\+\s0-9]+$/'],
            'address'   => 'max:255',
            'text'      => 'max:10000'
        ]);
        if ($validation->fails())
            return Redirect::back()->withErrors($validation)->withInput();
        
        $order = Order::create([
            'name'      => $customer['name'],
            'email'     => $customer['email'],
            'phone'     => $customer['phone'],
            'address'   => $customer['address'],
            'text'      => $customer['text'],
            'processed' => 0
        ]);

        $productArray = [];
        foreach ($products as $p)
            $productArray[] = [
                'order_id'      => $order['id'],
                'product_id'    => $p['id'],
                'name'          => $p['name'],
                'amount'        => $p['amount'],
                'price'         => $p['price'],
                'discount'      => $p['discount'],
                'parameters'    => $p['parameters_names']
            ];
        
        \DB::table('order_product')->insert($productArray);
        
        \App\Email::order($customer, $productArray);
        
        \Session::forget('cart');
        
        return view('cart', [
            'message' => 'Спасибо за заказ! Мы свяжемся с Вами для уточнения очень скоро.',
            'content' => [
                'id' => 9999,
                'address' => 'cart',
                'parent' => 0,
                'title' => 'Ваш заказ принят!',
                'keywords' => 'заказ, онлайн-магазин, спортивные товары, корзина',
                'description' => ''
             ],
            'products' => [],
            'customer' =>Order::getCustomer(),
            'menu' => Content::getMenu(),
            'breadcrumb' => false
        ]);
    }
    
    
    public function getAdminPage($page = 1)
    { // показать страницу-перечень заказов
        $orders = Order::select('*')
            ->orderBy('created_at','desc')
            ->skip(Order::$forPage * ($page - 1))
            ->take(Order::$forPage)
            ->get();
        
        if (!$orders || !count($orders))
        {
            $orders = false;
            $products = false;
            $paginator = false;
        }
        else
        {
            $paginator = Order::paginator($page);
            $orders = $orders->toArray();
            $products = \DB::table('order_product')
                ->whereIn('order_id',array_fetch($orders, 'id'))
                ->select('*')
                ->get();
        
            if ($products)
            {
                $products = json_decode(json_encode($products), true);
            
                foreach ($orders as $k=>$v)
                    foreach ($products as $p)
                        if ($p['order_id'] == $v['id'])
                            $orders[$k]['products'][] = $p;
            }
        }
        
        return view('admin.order.index', ['orders'=>$orders, 'paginator'=>$paginator]);
    }
    
    public function getEdit($order_id)
    {
        $order = Order::select('*')
            ->whereId($order_id)
            ->first();
      
        if ($order)
        {
            $order = $order->toArray();
            $products = \DB::table('order_product')
                ->where('order_id', $order_id)
                ->select('*')
                ->get();
            if ($products)
                $products = json_decode(json_encode($products), true);
        }
        else
        {
            $products = false;
        }
        
        return view('admin.order.edit',['order'=>$order, 'products'=>$products]);
    }
    
    public function postEdit($order_id)
    {
        $data = \Input::all();
        $processed = ($data['checked'] == 'true') ? 1 : 0;
        
        $order = Order::find($order_id);
        if (!$order)
            return 'Ошибка. Не найден такой заказ.';
        
        $order->update(['processed'=>$processed]);
        
        return $processed ? 'Заказ отмечен как обработанный.' : 'Заказ отмечен как необработанный.';
    }
}