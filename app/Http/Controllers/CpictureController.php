<?php

namespace App\Http\Controllers;

use Input;
use App\Http\Controllers\Controller;
use App\Cpicture;

class CpictureController extends Controller
{
    public function postEdit($type, $id)
    {
        if ($type == 'show')
        { // при открытии страницы нужно просто показать без никаких доп действий
            $res = ['none' => ''];
            $item_id = $id;
        }
        elseif ($type == 'add')
        { // в add и show в $id уже лежит item_id
            $data = json_decode(Input::all()['data'], true);
            $res = Cpicture::add($id, $data);
            $item_id = $id;
        }
        else
        { // в остальных $id - это id картинки, а item_id нужно ещё узнавать
            $row = Cpicture::whereId($id)->select('item_id')->first();
            $item_id = $row['item_id'];
            $data = json_decode(Input::all()['data'], true);
            $res = Cpicture::$type($id, $data);
        }
        
        $pictures = Cpicture::getForItem($item_id);
        
        return view('admin.pictures',[
            'pictures' => $pictures,
            key($res) => $res[key($res)]
        ]);
    }
}