@extends('admin.base')
 
@section('title')
Работа с категориями
@stop
 
@section('content')
    <h3>Категории</h3>
    
    <ul class="menu">
        <li><a href="/admin/content/add" class="btn btn-default submit-button btn-xs" title="создать новую">
            <span class="glyphicon glyphicon-plus"></span>
        </a></li>
    @foreach ($menu as $m)
        @if (!$m['parent'])
            <li>
                <a href="/admin/content/edit/{!! $m['id'] !!}" class="btn btn-default submit-button btn-xs" title="редактировать">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                        
                {!! Form::open(['url' => 'admin/content/up/' . $m['id'], 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'style' => 'display:inline-block']) !!}
                    <button type="submit" class="btn btn-default submit-button btn-xs"><span class="glyphicon glyphicon-arrow-up"></span></button>
                {!! Form::close() !!}
                        
                {!! Form::open(['url' => 'admin/content/down/' . $m['id'], 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'style' => 'display:inline-block']) !!}
                    <button type="submit" class="btn btn-default submit-button btn-xs"><span class="glyphicon glyphicon-arrow-down"></span></button>
                {!! Form::close() !!}
                
                {!! $m['name'] !!}
                        
                @if($m['hasChildren'])
                <ul>
                                
                    @foreach ($menu as $m1)
                        @if ($m1['parent'] == $m['id'])
                            <li>
                                <a href="/admin/content/edit/{!! $m1['id'] !!}" class="btn btn-default submit-button btn-xs" title="редактировать">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </a>
                        
                                {!! Form::open(['url' => 'admin/content/up/' . $m1['id'], 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'style' => 'display:inline-block']) !!}
                                    <button type="submit" class="btn btn-default submit-button btn-xs"><span class="glyphicon glyphicon-arrow-up"></span></button>
                                {!! Form::close() !!}
                        
                                {!! Form::open(['url' => 'admin/content/down/' . $m1['id'], 'method' => 'post', 'role' => 'form', 'class' => 'form-inline', 'style' => 'display:inline-block']) !!}
                                    <button type="submit" class="btn btn-default submit-button btn-xs"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                {!! Form::close() !!}
                                
                                {!! $m1['name'] !!}
                            </li>
                        @endif
                    @endforeach
                </ul>
                @endif
                        
            </li>
        @endif
    @endforeach
    </ul>
@stop