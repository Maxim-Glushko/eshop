<div class="form-group">
    <label for="address" class="col-xs-2 control-label">Адрес</label>
    <div class="col-xs-10">
        {!! Form::text('address', isset($value)?$value->address:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="name" class="col-xs-2 control-label">Название</label>
    <div class="col-xs-10">
        {!! Form::text('name', isset($value)?$value->name:null, array('class' => 'form-control')) !!}
    </div>
</div>