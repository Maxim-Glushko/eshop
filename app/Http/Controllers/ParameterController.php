<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Parameter;
use Illuminate\Support\Facades\Redirect;

class ParameterController extends Controller {
  
    public function getAdminIndex()
    {
        return view('admin.parameter.index', [
            'parameters'=>Parameter::select('*')
                ->orderBy('sequence','asc')
                ->get()
        ]);
    }
    
    public function getAdd()
    { // форма добавления нового параметра
        return view('admin.parameter.add');
    }

    public function postAdd()
    {
        $res = Parameter::add();
        if (isset($res['errors']))
            return Redirect::back()->withErrors($res['errors'])->withInput();
        return redirect('admin/parameter/')->with($res['message']);
    }

    public function getEdit($id)
    {
        if (!$row = Parameter::find($id))
            abort(404);
        return view('admin.parameter.edit',['parameter'=>$row]);
    }

    public function postEdit($id)
    {
        $res = Parameter::edit($id);
        if (isset($res['errors']))
            return Redirect::back()->withErrors($res['errors'])->withInput();
        return redirect('admin/parameter')->with($res['message']);
    }
    
    public function postUp($id)
    {
        return redirect('admin/parameter')->with(Parameter::up($id));
    }
    
    public function postDown($id)
    {
        return redirect('admin/parameter')->with(Parameter::down($id));
    }

    public function postDel($id)
    {
        return redirect('admin/parameter')->with(Parameter::del($id));
    }
    
    public function postShow($product_id)
    { // у наследника (valueController) это будет совсем другой метод
      // здесь выдаём массив имеющихся связей
        return view('admin.parameter.show',['parameters' => Parameter::getJoinForItem($product_id)]);
    }
    
    public function postJoin($product_id, $value_id)
    {
        // проверить, нет ли уже такой связки и лишь потом создать её
        $row = \DB::table('product_value')
            ->where(['product_id'=>$product_id, 'value_id'=>$value_id])
            ->select('id')->first();
        if (!$row)
        {
            \DB::table('product_value')
                ->insert(['product_id'=>$product_id, 'value_id'=>$value_id]);
        }
        return view('admin.parameter.show',['parameters' => Parameter::getJoinForItem($product_id)]);
    }
    
    public function postDelJoin($product_id, $id)
    {
        \DB::table('product_value')->whereId($id)->delete();
        return view('admin.parameter.show',['parameters' => Parameter::getJoinForItem($product_id)]);
    }
}