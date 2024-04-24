@if (Session::has('message') || isset($message))
    <div class="alert alert-success">
        <p><span class="glyphicon glyphicon-thumbs-up"></span> {!! Session::has('message') ? Session::get('message') : $message !!}</p>
    </div>
@endif
            
@if (Session::has('error') || isset($error))
    <div class="alert alert-danger">
        <p><span class="glyphicon glyphicon-warning-sign"></span> {!! Session::has('error') ? Session::get('error') :  $error !!}</p>
    </div>
@endif


<div id="parameter">
    <div class="form-group">
        <div class="col-xs-4">
            {!! Form::select('parameter_id', [''=>'+ новый параметр'] + \App\Parameter::getAll(), null, ['class' => 'form-control']) !!}
        </div>
        <div class="col-xs-4" id="value">
            
        </div>
    </div>
</div>


@if ($parameters)
    <div style="clear:both;height:10px;"></div>
    <h4>Прикреплённые к продукту параметры и их значения:</h4>
    <div class="exists-parameter">
        @foreach ($parameters as $p)
            <p>
                {!! Form::hidden('id', $p['id'], ['class' => 'form-control']) !!}
                <button class="btn btn-danger submit-button btn-xs joindel"><span class="glyphicon glyphicon-remove"></span></button>
                {{ $p['name'] }}
            </p>
        @endforeach
    </div>
@endif