<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');


// Registration routes...
// MG: регистрацию я обрубил, т.к. здесь нужен всего один авторизованный пользователь, который и есть админ, даже без проверки
// новые юзеры не нужны, а единственный уже есть в базе данных
//Route::get('auth/register', 'Auth\AuthController@getRegister');
//Route::post('auth/register', 'Auth\AuthController@postRegister');


// Роуты запроса ссылки для сброса пароля
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');


// Роуты сброса пароля
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::any('home', function(){
    return Redirect::to('admin');
});

// немного изменённое отсюда: https://forum.laravel.gen.tr/viewtopic.php?id=1656
Route::group(['middleware' => 'auth', 'prefix'=>'elfinder'], function () {
    Route::get('ckeditor', '\Barryvdh\Elfinder\ElfinderController@showCKeditor4');
    Route::any('connector', '\Barryvdh\Elfinder\ElfinderController@showConnector');
    Route::any('popup/{input_id?}', '\Barryvdh\Elfinder\ElfinderController@showPopup');
});



Route::group(['middleware' => 'auth', 'prefix'=>'admin'], function()
{
    Route::get('/','ContentController@getAdminIndex');
    
    Route::group(['prefix'=>'content'], function()
    {
        Route::get('/',             'ContentController@getAdminContent');
        
        Route::get('add',           'ContentController@getAdd');
        Route::post('add',          'ContentController@postAdd');
        
        Route::get('edit/{id}',     'ContentController@getEdit')    ->where('id', '[0-9]+');
        Route::post('edit/{id}',    'ContentController@postEdit')   ->where('id', '[0-9]+');
        Route::post('del/{id}',     'ContentController@postDel')    ->where('id', '[0-9]+');
        
        // со страницы выбора очерёдности
        Route::post('up/{id}',      'ContentController@postUp')     ->where('id', '[0-9]+');
        Route::post('down/{id}',    'ContentController@postDown')   ->where('id', '[0-9]+');
    });
    
    Route::group(['prefix'=>'product'], function()
    {
        Route::get('/',             'ProductController@getAdminIndex');
        
        Route::get('add',           'ProductController@getAdd');
        Route::post('add',          'ProductController@postAdd');
        
        Route::get('edit/{id}',     'ProductController@getEdit')    ->where('id', '[0-9]+');
        Route::post('edit/{id}',    'ProductController@postEdit')   ->where('id', '[0-9]+');
        Route::post('del/{id}',     'ProductController@postDel')    ->where('id', '[0-9]+');
        
        // содержимое: ссылка для создания нового продукта и ссылки на категории с продуктами
        Route::get('{category_id}','ProductController@getAdminContent')->where('category_id', '[0-9]+');
        // вывод продуктов, прикреплённых к этой категории: имя-ссылка, рядом артикул
        
        // редактирование курса доллара - не стал делать новый контроллер из-за этого
        Route::get('dollar',   'ProductController@getEditDollar');
        Route::post('dollar',  'ProductController@postEditDollar');
    });
    
    Route::group(['prefix'=>'parameter'], function()
    {
        Route::get('/',             'ParameterController@getAdminIndex');
        Route::get('add',           'ParameterController@getAdd');
        Route::post('add',          'ParameterController@postAdd');
        Route::get('edit/{id}',     'ParameterController@getEdit')->where('id','[0-9]+');
        Route::post('edit/{id}',    'ParameterController@postEdit')->where('id','[0-9]+');
        Route::post('up/{id}',      'ParameterController@postUp')->where('id','[0-9]+');
        Route::post('down/{id}',    'ParameterController@postDown')->where('id','[0-9]+');
        Route::post('del/{id}',     'ParameterController@postDel')->where('id','[0-9]+');
        
        Route::post('show/{product_id}','ParameterController@postShow')->where('product_id','[0-9]+');
        // вывести html: перечень уже привязанных пар параметр-значение
        // + select всех параметров для создания новой связки
        Route::post('join/{product_id}/{value_id}','ParameterController@postJoin')
            ->where(['product_id'=>'[0-9]+', 'value_id'=>'[0-9]+']);
        // занесение в product_value связи (с предварительной проверкой таковой)
        Route::post('deljoin/{product_id}/{id}','ParameterController@postDelJoin')
            ->where(['product_id'=>'[0-9]+', 'id'=>'[0-9]+']);
        // удаление связи продукта, параметра и значения
    });
    
    Route::group(['prefix'=>'value'], function()
    {
        Route::get('{parameter_id}', 'ValueController@getAdminIndex')->where('parameter_id','[0-9]+');
        Route::get('add/{parameter_id}','ValueController@getAdd')->where('parameter_id','[0-9]+');
        Route::post('add',          'ValueController@postAdd');
        Route::get('edit/{id}',     'ValueController@getEdit')->where('id','[0-9]+');
        Route::post('edit/{id}',    'ValueController@postEdit')->where('id','[0-9]+');
        Route::post('up/{id}',      'ValueController@postUp')->where('id','[0-9]+');
        Route::post('down/{id}',    'ValueController@postDown')->where('id','[0-9]+');
        Route::post('del/{id}',     'ValueController@postDel')->where('id','[0-9]+');
        
        Route::post('show/{product_id}/{parameter_id}','ValueController@postShow')
                ->where(['product_id'=>'[0-9]+', 'parameter_id'=>'[0-9]+']);
        // вывести html select значений для этого продукта и параметра
        // из расчёта, если уже к этому параметру привязано значение, его уже не выводить
    });
    
    Route::group(['prefix'=>'order'], function()
    {
        Route::get('{page?}',       'OrderController@getAdminPage')->where('page', '[0-9]+');
        // вывести страницу перечня заказов
        Route::get('edit/{id}',     'OrderController@getEdit')->where('id','[0-9]+');
        // вывести всю информацию о заказе
        Route::post('edit/{id}',    'OrderController@postEdit')->where('id','[0-9]+');
        // В редактировании доступно лишь отметить: обработан или нет
    });
    
    Route::post('cpicture/{type}/{id}','CpictureController@postEdit')
        ->where([
            'type' => 'show|add|src|up|down|face|text|del',
            'id' => '[0-9]+'
        ]);
    // прикрепление картинки к странице/категории
    // в этом пректе нужно лишь по одной картинке и то лишь на главной
    
    Route::post('picture/{type}/{id}','PictureController@postEdit')
        ->where([
            'type' => 'show|add|src|up|down|face|text|del',
            'id' => '[0-9]+'
        ]);
    // прикрепление картинки к товару со страницы товара
});

