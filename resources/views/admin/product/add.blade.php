@extends('admin.base')
 
@section('title')
Добавление нового продукта
@stop
 
@section('content')
<div class="col-xs-2">&nbsp;</div>
<div class="col-xs-10"><h3>Добавление нового продукта</h3></div>
{!! Form::open(['url' => 'admin/product/add', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    
    @include('admin/product/form')
        
    <div class="form-group">
        <div class="col-xs-2">&nbsp;</div>
        <div class="col-xs-5">
            <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Добавить</button>
        </div>
    </div>
{!! Form::close() !!}
@stop

@push('scripts')
<script type="text/javascript">
    $('.select2').select2();
</script>
@endpush