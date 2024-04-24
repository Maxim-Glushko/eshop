<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>{{{ $content['title'] }}}</title>
    <meta name="keywords" content="{{{ $content['keywords'] }}}" />
    <meta name="description" content="{{{ $content['description'] }}}" />
        
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
        
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
        
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css" />
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
        
    <meta name="viewport" content="width=device-width, initial-scale=1" />
        
    <link rel="stylesheet" href="/css/style.css" />
    
</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>                        
                </button>
                <a class="navbar-brand" href="/">Pechatnik.Od.Ua</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav nav-pills">
                @foreach ($menu as $m)
                    @if (!$m['parent'])
                        @if ($m['hasChildren'])
                            <li class="dropdown-split-left"><a href="/{{{$m['address']}}}">{{{$m['name']}}}</a></li>
                            <li class="dropdown dropdown-split-right">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
                                <ul class="dropdown-menu pull-right">
                            @foreach ($menu as $m2)
                                @if ($m2['parent'] == $m['id'])
                                    <li><a href="/{{{$m2['address']}}}">{{{$m2['name']}}}</a></li>
                                @endif
                            @endforeach
                                </ul>
                            </li>
                        @elseif($m['id'] == $content['id'] || $m['id'] == $content['parent'])
                            <li class="active"><a href="/{{{$m['address']}}}">{{{$m['name']}}}</a></li>
                        @else
                            <li><a href="/{{{$m['address']}}}">{{{$m['name']}}}</a></li>
                        @endif
                    @endif
                @endforeach
                </ul>
            </div>
        </div>
    </nav>


    <div id="header<?php if ($content['id'] == 1) echo '-main'; ?>" class="header">
        <div class="overlay"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-1 col-xs-0"></div>
                <div class="col-lg-2 col-md-4 col-sm-5 col-xs-6">
                    <a id="logo" href="/"></a>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-6">
                <?php if ($content['id'] == 1) { ?>
                    <div class="contacts">
                        <h1>Печать на ткани</h1>
                        <p>
                            <span class="glyphicon glyphicon-phone"></span> <a href="tel:+380939777222">093 977 72 22</a><br />
                            <span class="glyphicon glyphicon-phone-alt"></span> <a href="tel:+380487003443">048 700 34 43</a><br />

<script type="text/javascript">
    document.write('<span class="glyphicon glyphicon-envelope"></span> ');
    eval(unescape('%64%6f%63%75%6d%65%6e%74%2e%77%72%69%74%65%28%27%3c%61%20%68%72%65%66%3d%22%6d%61%69%6c%74%6f%3a%37%30%30%33%34%34%33%40%67%6d%61%69%6c%2e%63%6f%6d%22%3e%37%30%30%33%34%34%33%40%67%6d%61%69%6c%2e%63%6f%6d%3c%2f%61%3e%27%29%3b'));
</script>
 
                        </p>
                        <div class="btn-group btn-group-md">
                            <a href="/contacts" class="btn btn-info"><span class="glyphicon glyphicon-question-sign"></span> спросить</a>
                            <a href="#prices" class="btn btn-info"><span class="glyphicon glyphicon-usd"></span> смотреть цены</a>
                        </div>
                    </div>
                <?php } ?>
                </div>
                <div class="col-lg-1 col-xs-0"></div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-xs-0"></div>
            <div class="col-lg-10 col-xs-12">
                <div class="content">