// корзина и заказ - OrderController
Route::group(['prefix'=>'cart'], function()
{
    Route::get('/', 'OrderController@getPageCart');
    Route::post('/','OrderController@postPageCart'); // оформить заказ, нажимается на странице корзины
    
    Route::post('amount','OrderController@postAmount'); // получение количества товара в корзине
    
    Route::post('add/{id}/{amount}','OrderController@postAdd')
        ->where('id','[0-9]+')->where('amount','[0-9]+'); // со страницы товара
    
    Route::post('change','OrderController@postChange'); // страница корзины: изменение количества товара
});
        
//Route::get('{address}/{page}', 'ContentController@getPage')
//        ->where('page', '[0-9]+');
// на главной странице не должно быть никаких товаров
// верне, к ней не должно быть прикреплено ни одного товара, т.к. неясно, какой адрес делать

Route::get('{address}/{productaddress}', 'ProductController@getPage')
        ->where('productaddress', '[-_0-9a-zA-Z]+');

Route::get('{address}/{parameters?}', 'ContentController@getPage')
        ->where(['address'=>'[-_0-9a-zA-Z]+','parameters'=>'[-_=;:0-9a-zA-Z]+']);

Route::get('{address?}', 'ContentController@getPage')
        ->where('address', '[-0-9a-zA-Z]+');
Route::post('{address?}', 'ContentController@postPage'); // для имейла


Route::any('{q1?}/{q2?}/{q3?}/{q4?}/{q5?}',function(){abort(404);});