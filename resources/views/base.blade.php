<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>{{ isset($product) ? $product['title'] : $content['title'] }}</title>
    <meta name="keywords" content="{{ isset($product) ? $product['keywords'] : $content['keywords'] }}" />
    <meta name="description" content="{{ isset($product) ? $product['description'] : $content['description'] }}" />
    
    @if (!isset($product) || !$product)
    <link rel="canonical" href="http://{!! $_SERVER['HTTP_HOST'].'/'.$content['address'] !!}" />
    @endif
    
    <meta name="csrf-token" content="{{ csrf_token() }}" />
        
    <link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
        
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css" />
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="/js/all.js"></script>
        
    <meta name="viewport" content="width=device-width, initial-scale=1" />
        
    <link rel="stylesheet" href="/css/style.css" />
    <link rel="stylesheet" href="/css/font-awesome.min.css" />
    
    <link rel="stylesheet"  href="/css/lightslider.css"/>
    
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-75621622-1', 'auto');
        ga('send', 'pageview');
    </script>
    
</head>

<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid" style="max-width:1230px;">
        <div class="row" style="position:relative;">
            <div id="polosa"></div>
            <div class="col-lg-11 col-md-10 col-sm-10 col-xs-9">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>                        
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav nav-pills">
                            <li<?php if ((1 == $content['id'] || 1 == $content['parent'])) echo ' class="active"'; ?>>
                                <a href="/"><span class="glyphicon glyphicon-home"></span></a>
                            </li>
                    @foreach ($menu as $m)
                        @if (!$m['parent'])
                            @if ($m['hasChildren'])
                            <li class="dropdown-split-left {{ ($m['id'] == $content['id'] || $m['id'] == $content['parent']) ? ' active' : '' }}">
                                <a href="/{{$m['address']}}">{{$m['name']}}</a>
                            </li>
                            <li class="dropdown dropdown-split-right small">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                                <ul class="dropdown-menu pull-right">
                                    @foreach ($menu as $m2)
                                        @if ($m2['parent'] == $m['id'])
                                    <li class="{{ ($m2['id'] == $content['id']) ? ' active' : '' }}">
                                            <a href="/{{$m2['address']}}">{{$m2['name']}}</a>
                                    </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                            @else
                            <li class="{{ ($m['id'] == $content['id'] || $m['id'] == $content['parent']) ? ' active' : '' }}">
                                <a href="/{{$m['address']}}">{{$m['name']}}</a>
                            </li>
                            @endif
                        @endif
                    @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-3">
                <a href="/cart" id="top-cart" class="navbar-link navbar-text navbar-right">
                    <span class="glyphicon glyphicon-shopping-cart basket"></span>
                    <span id="generalAmount" class="badge pull-right count_order"></span>
                </a>
            </div>
        </div>
    </div>
</nav>
    
<div id="header" class="header">
    <div class="bg-header   hidden-xs hidden-sm hidden-md"></div>
    <div class="bg-header-2 hidden-xs hidden-sm hidden-lg"></div>
    <div class="bg-header-3 hidden-xs hidden-md hidden-lg"></div>
    <div class="inheader">
        <a href="/" id="logo"><img src="/servimg/logo.png" alt="" /></a>
        <div>
            {!! HTML::mailto('dteam.com.ua@gmail.com') !!}
            <span>Пн-Пт 10:00-18:00</span>
            <a href="tel:+380937002442" class="tel">+38 093 700 24 42</a>
            <a href="tel:+380487002442" class="tel">+38 048 700 24 42</a>
            <div class="socials">
                <a href="https://vk.com/forma_dteam" title="ВКонтакте" target="_blank">
                    <span class="fa fa-vk"></span>
                </a>
                <a href="https://www.facebook.com/forma.dteam/" title="FaceBook" target="_blank">
                    <span class="fa fa-facebook"></span>
                </a>
                <a href="https://www.instagram.com/forma.dteam/" title="Instagram" target="_blank">
                    <span class="fa fa-instagram"></span>
                </a>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid" style="max-width:1230px;">
    <div class="row" style="position:relative;">
        <div class="breadcrumb-bg"></div>

        @yield('content')
        
    </div>
</div>

<div id="footer">
    <div class="f-first">
        <div>
            <ul>
            @foreach ($menu as $m)
                @if (!$m['parent'])
                <li><a href="/{{ $m['address'] }}">{{ $m['name'] }}</a></li>
                @endif
            @endforeach
            </ul>
            <p>
                <span class="glyphicon glyphicon-earphone"></span>
                <a href="tel:+380937002442">+38 093 700 24 42</a>
                <span class="glyphicon glyphicon-earphone"></span>
                <a href="tel:+380487002442">+38 048 700 24 42</a>
            <p>
            <div style="clear:both"></div>
        </div>
    </div>
    <div class="f-second">
        <div>
            <p>Спортивные товары - Одесса, М.Жукова 3/а, ТЦ "Успех" 2 эт. офис 13а</p>

            <div style="display: block; float: right; width: 88px; height: 31px; margin: 0 0 0 16px;">
                <!-- MyCounter v.2.0 -->
                <script type="text/javascript">
                    my_id = 172049;
                    my_width = 88;
                    my_height = 31;
                    my_alt = "MyCounter - счётчик и статистика";
                </script>
                <script type="text/javascript" src="https://get.mycounter.ua/counter2.0.js"></script>
                <noscript>
                    <a target="_blank" href="https://mycounter.ua/">
                        <img src="https://get.mycounter.ua/counter.php?id=172049"
                             title="MyCounter - счётчик и статистика"
                             alt="MyCounter - счётчик и статистика"
                             width="88" height="31" border="0" />
                    </a>
                </noscript>
                <!--/ MyCounter -->
            </div>

            <a href="http://glushko.info" target="_blank" title="разработка сайта">
                <span class="glyphicon glyphicon-wrench"></span>
            </a>

            <div style="clear:both;"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
</div>

@stack('scripts')


<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter36336440 = new Ya.Metrika({
                    id:36336440,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/36336440" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->


<!--
    разработка сайта: http://glushko.info
    дизайн: http://freelance.ua/user/anelisa/portfolio/
-->
</body>
</html>