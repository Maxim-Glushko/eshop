@extends('admin.base')
 
@section('title')
Добавление новой страницы
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Добавление новой страницы</h3></div>
{!! Form::open(['url' => 'admin/content/add', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    
    @include('admin/content/form')
        
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Добавить</button>
        </div>
    </div>
{!! Form::close() !!}
@stop