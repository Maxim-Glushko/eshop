@extends('admin.base')
 
@section('title')
Работа со значениями параметра
@stop
 
@section('content')
    <h3>Значения параметра "{{ $parameter['address'] . ' / ' . $parameter['name'] }}"</h3>
    
    <ul class="menu">
            <li><a href="/admin/value/add/{{ $parameter['id'] }}" class="btn btn-default submit-button btn-xs" title="добавить">
                <span class="glyphicon glyphicon-plus"></span>
            </a></li>
    @foreach ($values as $v)
            <li>
                <a href="/admin/value/edit/{!! $v['id'] !!}" class="btn btn-default submit-button btn-xs" title="редактировать">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                        
                {!! Form::open(['url' => 'admin/value/up/' . $v['id'], 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'style' => 'display:inline-block']) !!}
                    <button type="submit" class="btn btn-default submit-button btn-xs"><span class="glyphicon glyphicon-arrow-up"></span></button>
                {!! Form::close() !!}
                        
                {!! Form::open(['url' => 'admin/value/down/' . $v['id'], 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'style' => 'display:inline-block']) !!}
                    <button type="submit" class="btn btn-default submit-button btn-xs"><span class="glyphicon glyphicon-arrow-down"></span></button>
                {!! Form::close() !!}
                
                {!! $v['address'] . ' / ' . $v['name'] !!}
            </li>
    @endforeach
    </ul>
@stop