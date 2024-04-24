@extends('admin.base')
 
@section('title')
Архив заказов
@stop
 
@section('content')
<div id='orders'>
    @if($orders)
    <table class="table table-hover">
        @foreach($orders as $o)
        <tr<?php if ($o['processed']) echo ' class="processed"'?>>
            <td>{{$o['created_at']}}</td>
            <td>{{$o['name']}}<br />{{$o['email']}}</td>
            <td>
            <?php $sum=0; ?>
            @foreach ($o['products'] as $p)
            <?php $s = round($p['price'] * (100 - $p['discount']) / 100) * $p['amount']; $sum += $s; ?>
            <p>{{$p['name']}} => {{$p['price']}}грн * {{$p['amount']}} - {{$p['discount']}}% = {{$s}}грн</p>
            @endforeach
            </td>
            <td>Сумма: {{$sum}}грн</td>
            <td><a href="/admin/order/edit/{{$o['id']}}"><span class="glyphicon glyphicon-eye-open"></span></a></td>
        </tr>
        @endforeach
    </table>
    @else
    <p>пока нет заказов</p>
    @endif
    
    @if ($paginator)
    <div class="btn-group" aria-label="paginator1" role="group">
        @foreach($paginator as $p)
            @if ($p['current'])
            <span class="btn btn-default active" type="button">{{ $p['num'] }}</span>
            @else
            <a href="{{ $p['url'] }}" class="btn btn-default" type="button">{{ $p['num'] }}</a>
            @endif
        @endforeach
    </div>
    @endif
</div>
@stop











