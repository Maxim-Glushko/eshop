<!DOCTYPE html>
<html>

<head>
    <title>Админ панель - @yield('title')</title>
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
  
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css" />
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="/css/admin.css" type="text/css" />
</head>


<body>
    <div class="row" style="padding-top: 20px;">
        <div class="col-xs-2"></div>
        <div class="col-xs-8">
            @yield('content')
        </div>
        <div class="col-xs-2"></div>
        </div>
    </div>

<?php /*
@if (!Auth::check())
    <form class="navbar-form navbar-right" role="form" action="{{ action('UsersController@postLogin') }}" method="post">
        <a href="/users/login" class="btn btn-success">Войти</a>
        <a href="/users/register" class="btn btn-success">Регистрация</a>
    </form>
@else
    <form class="navbar-form navbar-right" role="form" action="/users/logout">
        <button class="btn btn-success">Выйти</button>
    </form>
    <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><strong>{{ Auth::user()->username }}</strong></a></li>
    </ul>
@endif
*/
?>

</body>
</html>