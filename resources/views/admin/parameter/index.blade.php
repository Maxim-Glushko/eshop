@extends('admin.base')
 
@section('title')
Работа с названиями параметров
@stop
 
@section('content')
    <h3>Параметры продуктов</h3>
    
    <ul class="menu">
            <li><a href="/admin/parameter/add" class="btn btn-default submit-button btn-xs" title="добавить параметр">
                <span class="glyphicon glyphicon-plus"></span>
            </a></li>
    @foreach ($parameters as $p)
            <li>
                <a href="/admin/parameter/edit/{{ $p['id'] }}" class="btn btn-default submit-button btn-xs" title="сменить название параметра">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                
                <a href="/admin/value/{{ $p['id'] }}" class="btn btn-default submit-button btn-xs" title="изменить или добавить значения">
                    <span class="glyphicon glyphicon-folder-open"></span>
                </a>
                        
                {!! Form::open(['url' => 'admin/parameter/up/' . $p['id'], 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'style' => 'display:inline-block']) !!}
                    <button type="submit" class="btn btn-default submit-button btn-xs"><span class="glyphicon glyphicon-arrow-up"></span></button>
                {!! Form::close() !!}
                        
                {!! Form::open(['url' => 'admin/parameter/down/' . $p['id'], 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'style' => 'display:inline-block']) !!}
                    <button type="submit" class="btn btn-default submit-button btn-xs"><span class="glyphicon glyphicon-arrow-down"></span></button>
                {!! Form::close() !!}
                
                {!! $p['address'] !!} / {!! $p['name'] !!}
            </li>
    @endforeach
    </ul>
@stop