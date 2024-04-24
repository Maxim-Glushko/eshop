<div class="form-group">
    <label for="address" class="col-xs-2 control-label">Адрес</label>
    <div class="col-xs-10">
        {!! Form::text('address', isset($product)?$product->address:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="name" class="col-xs-2 control-label">Название</label>
    <div class="col-xs-10">
        {!! Form::text('name', isset($product)?$product->name:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="title" class="col-xs-2 control-label">title</label>
    <div class="col-xs-10">
        {!! Form::text('title', isset($product)?$product->title:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="description" class="col-xs-2 control-label">description</label>
    <div class="col-xs-10">
        {!! Form::text('description', isset($product)?$product->description:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="keywords" class="col-xs-2 control-label">keywords</label>
    <div class="col-xs-10">
        {!! Form::text('keywords', isset($product)?$product->keywords:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="text" class="col-xs-2 control-label">Текст</label>
    <div class="col-xs-10">
        {!! Form::textarea('text', isset($product)?$product->text:null, array('class' => 'form-control', 'id' => 'editor1')) !!}<br />
    </div>
</div>
<div class="form-group">
    <label for="price" class="col-xs-2 control-label">Цена, $</label>
    <div class="col-xs-2">
        {!! Form::text('price', isset($product)?$product->price:null, array('class' => 'form-control')) !!}
    </div>

    <label for="discount" class="col-xs-2 control-label">Скидка, %</label>
    <div class="col-xs-2">
        {!! Form::text('discount', isset($product)?$product->discount:null, array('class' => 'form-control')) !!}
    </div>
    
    <label for="availability" class="col-xs-2 control-label">В наличии</label>
    <div class="col-xs-1">
        {!! Form::checkbox('availability', 1, (isset($product) ? ($product->availability?true:false) : true), array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="parent" class="col-xs-2 control-label" title="главная категория продукта">Категория</label>
    <div class="col-xs-6">
        {!! Form::select('main', [''=>'-- выберите --'] + \App\Content::getCats(), isset($product) ? $product['main'] : false, array('class' => 'form-control')) !!}
    </div>
    
    <label for="vendorcode" class="col-xs-2 control-label">Артикул</label>
    <div class="col-xs-2">
        {!! Form::text('vendorcode', isset($product)?$product->vendorcode:null, array('class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="content_ids" class="col-xs-2 control-label" title="категории, в которые также входит этот продукт">Категории</label>
    <div class="col-sm-10">
        {!!Form::select(
            'content_ids',
            \App\Content::getCats(),
            isset($product)?\App\Content::getMyCats($product->id):[],
            ['class'=>'form-control select2', 'multiple'=>'multiple', 'name'=>'content_ids[]']
        ) !!}
    </div>
</div>