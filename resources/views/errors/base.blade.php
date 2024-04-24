<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>@yield('title')</title>
    <style>
        html, body {
            height: 100%;
        }
        a {
            color: #B0BEC5;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; width: 100%; color: #B0BEC5; display: table; font-weight: 100;">
    <div style="text-align: center; display: table-cell; vertical-align: middle;">
        <div style="text-align: center; display: inline-block;">
            <div style="font-size: 50px; margin-bottom: 40px;">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>