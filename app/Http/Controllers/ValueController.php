<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Value;
use Illuminate\Support\Facades\Redirect;

class ValueController extends Controller {
  
    public function getAdminIndex($parameter_id)
    {
        return view('admin.value.index', [
            'parameter' => \App\Parameter::find($parameter_id),
            'values' => Value::getForParameter($parameter_id)
        ]);
    }
    
    public function postForParameter($parameter_id)
    {
        return view('admin.value.forparameter', [
            'parameter_id' => $parameter_id,
            'values' => Value::getForParameter($parameter_id)
        ]);
    }
    
    public function getAdd($parameter_id)
    { // форма добавления нового параметра
        return view('admin.value.add', ['parameter' => \App\Parameter::where('id', $parameter_id)->first()]);
    }

    public function postAdd()
    {
        $res = Value::add();
        if (isset($res['errors']))
            return Redirect::back()->withErrors($res['errors'])->withInput();
        return redirect('admin/value/'.$res['parameter_id'])->with($res['message']);
    }

    public function getEdit($id)
    {
        if (!$row = Value::find($id))
            abort(404);
        return view('admin.value.edit',['value'=>$row]);
    }

    public function postEdit($id)
    {
        $res = Value::edit($id);
        if (isset($res['errors']))
            return Redirect::back()->withErrors($res['errors'])->withInput();
        return redirect('admin/value/'.$res['parameter_id'])->with($res['message']);
    }
    
    public function postUp($id)
    {
        $res = Value::up($id);
        if (isset($res['error']))
            return Redirect::back()->withErrors($res['error'])->withInput();
        
        return redirect('admin/value/'.$res['parameter_id'])->with($res['message']);
    }
    
    public function postDown($id)
    {
        $res = Value::down($id);
        if (isset($res['error']))
            return Redirect::back()->withErrors($res['error'])->withInput();
        
        return redirect('admin/value/'.$res['parameter_id'])->with($res['message']);
    }

    public function postDel($id)
    {
        $res = Value::del($id);
        if (isset($res['error']))
            return Redirect::back()->withErrors($res['error'])->withInput();
        
        return redirect('admin/value/'.$res['parameter_id'])->with($res['message']);
    }
    
    public function postShow($product_id, $parameter_id)
    {
        return view('admin.value.show',['values' => Value::getAllFor($product_id, $parameter_id)]);
    }
}