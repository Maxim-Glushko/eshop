@extends('auth.base')



@section('title')
Логин
@stop



@section('content')

<?php /* исходный пример
<form method="POST" action="/auth/login">
    {!! csrf_field() !!}

    <div>Email <input type="email" name="email" value="{{ old('email') }}"></div>

    <div>Password <input type="password" name="password" id="password"> </div>

    <div><input type="checkbox" name="remember"> Remember Me</div>

    <div>
        <button type="submit">Login</button>
    </div>
</form>

изменённый пример из laravel 4
*/ ?>

<form class="form-signin" role="form" action="/auth/login" method="post">
    <h2 class="form-signin-heading">Вход</h2>
    {!! csrf_field() !!}
    <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus />
    <input type="password" class="form-control" placeholder="Password" name="password" required />
    <label class="checkbox">
        <input type="checkbox" name="remember" value="remember-me"> Запомнить меня
    </label>
    <button class="btn btn-md btn-primary" type="submit"><span class="glyphicon glyphicon-log-in"></span> Войти</button>
    <a class="btn btn-md btn-warning" href="/password/email"><span class="glyphicon glyphicon-envelope"></span> Забыли пароль?</a><br />
    <?php /*
    <a href="/auth/register">Регистрация</a>
    */ ?>
</form>

@stop