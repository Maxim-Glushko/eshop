@extends('base')


@section('content')
<div class="col-sm-5 col-md-4 col-lg-3" id="contacts">
    <div class="breadcrumb-bg"></div>
    @if ($errors->all())
        @foreach ($errors->all() as $error)
            <p><span class="glyphicon glyphicon-warning-sign"></span> {!! $error !!}</p>
        @endforeach
    @endif
            
    @if (Session::has('message'))
        <p><span class="glyphicon glyphicon-thumbs-up"></span> {!! Session::get('message') !!}</p>
    @endif
            
    @if (isset($message))
        <p><span class="glyphicon glyphicon-thumbs-up"></span> {!! $message !!}</p>
    @endif
            
    @if (Session::has('error'))
        <p><span class="glyphicon glyphicon-warning-sign"></span> {!! Session::get('error') !!}</p>
    @endif
    
    @if ($products)
    <div id="customer">
    
        <h3>Контакты для<br />связи с Вами</h3>
        <br /><p>Пожалуйста, укажите Ваши контакты</p><br />
    
        {!! Form::open(['url' => 'cart', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
            {!! Form::text('name', isset($customer)?$customer['name']:null, ['class' => 'form-control', 'placeholder'=>'Имя']) !!}
            {!! Form::text('email', isset($customer)?$customer['email']:null, ['class' => 'form-control', 'placeholder'=>'E-mail']) !!}
            {!! Form::text('phone', isset($customer)?$customer['phone']:null, ['class' => 'form-control', 'placeholder'=>'Телефон']) !!}
            {!! Form::text('address', isset($customer)?$customer['address']:null, ['class' => 'form-control', 'placeholder'=>'Адрес доставки']) !!}
            {!! Form::textarea('text', isset($customer)?$customer['text']:null, ['class' => 'form-control', 'placeholder'=>'Примечания']) !!}<br />
            <button type="submit" class="btn btn-primary submit-button">Оформить заказ</button>
        {!! Form::close() !!}
    </div>
    @endif
    
</div>
<div class="col-sm-7 col-md-8 col-lg-9">
    <div id="breadcrumb" style="padding-left:0;">
        <p><a href="/">Главная</a> / <span>Корзина</span></p>
    </div>
    <div id="cart">

    </div>
</div>
@endsection



@push('scripts')

<script type="text/javascript">
    $(function() {
        // на странице корзины нажатие на кнопках: плюс, минус, изменить количество, удалить из корзины
        // форма не нужна, есть несколько button со своими именами, есть input с product_id
        // и есть поле количества
        //$('#cart button').on('click', function(){
        $(document).on('click', '#cart button', function(){
            var id = $(this).parent().find('.product_id').val();
            var type = $(this).parent().find('.type').val();
            var parameter_value = $(this).parent().find('.parameter_value').val();
        var data = {
                id : id,
                type : type,
                parameter_value: parameter_value
            };
            if (type == 'change')
                data.amount = $(this).parent().find('.amount').val();
            cartoperations(data);
        });
        
        function cartoperations(data){
            $.ajax({
                url: '/cart/change',
                type: 'POST',
                dataType: 'html',
                data: data,
                beforeSend: function(){
                    $('#cart').empty();
                },
                success: function(data){
                    generalAmount();
                    console.log('Корзина обновлена');
                    $('#cart').append(data);
                    return false;
                },
                error: function(){
                    console.log('Ошибка обработки корзины');
                }
            });
        }
        
        cartoperations({type:'show'});
    });
</script>

@endpush