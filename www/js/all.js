// нужно в сочетании с <meta name="csrf-token" ...> в шапке, чтобы не возникали ошибки
// при общении с сервером через ajax минуя форму
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

function generalAmount()
{
    $.ajax({
        url: '/cart/amount',
        type: 'POST',
        dataType: 'text',
        success: function(data){$('#generalAmount').text(data);},
    });
}
function logo_resize() {
    var window_width = $(window).width();
    console.log(window_width);
    $('#logo').css({
        'top' :     (window_width < 470) ? '55px' : '30px',
        'width':    (window_width < 388) ? ((window_width - $('.inheader>div').width() + 70) + 'px') : '204px'
    });
}
$(window).resize(function(){
    logo_resize();
})

$(document).ready(function(){
    jQuery(function($){

        logo_resize();
                
        // подгрузка числа рядом со значком корзины в шапке
        // запускается сразу после загрузки страницы,
        // также при добавлении в корзину и изменении состава корзины (удалить, изменить количество,
        // обнуление при заказе)
        
        generalAmount();

        // на странице товара нажатие на кнопке заказа
        $('#tocart').on('submit', function(event){
            event.preventDefault();
            var amount = $('#tocart .amount').val();
            var data = $(this).serialize();
            $.ajax({
                url: '/cart/add/' + item_id + '/' + amount,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(data){
                    if (data.error)
                    {
                        console.log(data.error);
                        $('#myModal .modal-title').text('Ошибка...')
                        $('#myModal .modal-body p').text(data.error);
                        $('#myModal .modal-footer').empty();
                        $('#myModal').modal();
                    }
                    else
                    {
                        $('#generalAmount').text(data.amount);
                        console.log('Товар добавлен');
                        $('#myModal .modal-title').text('Товар добавлен в корзину')
                        $('#myModal .modal-body p').text('Вы можете перейти к оформлению заказа либо продолжить знакомиться с другими спортивными товарами.');
                        $('#myModal .modal-footer').html('<button type="button"  class="btn btn-default" data-dismiss="modal">Продолжить</button><a href="/cart" class="btn btn-default">Оформить</button>');
                        $('#myModal').modal();
                    }
                    return false;
                },
                error: function(){
                    console.log('Ошибка добавления товара в корзину');
                    $('#myModal .modal-title').text('Ошибка...')
                    $('#myModal .modal-body p').text('Возникла непредвиденная ошибка. Перезагрузите страницу. Если она повторяется, зайдите позже.');
                    $('#myModal .modal-footer').empty();
                    $('#myModal').modal();
                }
            });
            return false;
        });
    });
});