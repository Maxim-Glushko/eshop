@extends('admin.base')
 
@section('title')
Редактирование названия параметра
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Редактирование названия параметра</h3></div>
{!! Form::open(['url' => 'admin/parameter/edit/' . $parameter->id, 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    
    @include('admin/parameter/form')
    
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Редактировать</button>
        </div>
    </div>
{!! Form::close() !!}


{!! Form::open(['url' => 'admin/parameter/del/' . $parameter->id, 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <div class="col-xs-10">&nbsp;</div>
        <div class="col-xs-2 text-right">
            <button type="submit" class="btn btn-danger submit-button" style="margin-top: -90px;"><span class="glyphicon glyphicon-remove"></span> &nbsp; Удалить</button>
        </div>
    </div>
{!! Form::close() !!}
@stop