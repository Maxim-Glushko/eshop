<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Input;
//use App\Picture;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Redirect;

class Email extends Eloquent {
    
    //public static $adminmail = 'megl@ya.ru';
    //public static $adminmail = '7003443@gmail.com';
    public static $adminmail = 'dteam.com.ua@gmail.com';
    public static $adminname = 'Администратору сайта DTEAM';
    
    public static $servermail = 'admin@pechatnik.od.ua';
    public static $servername = 'DTEAM.COM.UA';
    
    public static $subject = 'Вопрос посетителя сайта DTEAM.COM.UA';
    
    public static $orderSubject = 'ЗАКАЗ с сайта DTEAM.COM.UA';
    
    
    protected $fillable =
    [
        'name',
        'email',
        'tel',
        'text'
    ];
    
    
    
    public static function getValidationRules()
    {
        return [
            'name'          => 'required|min:1|max:255',
            'email' => ['required_without:tel', 'email', 'max:255'],
            'tel'   => ['required_without:email', 'max:20', 'regex:/^[-\+\s0-9]+$/'],
            'text'  => 'required|min:5|max:2000'
        ];
    }
    
    
    
    public static function mime_header_encode($str, $data_charset, $send_charset)
    {
        if($data_charset != $send_charset)
            $str = iconv($data_charset, $send_charset, $str);
        return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
    }
    
    public static function send_mime_mail(
        $name_from/*имя отправителя*/, $email_from/*email отправителя*/, $name_to/*имя получателя*/, $email_to/*email получателя*/,
        $data_charset/*кодировка данных*/, $send_charset/*кодировка письма*/, $subject/*тема письма*/, $body/*текст письма*/,
        $content_type = 'text/plain'/*MG: тип данных, ввёл я*/
    )
    {
        $to = self::mime_header_encode($name_to, $data_charset, $send_charset) . ' <' . $email_to . '>';
        $subject = self::mime_header_encode($subject, $data_charset, $send_charset);
        $from =  self::mime_header_encode($name_from, $data_charset, $send_charset) . ' <' . $email_from . '>';
        if($data_charset != $send_charset)
            $body = iconv($data_charset, $send_charset, $body);
        $headers = 'From: '.$from."\r\n";
        $headers .= 'Content-type: '.$content_type.'; charset='.$send_charset."\r\n";

        return mail($to, $subject, $body, $headers);
    }
    
    
    public static function add(){
        $data = Input::all();
        $data['text'] = strip_tags($data['text']);
        
        $validation = Validator::make($data, self::getValidationRules());
        if ($validation->fails())
            return ['errors' => $validation->messages()];
            //return Redirect::back()->withErrors($validation)->withInput();
            
        $text = '
            <div style="width:100%;padding:20px 0 20px 0;background:#fff;">
                <div style="position:relative;width:90%;margin:0 auto;padding:20px 5%;background:#ddd;border:1px solid #aaa;border-radius:10px/7px;">
                    <p>
                        '.($data['name']?('<b>Имя:</b> '.$data['name'].'<br />'):'').'
                        '.($data['email']?('<b>E-mail:</b> '.$data['email'].'<br />'):'').'
                        '.($data['tel']?('<b>Телефон:</b> '.$data['tel'].'<br />'):'').'
                        <br /><b>Сообщение:</b>
                    </p>
                    <p>'.nl2br($data['text']).'</p>
                </div>
            </div>';

        @$res = self::send_mime_mail(
            self::$servername, self::$servermail, self::$adminname, self::$adminmail,
            'utf8', 'utf8', self::$subject, $text,
            'text/html'
        );
        
        if (!$res)
            return ['error' => 'Что-то пошло не так при отправке сообщения. Пожалуйста, позвоните нам!'];
            //return Redirect::back()->withErrors(array('message' => 'Что-то пошло не так при отправке сообщения. Пожалуйста, позвоните нам!'))->withInput();
        
        return ['message' => 'Ваш вопрос принят! Мы свяжемся с Вами.<br />Спасибо за интерес к нашей компании!'];
        //return redirect($address)->with('message', 'Ваш вопрос принят! Мы свяжемся с Вами.<br />Спасибо за интерес к нашей компании!');
    }
    
    
    public static function order($data, $products)
    {
        $text = '
            <div style="width:100%;padding:20px 0 20px 0;background:#fff;">
                <div style="position:relative;width:90%;margin:0 auto;padding:20px 5%;background:#ddd;border:1px solid #aaa;border-radius:10px/7px;">
                    <p>
                        '.($data['name']?('<b>Имя:</b> '.$data['name'].'<br />'):'').'
                        '.($data['email']?('<b>E-mail:</b> '.$data['email'].'<br />'):'').'
                        '.($data['phone']?('<b>Телефон:</b> '.$data['phone'].'<br />'):'').'
                        '.($data['address']?('<b>Адрес:</b> '.$data['address'].'<br />'):'').'
                    </p><br />
                    '.($data['text']?('<p><b>Сообщение:</b></p><p>'.nl2br($data['text']).'</p><br />'):'').' 
                    <p><b>Заказ:</b></p>';
        $sum = 0;
        foreach ($products as $p)
        {
            $text .= '<p><b>Продукт:</b> ' . $p['name'] . '<br />';
            if ($p['parameters'])
                $text .= $p['parameters'] . '<br />';
            $text .= '<b>Цена:</b> ' . $p['price'] . ' грн<br />';
            
            if ($p['discount'] > 0)
            {
                $price = round($p['price'] * (100 - $p['discount']) / 100);
                $text .= '
                    <b>Скидка:</b> ' . number_format($p['discount'], 2, '.', '') . '%<br />
                    <b>Цена со скидкой:</b> ' . $price . ' грн<br />';
            }
            $text .= '<b>Количество:</b> ' . $p['amount'] . '</p><br />';
            
            $sum += round($p['amount'] * $p['price'] * (100 - $p['discount']) / 100);
        }
        
        $text .= '<p><b>Общая сумма:</b> ' . $sum . ' грн</p>';
        
        $text .= '
                </div>
            </div>';
        
        @$res = self::send_mime_mail(
            self::$servername, self::$servermail, self::$adminname, self::$adminmail,
            'utf8', 'utf8', self::$orderSubject, $text,
            'text/html'
        );
    }
}