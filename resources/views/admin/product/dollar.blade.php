@extends('admin.base')
 
@section('title')
Редактирование продукта
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Редактирование курса доллара</h3></div>
{!! Form::open(['url' => 'admin/product/dollar/', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    
    <div class="form-group">
        <label for="value" class="col-xs-2 control-label">Курс доллара</label>
        <div class="col-xs-10">
            {!! Form::text('value', isset($value)?$value:null, array('class' => 'form-control')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Редактировать</button>
        </div>
    </div>
{!! Form::close() !!}


<div style="clear:both; height:60px;"></div>
@stop