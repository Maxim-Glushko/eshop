@extends('admin.base')
 
@section('title')
Редактирование продукта
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Редактирование продукта</h3></div>
{!! Form::open(['url' => 'admin/product/edit/' . $product->id, 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    
    @include('admin/product/form')
    
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Редактировать</button>
        </div>
    </div>
{!! Form::close() !!}


{!! Form::open(['url' => 'admin/product/del/' . $product->id, 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <div class="col-xs-10">&nbsp;</div>
        <div class="col-xs-2 text-right">
            <button type="submit" class="btn btn-danger submit-button" style="margin-top: -90px;"><span class="glyphicon glyphicon-remove"></span> &nbsp; Удалить</button>
        </div>
    </div>
{!! Form::close() !!}

<div style="clear:both; height:1px;"></div>
<hr />


<h3>Управление прикреплёнными к продукту изображениями</h3>
<div id="images"></div>

<hr />

<h3>Управление параметрами продукта</h3>
<p>[чтобы можно было выбрать параметр или его значение, нужно предварительно создать их в разделе "Параметры"]</p> 
<div id="parameters"></div>

<div style="clear:both; height:60px;"></div>
@stop

@push('scripts')

<script type="text/javascript" src="/js/adminpictures.js"></script>
<script type="text/javascript" src="/js/adminparameters.js"></script>
<script type="text/javascript">
    var item_id = {{$product->id}};
    var workingpictureitem = 'picture';
    $('.select2').select2();
</script>

@endpush