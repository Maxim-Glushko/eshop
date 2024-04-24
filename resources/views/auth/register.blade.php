@extends('auth.base')



@section('title')
Регистрация
@stop



@section('content')

<?php /*

исходный пример

<form method="POST" action="/auth/register">
    {!! csrf_field() !!}

    <div class="col-xs-6">Name <input type="text" name="name" value="{{ old('name') }}"></div>

    <div>Email <input type="email" name="email" value="{{ old('email') }}"> </div>

    <div>Password <input type="password" name="password"></div>

    <div class="col-xs-6">Confirm Password <input type="password" name="password_confirmation"></div>

    <div><button type="submit">Register</button></div>
</form>


изменённый пример из laravel 4
*/
?>



<div class="container">

    <h1>Регистрация</h1>
{!! Form::open(array('url' => '/auth/register', 'role' => 'form', 'class' => 'form-horizontal')) !!}
    <div class="form-group">
        {!! Form::label('email', 'E-Mail', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-sm-5">
            {!! Form::email('email', null, array('class' => 'form-control')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('name', 'Логин', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-sm-5">
            {!! Form::text('name', null, array('class' => 'form-control')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('password', 'Пароль', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-sm-5">
            {!! Form::password('password', array('class' => 'form-control')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('password_confirmation', 'Повтор пароля', array('class' => 'col-sm-2 control-label')) !!}
        <div class="col-sm-5">
            {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">&nbsp;</div>
        <div class="col-sm-5">
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </div>
    </div>

{!! Form::close() !!}

</div>

@stop