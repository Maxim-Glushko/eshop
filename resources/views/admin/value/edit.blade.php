@extends('admin.base')
 
@section('title')
Редактирование значения параметров
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Редактирование значения параметров</h3></div>
{!! Form::open(['url' => 'admin/value/edit/' . $value->id, 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    
    @include('admin/value/form')
    
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Редактировать</button>
        </div>
    </div>
{!! Form::close() !!}


{!! Form::open(['url' => 'admin/value/del/' . $value->id, 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <div class="col-xs-10">&nbsp;</div>
        <div class="col-xs-2 text-right">
            <button type="submit" class="btn btn-danger submit-button" style="margin-top: -90px;"><span class="glyphicon glyphicon-remove"></span> &nbsp; Удалить</button>
        </div>
    </div>
{!! Form::close() !!}
@stop