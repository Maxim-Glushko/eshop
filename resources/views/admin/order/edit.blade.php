@extends('admin.base')
 
@section('title')
Подробный просмотр заказа
@stop
 
@section('content')

<div id='order'>
    @if($order)
    <table class="table table-hover">
        <tr>
            <td>{{$order['created_at']}}</td>
            <td>{{$order['name']}}<br />{{$order['email']}}<br />
            @if($order['address'])
                {{$order['address']}}<br />
            @endif
            @if($order['text'])
                {{$order['text']}}
            @endif
            </td>
            <td>
            <?php $sum=0; ?>
            @foreach ($products as $p)
            <?php $s = round($p['price'] * (100 - $p['discount']) / 100) * $p['amount']; $sum += $s; ?>
            <p>
                <b>{{$p['name']}}</b> {{str_replace('<br />', '; ', $p['parameters'])}}<br />
                {{$p['price']}}грн * {{$p['amount']}} - {{$p['discount']}}% = {{$s}}грн
            </p>
            @endforeach
            </td>
            <td>Сумма: {{$sum}}грн</td>
            <td>
                <span>Заказ обработан</span>
                {!! Form::checkbox('processed', 1, $order['processed']?true:false) !!}
                {!! Form::hidden('order_id',$order['id']) !!}
            </td>
        </tr>
    </table>
    @else
    <h3>Нет такого заказа</h3>
    @endif
</div>
@stop


@push('scripts')

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });
    $(function() {
        $(document).on('change','input[type=checkbox]',function(){
           var checked = $(this).prop('checked');
           var order_id = $(this).parent().find('input[name=order_id]').val();
           $.ajax({
                url: '/admin/order/edit/' + order_id,
                type: 'POST',
                dataType: 'html',
                data: {checked:checked},
                success: function(data){
                    alert(data);
                    console.log(data);
                    return false;
                },
                error: function(){
                    console.log('Ошибка');
                }
            });
        });
    });
</script>

@endpush











