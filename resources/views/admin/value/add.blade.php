@extends('admin.base')
 
@section('title')
Добавление нового значения параметров
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Добавление нового значения для параметра "{{$parameter['address'] . ' / ' . $parameter['name']}}"</h3></div>
{!! Form::open(['url' => 'admin/value/add', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}

    {!! Form::hidden('parameter_id', $parameter['id'], ['class' => 'form-control']) !!}
    
    @include('admin/value/form')
        
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Добавить</button>
        </div>
    </div>
{!! Form::close() !!}
@stop