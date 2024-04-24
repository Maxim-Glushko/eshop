<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
  
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css" />
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
</head>


<body>
<div class="row" style="padding-top: 20px;">
        <div class="col-xs-3"></div>
        <div class="col-xs-6">
            
            @if ($errors->all())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{!! $error !!}</p>
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
        <div class="col-xs-3"></div>
        </div>
</div>
</body>
</html>