<?php
    
    if ($content['id']!=6)
    {
        echo $content['text'];
    }
    else
    {
?>
                    <div class="row">
                        <div class="col-md-6">
                            
                            
<?php
        // контакты - форма
        if ($content['id'] == 6)
        {
?>
                            
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
                            
                            <h1>Задайте вопрос</h1>
                            
                            {!! Form::open(['url' => $content['address'], 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
                            
                            <div class="form-group">
                                <label for="name" class="col-xs-2 control-label">Имя:</label>
                                <div class="col-xs-10">
                                    {!! Form::text('name', (isset($content->mail) && isset($content->mail->name)) ? $content->mail->name : null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="col-xs-2 control-label">E-mail:</label>
                                <div class="col-xs-10">
                                    {!! Form::text('email', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="tel" class="col-xs-2 control-label">Телефон:</label>
                                <div class="col-xs-10">
                                    {!! Form::text('tel', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="text" class="col-xs-2 control-label">Сообщение:</label>
                                <div class="col-xs-10">
                                    {!! Form::textarea('text', null, array('class' => 'form-control')) !!}<br />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-xs-2"></div>
                                <div class="col-xs-10">
                                    <button type="submit" class="btn btn-primary submit-button"><span class="glyphicon glyphicon-ok"></span> &nbsp; Отправить</button>
                                </div>
                            </div>
                            
                            {!! Form::close() !!}
                            
<?php
        }
?>
                            
                            
                        </div>
                        <div class="col-md-6">
                            <?php echo $content['text']; ?><br />
                            
                            <iframe src="https://www.google.com/maps/d/u/0/embed?mid=zJnYXFsXbsxk.kJW14GlVQ5r4" width="100%" height="400"></iframe>
                        </div>
                    </div>
                    
<?php
    }
?>
                </div>
            </div>
            <div class="col-lg-1 col-xs-0"></div>
        </div>
    </div>

<?php
    if ($content['id']==1)
    {
        if ($content['pictures'])
        {
?>

    <div id="showcase" class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-xs-0"></div>
            <div class="col-lg-10 col-xs-12 ">
                <div class="row">
<?php
            foreach ($content['pictures'] as $img)
            {
?>
                    <div class="col-md-4 col-sm-6 lot">
                        <img src="{{{$img['sizes'][$content['type']]}}}" alt="{{{$img['text']}}}" />
                        <p>{{{$img['text']}}}</p>
                    </div>
<?php
            }
?>
                </div>
            </div>
            <div class="col-lg-1 col-xs-0"></div>
        </div>
    </div>
<?php
        }
?>

<?php
        if ($content['acc'])
        {
?>
    
    <div id="bg2" class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-xs-0"></div>
            <div class="col-lg-10 col-xs-12 ">
                <?php echo $content['acc'][0]; ?>
            </div>
            <div class="col-lg-1 col-xs-0"></div>
        </div>
    </div>

<?php
        }
        
        if ($content['acc'] && count($content['acc']>1))
        {
?>
    
    <a name="prices"></a>
    <div id="prices" class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-xs-0"></div>
            <div class="col-lg-10 col-xs-12">
                <?php echo $content['acc'][1]; ?>
            </div>
            <div class="col-lg-1 col-xs-0"></div>
        </div>
    </div>

<?php
        }
        
        if ($content['randimg'])
        {
?>

    <div id="works" class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-xs-0"></div>
            <div class="col-lg-10 col-xs-12">
                <h3>Примеры работ</h3>
                <h5>изделия с печатью</h5>
                <div class="row">
<?php
            foreach ($content['randimg'] as $randimg)
            {
?>
                    <a href="/{{{$randimg['address']}}}" class="col-md-4 col-sm-6 lot">
                        <img src="{{{$randimg['sizes'][4]}}}" alt="{{{$randimg['text']}}}" />
                        <div class="details">
                            <div>
                                <div><p>{{{$randimg['text']}}}</p></div>
                            </div>
                        </div>
                    </a>
<?php
            }
?>
                </div>
            </div>
            <div class="col-lg-1 col-xs-0"></div>
        </div>
    </div>

<?php
        }
    }
    
    if ($content['id'] > 1 && $content['pictures'])
    {
?>
    <div id="works" class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-xs-0"></div>
            <div class="col-lg-10 col-xs-12">
                <div class="row">
                    <h3>Образцы:</h3>
<?php
            foreach ($content['pictures'] as $img)
            {
?>
                    <div class="col-md-4 col-sm-6 lot">
                        <img src="{{{$img['sizes'][$content['type']]}}}" alt="{{{$img['text']}}}" />
                        <div class="details">
                            <div>
                                <div><p>{{{$img['text']}}}</p></div>
                            </div>
                        </div>
                    </div>
<?php
            }
?>
                </div>
            </div>
            <div class="col-lg-1 col-xs-0"></div>
        </div>
    </div>
<?php
    }
?>

    <div id="footer" class="container-fluid">
        <div class="row">
            <div class="col-lg-1 col-xs-0"></div>
            <div class="col-lg-10 col-xs-12 ">
                <p style="width:310px;">
                    Печать на ткани - Одесса<br />
                    М.Жукова 3/а, ТЦ "Успех" 2 эт. офис 19
                </p>
                <p style="width:130px;">
                    <span class="glyphicon glyphicon-phone"></span> <a href="tel:+380939777222">093 977 72 22</a><br />
                    <span class="glyphicon glyphicon-phone-alt"></span> <a href="tel:+380487003443">048 700 34 43</a>
                </p>
                
                <a href="http://glushko.info" class="btn btn-default btn-xs" target="_blank" style="float: right; margin:35px 0 0 0;">
                    <span class="glyphicon glyphicon-info-sign"></span> создание сайта
                </a>
            </div>
            <div class="col-lg-1 col-xs-0"></div>
        </div>
    </div>
    
    
    @stack('scripts')

    
    <script type="text/javascript">
        $(document).ready(function(){
            jQuery(function($){
                function overlayColor()
                {
                    //$('#header-main .overlay').stop().animate({opacity:(($(window).scrollTop() > 200) ? '0.45' : 0)},500);
                    var height = parseInt($('.header .overlay').css('height')) + 50;
                    $('.header .overlay').stop().animate({opacity:($(window).scrollTop() / height )},10);
                }
                function showcaseHeight()
                {
                    $('#showcase .lot').css({'height':'auto'});
                    var maxHeight = 0;
                    $('#showcase .lot').each(function(){
                        var height = $(this).height();
                        if (maxHeight < height)
                            maxHeight = height;
                    });
                    $('#showcase .lot').css({'height':maxHeight+15+'px'});
                }
                function start_details()
                {
                  $('.details').parent().each(function(){
                    var height = '0px';
                    $(this).find('.details>div').height('0px');
                    
                    $(this).hover(function(){
                        var height2 = $(this).find('.details>div>div').height() + 10 + 'px';
                        $(this).find('.details>div').animate({'height':height2},200);
                    },
                    function(){
                        var height1 = '0px';
                        var height2 = $(this).find('.details>div>div').height() + 10 + 'px';
                        
                        $('.details>div').stop()
                        $('.details').each(function(){
                            $(this).children('div').css({'height':'0px'});
                        })
                        
                        $(this).find('.details>div').css({'height':height2});
                        $(this).find('.details>div').animate({'height':'0px'},200);
                    });
                  })
                }
                
                start_details();
                showcaseHeight();
                
                $(window).scroll(function(){
                    overlayColor();
                });
                
                $(window).resize(function(){
                    overlayColor();
                    showcaseHeight();
                    start_details();
                });
                
                $('a[href^="#"]').click(function()
                {
                    var target = $(this).attr('href');
                    var target_top = parseInt($(target).offset().top) - 50;
                    $('html, body').animate({scrollTop: target_top+'px'}, 800);
                    return false;
                });
                
            });
        });
    </script>
    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-68648896-1', 'auto');
  ga('send', 'pageview');
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter33028434 = new Ya.Metrika({
                    id:33028434,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
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
<noscript><div><img src="https://mc.yandex.ru/watch/33028434" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    
</body>
</html>