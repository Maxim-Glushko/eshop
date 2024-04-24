@extends('auth.base')



@section('title')
Смена пароля
@stop



@section('content')
<div class="cols-xs-4"></div>
<div class="cols-xs-4">
<form method="POST" action="/password/reset">
    {!! csrf_field() !!}
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <input class="form-control" placeholder="email" type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        <input class="form-control" placeholder="пароль" type="password" name="password">
    </div>

    <div>
        <input class="form-control" placeholder="повторение пароля" type="password" name="password_confirmation">
    </div>

    <div>
        <button class="btn btn-primary" type="submit">Reset Password</button>
    </div>
</form>
</div>
<div class="cols-xs-4"></div>
@stop