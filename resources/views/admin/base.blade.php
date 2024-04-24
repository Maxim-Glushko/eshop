<!DOCTYPE html>
<html>

<head>
    <title>Админ панель - @yield('title')</title>
    
    <meta name="_token" content="<?= csrf_token() ?>" />
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
  
    
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
    
    <link rel="stylesheet" href="/js/jquery-ui-1.11.4/jquery-ui.css" />
    <script type="text/javascript" src="/js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    
    <script type="text/javascript" src="/js/jquery.json-2.3.min.js"></script>
    
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css" />
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="/js/ckeditor/ckeditor.js" charset="utf-8" ></script>
    
    <link rel="stylesheet" href="/packages/barryvdh/elfinder/css/elfinder.min.css" />
    <script type="text/javascript" src="/packages/barryvdh/elfinder/js/elfinder.min.js" charset="utf-8" ></script>
    <script type="text/javascript" src="/packages/barryvdh/elfinder/js/i18n/elfinder.ru.js" charset="utf-8" ></script>
    
    <link rel="stylesheet" href="/colorbox/colorbox.css" />
    <script type="text/javascript" src="/colorbox/jquery.colorbox-min.js" charset="utf-8" ></script>

    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>

    <link rel="stylesheet" href="/css/admin.css" type="text/css" />
</head>


<body>
<div class="container-fluid">
    <div class="row">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <span class="navbar-brand">Dteam.com.ua<small> - раздел администрирования</small></span>
                </div>
                <form role="form" action="/auth/logout" class="navbar-form navbar-right">
                    <button class="btn btn-default"><span class="glyphicon glyphicon-log-out"></span> Выйти</button>
                </form>
            </div>
        </nav>

        <div class="col-xs-3">
            <ul class="left-menu nav nav-pills nav-stacked">
                
                <li><a href="/admin/content"><span class="glyphicon glyphicon-file"></span> Категории</a>
                <li><a href="/admin/product"><span class="glyphicon glyphicon-file"></span> Продукты</a>
                <li><a href="/admin/parameter"><span class="glyphicon glyphicon-file"></span> Параметры</a>
                <li><a href="/admin/order"><span class="glyphicon glyphicon-file"></span> Заказы</a>
                <li><a href="/admin/product/dollar"><span class="glyphicon glyphicon-file"></span> Доллар</a>
            </ul>
        </div>
        
        <div class="col-xs-9">
            
            @if ($errors->all())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p><span class="glyphicon glyphicon-warning-sign"></span> {!! $error !!}</p>
                @endforeach
            </div>
            @endif
            
            @if (Session::has('message'))
            <div class="alert alert-success">
                <p><span class="glyphicon glyphicon-thumbs-up"></span> {!! Session::get('message') !!}</p>
            </div>
            @endif
            
            @if (Session::has('error'))
            <div class="alert alert-danger">
                <p><span class="glyphicon glyphicon-warning-sign"></span> {!! Session::get('error') !!}</p>
            </div>
            @endif
            
            @yield('content')
            
        </div>
            
        <div style="clear: both;"></div> 
            
        <div class="navbar navbar-default">
            <span class="navbar-brand">Dteam.com.ua</span>
        </div>
        
    </div>
</div>

<script type="text/javascript">
var editor = CKEDITOR.replace( 'editor1',{
    filebrowserBrowseUrl : '/elfinder/ckeditor'
});
</script>

@stack('scripts')
    
</body>
</html>