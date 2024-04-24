@extends('admin.base')
 
@section('title')
Добавление нового названия параметра
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Добавление нового названия параметра</h3></div>
{!! Form::open(['url' => 'admin/parameter/add', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    
    @include('admin/parameter/form')
        
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Добавить</button>
        </div>
    </div>
{!! Form::close() !!}
@stop