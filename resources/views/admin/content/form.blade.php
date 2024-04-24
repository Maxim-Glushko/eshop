<div class="form-group">
    <label for="address" class="col-xs-2 control-label">Адрес</label>
    <div class="col-xs-10">
        {!! Form::text('address', isset($content)?$content->address:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="name" class="col-xs-2 control-label">Название</label>
    <div class="col-xs-10">
        {!! Form::text('name', isset($content)?$content->name:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="title" class="col-xs-2 control-label">title</label>
    <div class="col-xs-10">
        {!! Form::text('title', isset($content)?$content->title:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="description" class="col-xs-2 control-label">description</label>
    <div class="col-xs-10">
        {!! Form::text('description', isset($content)?$content->description:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="keywords" class="col-xs-2 control-label">keywords</label>
    <div class="col-xs-10">
        {!! Form::text('keywords', isset($content)?$content->keywords:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="text" class="col-xs-2 control-label">Текст</label>
    <div class="col-xs-10">
        {!! Form::textarea('text', isset($content)?$content->text:null, array('class' => 'form-control', 'id' => 'editor1')) !!}<br />
    </div>
</div>

<div class="form-group">
    <label for="parent" class="col-xs-2 control-label">Родитель</label>
    <div class="col-xs-10">
        {!! Form::select('parent', App\Content::getParents(), isset($content)?$content->parent:null, array('class' => 'form-control')) !!}
    </div>
</div>