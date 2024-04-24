@extends('auth.base')



@section('title')
Сброс пароля
@stop



@section('content')
<h2>Сброс пароля</h2>
<form method="POST" action="/password/email">
    {!! csrf_field() !!}

    <div>
        <input class="form-control" placeholder="Email" type="email" name="email" value="{{ old('email') }}"  required autofocus />
    </div><br />

    <div>
        <button type="submit" class="btn btn-primary">Восстановить пароль</button>
        <a href="/auth/login" class="btn btn-md btn-success">Нет, я помню</a>
    </div>
</form>
@stop