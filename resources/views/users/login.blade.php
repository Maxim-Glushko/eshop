@extends('users.login')
 
@section('title')
����
@stop

@section('headExtra')
    {!! HTML::style('/css/signin.css') !!}
@stop
 
@section('content')
<div class="container">
    @if (Session::has('alert'))
        <div class="alert alert-danger">
            <p>{!! Session::get('alert') !!}
        </div>
    @endif
 
    <form class="form-signin" role="form" action="{!! action('UsersController@postLogin') !!}" method="post">
        <h2 class="form-signin-heading">���� ������</h2>
        <input type="text" class="form-control" placeholder="Email ��� ���" name="username" required autofocus />
        <input type="password" class="form-control" placeholder="Password" name="password" required />
        <label class="checkbox">
            <input type="checkbox" name="remember" value="remember-me"> ��������� ����
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">�����</button>
 
        <a href="/password/remind">������ ������?</a><br />
        <a href="/users/register">�����������</a>
    </form>
</div>
@stop