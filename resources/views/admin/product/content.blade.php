@extends('admin.base')
 
@section('title')
Работа с категориями
@stop
 
@section('content')
    <h3>Продукты в категории {!! $contentname !!}:</h3>
    
    @if (!$products)
    <p>Категория пока не содержит продуктов.<br />
    Чтобы создать новый продукт, вернитесь в раздел Продукты. При создании продукта можно будет выбрать
    категории, в которые он должен входить.</p>
    @else
        <ul class="menu">
        @foreach ($products as $p)
            <li<?php if (!$p['availability']) echo ' style="color:grey;" '; ?>>
                <a <?php if (!$p['availability']) echo ' style="color:grey;" '; ?> href="/admin/product/edit/{!! $p['id'] !!}" class="btn btn-default submit-button btn-xs" title="редактировать товар">
                    <span class="glyphicon glyphicon-edit"></span>
                </a>
                {!! $p['name'] !!}
             </li>
        @endforeach
        </ul>
    @endif
@stop