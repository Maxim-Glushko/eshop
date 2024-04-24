@extends('admin.base')
 
@section('title')
Редактирование страницы
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Редактирование страницы</h3></div>
{!! Form::open(['url' => 'admin/content/edit/' . $content->id, 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    
    @include('admin/content/form')
    
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Редактировать</button>
        </div>
    </div>
{!! Form::close() !!}


{!! Form::open(['url' => 'admin/content/del/' . $content->id, 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <div class="col-xs-10">&nbsp;</div>
        <div class="col-xs-2 text-right">
            <button type="submit" class="btn btn-danger submit-button" style="margin-top: -90px;"><span class="glyphicon glyphicon-remove"></span> &nbsp; Удалить</button>
        </div>
    </div>
{!! Form::close() !!}

<hr />



<h3>Управление прикреплёнными к этой странице изображениями</h3>
<p>[Здесь имеет смысл лишь первый рисунок и только для категорий, содержащих товары. Остальные - на случай бастрой заготовленной замены.]</p>
<div id="images"></div>

@stop

@push('scripts')

<script type="text/javascript" src="/js/adminpictures.js"></script>
<script type="text/javascript">
    var item_id = {{$content->id}};
    var workingpictureitem = 'cpicture';
</script>

@endpush