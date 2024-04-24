<div class="form-group">
    <label for="address" class="col-xs-2 control-label">Адрес</label>
    <div class="col-xs-10">
        {!! Form::text('address', isset($parameter)?$parameter->address:null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label for="name" class="col-xs-2 control-label">Название</label>
    <div class="col-xs-10">
        {!! Form::text('name', isset($parameter)?$parameter->name:null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="form-group">
    <label for="for_order" class="col-xs-2 control-label" title="отмечается при заказе">При заказе</label>
    <div class="col-xs-10">
        {!! Form::checkbox('for_order', 1, isset($parameter)?$parameter->for_order:null, ['class' => 'form-control']) !!}
    </div>
</div>