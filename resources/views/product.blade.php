@extends('base')


@section('content')

        <div class="product-bg"></div>
        
        <div id="breadcrumb">
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
        
        <div style="clear:both;height:1px;"></div>
        
        <div class="col-sm-7 col-xs-12 col-sm-push-5" style="background:#fff;padding-left:15px;padding-right:15px;">
            <div id="datablock">
                <h1>{{ $product['name'] }}</h1>
    
    @if ($parameters || $product['vendorcode'])
                <table class="table">
        @if ($product['vendorcode'])
                    <tr>
                        <td>Код товара:</td>
                        <td>{{ $product['vendorcode'] }}</td>
                    </tr>
        @endif
    
        @if ($parameters)
            @foreach ($parameters as $p)
                    <tr>
                        <td>{{ $p['name'] }}:</td>
                        <td>
                <?php
                    $i = 0;
                    foreach ($p['values'] as $v) {
                        if ($i) echo ', ';
                        echo ' '.$v['name'];
                        $i++;
                    }
                ?>
                        </td>
                    </tr>
            @endforeach
        @endif
                </table>
    @endif
            </div>
                
                    @if ($product['availability'])
            <div id="priceblock">
                {!! Form::open(['url' => '#', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal', 'id'=>'tocart']) !!}
<?php
        if ($parameters) foreach ($parameters as $p)
        {
            if ($p['for_order'])
            {
                $select = [];
                $select[0] = '-- '.$p['name'].' --';
                foreach ($p['values'] as $v)
                    $select[$v['id']] = $v['name'];
                echo Form::select('parameters['.$p['id'].']', $select, null, ['class' => 'form-control parameters']);
            }
        }
?>
                <div style="clear:both;"></div>
        @if ($product['discount'] > 0)
                <span id="oldprice">{{ $product['price'] }} грн</span>
                <span id="price">{{ intval($product['price'] * (100 - $product['discount']) / 100) }} грн</span>
        @else
                <span id="price">{{ $product['price'] }} грн</span>
        @endif
                <div style="clear:both;"></div>
                <label for="amount" class="control-label">Количество товара:</label>
                {!! Form::number("amount",1,['class' => 'form-control amount']) !!}
                <button type="submit" class="btn btn-primary submit-button">
                    В корзину
                </button>
                {!! Form::close() !!}
            </div>
    @else
            <div id="archival">
                <p>архивный товар</p>
            </div>
    @endif
            <div style="clear:both;height:22px;"></div>
            <div id="product-content">
                {!! $product['text'] !!}

    @if($contents && count($contents)>1)
                <p style="padding-top:50px;">Продукт входит в категории: 
        <?php for ($i=0; $i<count($contents); $i++) {?>
                    <a href="/{{ $contents[$i]['address'] }}">{{ $contents[$i]['name'] }}</a>
            @if ($i<(count($contents)-1))
                    <span>,</span>
            @endif
        <?php } ?>
                </p>
    @endif
            </div>
            
            <div style="clear:both;height:50px;"></div>
        </div>

        <div class="col-sm-5 col-xs-12 col-sm-pull-7">

@if ($pictures)
            <div id="pictures">
    @if(count($pictures)<2)
    
                <img src="{{ $pictures[0]['sizes'][2] }}" alt="{{ $pictures[0]['text'] }}" style="width:100%;"/>
                @if ($pictures[0]['text'])
                <p class="picturedesc">{{ $pictures[0]['text'] }}</p>
                @endif
    
    @else
                <ul>
        @foreach($pictures as $p)
                    <li><img src="{{ $p['sizes'][2] }}" alt="{{ $p['text'] }}" /></li>
        @endforeach
                </ul>
    @endif
            </div>
            <div style="clear:both;height:30px;"></div>
@endif
    
        </div>

@endsection


@push('scripts')

<script type="text/javascript">
    
    var item_id = '{{ $product['id'] }}';
    
    // http://glushko.info
    var mg_p = {
        id_name: 'pictures',
        first_id: 'first_ul',
        second_id: 'second_ul',
        html_content1: '',
        html_content2: '',
        is_loaded_all:false, // все ли картинки уже загрузились
        percent: 85, // процент ширины большой картинки от общей ширины блока
        index: 0, // какая картинка по счёту сейчас активна
        max_height: 0, // высота самой высокой картинки
        imgs: [],
        init: function(){
            mg_p.ext_div = $('#'+mg_p.id_name);
            if (mg_p.ext_div.find('img').length > 1)
            {
                mg_p.width = mg_p.ext_div.width();
                mg_p.index = 0;
                
                if (mg_p.html_content1 == '')
                {
                    mg_p.imgs = [];
                    mg_p.ext_div.find('ul:first img').each(function(i){
                        mg_p.imgs[i] = {};
                        mg_p.imgs[i]['src'] = $(this).attr('src');
                        mg_p.imgs[i]['alt'] = $(this).attr('alt');
                    });
                
                    var html_content1 = '<ul id="'+mg_p.first_id+'">';
                    var html_content2 = '<ul id="'+mg_p.second_id+'">';
                    for (var i in mg_p.imgs)
                    {
                        html_content1 += '<li><img src="'+mg_p.imgs[i]['src']+'" alt="'+mg_p.imgs[i]['alt']+'" />';
                        if (mg_p.imgs[i]['alt'])
                            html_content1 += '<p><span>'+mg_p.imgs[i]['alt']+'</span></p>';
                        html_content1 += '</li>';
                        html_content2 += '<li><img src="'+mg_p.imgs[i]['src']+'" alt="'+mg_p.imgs[i]['alt']+'" /></li>';
                    }
                    html_content1 += '</ul>';
                    html_content2 += '</ul>';
                }
                
                mg_p.ext_div.empty().append(html_content1 + html_content2);
                mg_p.first_ul = mg_p.ext_div.find('#'+mg_p.first_id);
                mg_p.second_ul = mg_p.ext_div.find('#'+mg_p.second_id);
                mg_p.first_ul.width(mg_p.percent+'%');
                mg_p.second_ul.width(100-mg_p.percent+'%');
                
                mg_p.first_ul.find('img').each(function(i){
                    $(this).on('load', function() {
                        mg_p.imgs[i]['width'] = $(this).width();
                        mg_p.imgs[i]['height'] = $(this).height();
                        if ($(this).height() > mg_p.max_height)
                            mg_p.max_height = $(this).height();
                    });
                });
                
                if (!mg_p.is_loaded_all)
                    $(window).bind('load',function(){
                        mg_p.after_load();
                    });
                else
                    mg_p.after_load();
                    
                mg_p.first_ul.find('li:first').addClass('active');
                mg_p.second_ul.find('li:first').addClass('active');
            }
        },
        after_load: function(){
            mg_p.ext_div.height(mg_p.max_height);
            mg_p.first_ul.find('li').each(function(){
                var padding = (mg_p.max_height - $(this).find('img').height()) / 2;
                $(this).css({'padding-top':padding, 'padding-bottom':padding});
                $(this).find('p').css({'bottom':padding});
            });
            mg_p.second_ul.find('li').each(function(i){
                $(this).find('img').on('click',function(){
                    mg_p.click_to_min(i);
                });
            });
            mg_p.is_loaded_all = true;
        },
        click_to_min: function(index){
            var margin1 = mg_p.max_height * index * -1;
            mg_p.first_ul.animate({'margin-top':margin1},1200);
            if (mg_p.max_height < mg_p.second_ul.height())
            {
                var max_margin = mg_p.second_ul.height() - mg_p.max_height;
                //var c = (index+1 > mg_p.second_ul.find('li').length / 2) ? index+1 : index;
                //margin2 = max_margin * c / mg_p.second_ul.find('li').length * -1;
                var c = 0;
                if (index > 0)
                    for (var i=0; i<index; i++)
                        c += mg_p.second_ul.find('li').eq(i).find('img').height();
                margin2 = max_margin * c / (mg_p.second_ul.height() - mg_p.second_ul.find('li:last').find('img').height()) * -1;
                mg_p.second_ul.animate({'margin-top':margin2},1200);
            }
        }
    };
    
    mg_p.init();
    $(window).resize(function(){
        mg_p.init();
    });

</script>
@endpush