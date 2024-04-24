@extends('base')


@section('content')



@if ($content['id'] == 2)
    
    <div class="col-sm-5 col-md-4 col-lg-3 firstcol" id="contacts">
        <div class="breadcrumb-bg"></div>
        
        @if ($errors->all())
            @foreach ($errors->all() as $error)
                 <p><span class="glyphicon glyphicon-warning-sign"></span> {!! $error !!}</p>
             @endforeach
        @endif
            
        @if (Session::has('message'))
            <p><span class="glyphicon glyphicon-thumbs-up"></span> {!! Session::get('message') !!}</p>
        @endif
            
        @if (Session::has('error'))
            <p><span class="glyphicon glyphicon-warning-sign"></span> {!! Session::get('error') !!}</p>
        @endif
                            
        <h3>Наши контакты</h3>
        <br /><p>У Вас ещё остались вопросы? Вы можете связаться с нами любым удобным способом.</p><br />
        <p>
            <span class="glyphicon glyphicon-envelope"></span> {!! HTML::mailto('dteam.com.ua@gmail.com') !!}<br />
            <span class="glyphicon glyphicon-earphone"></span> <a href="tel:+380937002442" class="tel">+38 093 700 24 42</a><br />
            <span class="glyphicon glyphicon-earphone"></span> <a href="tel:+380487002442" class="tel">+38 048 700 24 42</a><br />
            <span class="glyphicon glyphicon-map-marker"></span> Одесса, М.Жукова 3/а<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ТЦ "Успех" 2 эт. офис 13а
        </p>
        
        <h3>Напишите нам</h3>
                            
        {!! Form::open(['url' => $content['address'], 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
            {!! Form::text('name', (isset($content->mail) && isset($content->mail->name)) ? $content->mail->name : null, ['class' => 'form-control', 'placeholder'=>'Имя']) !!}
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder'=>'E-mail']) !!}
            {!! Form::text('tel', null, ['class' => 'form-control','placeholder'=>'Телефон']) !!}
            {!! Form::textarea('text', null, ['class' => 'form-control', 'placeholder'=>'Сообщение']) !!}
            <button type="submit" class="btn btn-primary submit-button">Отправить</button>  
        {!! Form::close() !!}
        <div class="adding"></div>
    </div>
    <div class="col-sm-7 col-md-8 col-lg-9 secondcol">
        <div id="breadcrumb" style="padding-left:0;">
            <p><a href="/">Главная</a> / <span>Контакты</span></p>
        </div>
        @if ($content['text'])
            {!! $content['text'] !!}
            <br /><br />
        @endif
        <iframe src="https://www.google.com/maps/d/u/0/embed?mid=zJnYXFsXbsxk.kJW14GlVQ5r4" width="100%" height="550px"></iframe>
    </div>



@else
    <div class="col-sm-4 col-md-3 col-lg-2 firstcol" id="leftfilter">
        <div class="breadcrumb-bg"></div>
    @if ($filter)
        <?php /*<h4>Фильтры</h4> */?>
        @foreach ($filter as $p)
        <h6>{{ $p['name'] }}</h6>
            @foreach ($p['values'] as $v)
            <p>
                <?php /*{!! Form::checkbox($v['address'], $v['url'], $v['checked']) !!} */ ?>
                <b<?php if ($v['checked']) echo ' class="active"';?>></b>
                <a href="{{ $v['url'] }}">{{ $v['name'] }}</a>
            </p>
            @endforeach
        @endforeach
    @endif
        <div class="adding"></div>
    </div>
    <div class="col-sm-8 col-md-9 col-lg-10 secondcol" id="content">
    @if ($content['parent']) 
        <div id="breadcrumb" style="padding-left:0;">
            <p>
        @for ($i=count($breadcrumb)-1; $i>=0; $i--)
            @if ($i)
                <a href="/{{$breadcrumb[$i]['address']}}">{{$breadcrumb[$i]['name']}}</a> / 
            @else
                <span>{{$breadcrumb[$i]['name']}}</span>
            @endif
        @endfor
            </p>
        </div>
    @else
        <div style="height:15px;"></div>
    @endif
        
        <h1>{{ $content['name'] }}</h1>

    @if ($content['hasChildren'])
        <div class="row" id="childrencategories">
        @foreach ($menu as $m)
            @if ($m['parent'] == $content['id'])
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    @if (isset($m['picture']))
                <a href="/{{ $m['address'] }}">
                    <img src="{{ $m['picture'][1] }}" alt="{{ $m['name'] }}" />
                </a>
                    @endif
                <h4><a href="/{{ $m['address'] }}">{{ $m['name'] }}</a></h4>
                <p>{{ $m['description'] }}</p>
            </div>
            @endif
        @endforeach
        </div>
        <div style="clear:both;height:15px"></div>
    @endif
    
<?php
    if ($filter)
    {
        $filterflag = false;
        foreach ($filter as $p)
            foreach ($p['values'] as $v)
                if ($v['checked'])
                    $filterflag = true;
        if ($filterflag)
        {
            echo '
        <div id="filterinfo"><p>Вы искали:';
            foreach ($filter as $p)
            {
                $i=0;
                $s = $p['name'] . ' = ';
                foreach ($p['values'] as $v)
                {
                    if ($v['checked'])
                    {
                        if ($i++>0) $s .= ' <i>или</i> ';
                        $s .= $v['name'];
                    }
                }
                if ($i) echo $s . '; ';
            }
            echo '</p></div>';
        }
    }
?>

    @if($products)
        
        <?php /*
        @if($paginator)
        <div class="btn-group" aria-label="paginator1" role="group">
            @foreach ($paginator as $p)
                @if ($p['current'])
                <span class="btn btn-default active" type="button">{{ $p['num'] }}</span>
                @else
                <a href="{{ $p['url'] }}" class="btn btn-default" type="button">{{ $p['num'] }}</a>
                @endif
            @endforeach
        </div>
        @endif
        */ ?>
    
<?php
        if ($paginator)
        {
            echo '<div class="paginator">';
            foreach ($paginator as $p)
                echo $p['current'] ?
                    ('<span>' . $p['num'] . '</span>') :
                    ('<a href="' . $p['url'] . '">' . $p['num'] . '</a>');
            echo '</div>';
        }
?>
    
        <div class="orderby">
            {!! Form::label('orderby','Сортировать:') !!}
            {!! Form::select('orderby', $orderby['select'], $orderby['selected']) !!}
        </div>
        <div style="clear:both;height:10px"></div>
        
        <div id="products" class="row">
        @foreach ($products as $p)
            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    @if (isset($p['picture']))
                <a href="/{{ $content['address'] }}/{{ $p['address'] }}">
                    <img src="{{ $p['picture'][1] }}" alt="{{ $p['name'] }}" />
                </a>
                    @endif
                <h4><a href="/{{ $content['address'] }}/{{ $p['address'] }}">{{ $p['name'] }}</a></h4>
                <p>{{ $p['description'] }}</p>
                <div class="price">
                    @if($p['discount'] > 0)
                    <span class="old">{{ $p['price'] }} грн</span>
                    <span>{{ intval($p['price']*(100-$p['discount'])/100) }} грн</span>
                    @else
                    <span>{{ $p['price'] }} грн</span>
                    @endif
                    <a class="button" href="/{{ $content['address'] }}/{{ $p['address'] }}">купить</a>
                </div>
            </div>
        @endforeach
        </div>
        <div style="clear:both;height:10px"></div>
<?php /*
        @if($paginator)
        <div class="btn-group" aria-label="paginator2" role="group">
            @foreach ($paginator as $p)
                @if ($p['current'])
                <span class="btn btn-default active" type="button">{{ $p['num'] }}</span>
                @else
                <a href="{{ $p['url'] }}" class="btn btn-default" type="button">{{ $p['num'] }}</a>
                @endif
            @endforeach
        </div>
        @endif
*/ ?>
  
<?php
        if ($paginator)
        {
            echo '<div class="paginator">';
            foreach ($paginator as $p)
                echo $p['current'] ?
                    ('<span>' . $p['num'] . '</span>') :
                    ('<a href="' . $p['url'] . '">' . $p['num'] . '</a>');
            echo '</div>';
        }
?>

        <div class="orderby">
            {!! Form::label('orderby','Сортировать:') !!}
            {!! Form::select('orderby', $orderby['select'], $orderby['selected']) !!}
        </div>
        <div style="clear:both;height:15px"></div>
    @endif
        
        {!! $content['text'] !!}
    </div>
@endif

@endsection


@push('scripts')

<script type="text/javascript">
    
    var orderBy = {<?php foreach ($orderby['url'] as $k=>$v) echo '"'.$k.'":"'.$v.'",'; ?>};
     
    $(function() {
        $('#leftfilter b').on('click',function(){
            window.location.href = 'http://' + location.host + $(this).next().attr('href');
        });
        
        $('select[name=orderby]').on('change',function(){
            window.location.href = 'http://' + location.host + orderBy[$(this).val()];
        });
        
        function leftforright(){
            $('.adding').height('0');
            if ($(window).width() > 752) {
                var l = $('.firstcol').height();
                var r = $('.secondcol').height();
                if (r > l)
                    $('.adding').height(r-l+'px');
            }
        }
        leftforright();
        $(document).on('load',function(){leftforright();});
        $(window).resize(function(){leftforright();});
    });
</script>

@endpush