@if ($products)
<table class="table table-hover">
    <?php $sum = 0;?>
    <tr>
        <th style="width:17%;"></th>
        <th>Название</th>
        <th>Кол-во</th>
        <th>Цена</th>
        <th>Сумма</th>
        <th></th>
    </tr>
    @foreach ($products as $p)
    <tr>
        <td style="vertical-align:top;">
            <a href="/{{ $p['contentaddress'] . '/' . $p['address'] }}" target="_blank">
                <img src="{{ $p['picture'] }}" alt="" style="width:100%;"/>
            </a>
        </td>
        <td>
            <h5><a href="/{{ $p['contentaddress'] . '/' . $p['address'] }}" target="_blank">
                    {{ $p['name'] }}
            </a></h5>
            <p>Артикул: {{ $p['vendorcode'] }}</p>
            <p>{!! $p['parameters_names'] !!}</p>
            <p>{{ $p['description'] }}</p>
        </td>
        <td class="buttons">
            {!! Form::hidden("product_id",$p['id'],['class' => 'form-control product_id']) !!}
            {!! Form::hidden("parameter_value",$p['parameter_value'],['class' => 'form-control parameter_value']) !!}
            {!! Form::hidden("type",'change',['class' => 'form-control type']) !!}
            {!! Form::number("amount",$p['amount'],['class' => 'form-control amount']) !!}
            <button type="submit" class="btn btn-default submit-button  btn-sm"><span class="glyphicon glyphicon-edit"></span></button>
        </td>
        <td style="color:red;font-size:18px;">
            @if ($p['discount'])
            <span id="oldprice">{{ $p['price'] }}&nbsp;грн.</span><br />
            @endif
            <span id="price">{{ intval($p['price'] * (100 - $p['discount']) / 100) }}&nbsp;грн.</span>
        </td>
        <td style="color:red;font-size:18px;">
            {{ intval($p['price'] * (100 - $p['discount']) / 100) * $p['amount'] }}&nbsp;грн
        </td>
        <td>
            {!! Form::hidden("product_id",$p['id'],['class' => 'form-control product_id']) !!}
            {!! Form::hidden("parameter_value",$p['parameter_value'],['class' => 'form-control parameter_value']) !!}
            {!! Form::hidden("type",'del',['class' => 'form-control type']) !!}
            <button type="submit" class="btn btn-danger submit-button  btn-sm"><span class="glyphicon glyphicon-remove" style="color:#fff;"></span></button>
        </td>
    </tr>
    <?php $sum += intval($p['price'] * (100 - $p['discount']) / 100) * $p['amount']; ?>
    @endforeach
</table>
<p style="text-align:right;color:red;font-size:18px;font-weight:bold;padding:0 75px 0 0;">Всего к оплате: {{$sum}} грн</p>
@else
<p style="text-align:center;">В вашей корзине пока нет заказов</p>
@endif
<div style="clear:both;height:30px;"></div>
