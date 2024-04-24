@extends('admin.base')
 
@section('title')
Работа с категориями
@stop
 
@section('content')
    <h3>Продукты по категориям:</h3>
    <p>Выберите нужную категорию, чтобы затем выбрать нужный продукт для редактирования.<br />
        Либо создайте новый продукт.</p>
    
    <ul class="menu">
            <li><a href="/admin/product/add" class="btn btn-default submit-button btn-xs" title="создать новый продукт">
                <span class="glyphicon glyphicon-plus"></span>
            </a></li>
    @foreach ($menu as $m)
        @if (!$m['parent'])
            <li>
                <a href="/admin/product/{!! $m['id'] !!}" class="btn btn-default submit-button btn-xs" title="открыть категорию товаров">
                    <span class="glyphicon glyphicon-folder-open"></span>
                </a>
                {!! $m['name'] !!}
                        
                @if($m['hasChildren'])
                <ul>
                                
                    @foreach ($menu as $m1)
                        @if ($m1['parent'] == $m['id'])
                            <li>
                                <a href="/admin/product/{!! $m1['id'] !!}" class="btn btn-default submit-button btn-xs" title="открыть категорию товаров">
                                    <span class="glyphicon glyphicon-folder-open"></span>
                                </a>
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