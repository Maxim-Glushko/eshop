// этот код для редактирвания параметров на странице продукта
// для ред парметров на странице параметров - другой код

function adminparameters(url, all, scrtop)
{ //all - обновлять ли весь блок либо лишь вставить селект со значениями
    if (all)
    {
        var height = $('#parameters').height();
        $('#parameters').height(height+'px');
        $('#parameters').empty();
        $('#parameters').load(url, {});
        if (scrtop)
            $('html, body').animate({scrollTop: $('#parameters').offset().top}, 800);
    }
    else
        $('#value').load(url, {});
}

$(document).ajaxComplete(function() {
    $('#parameters').height('auto');
});

$(document).ready(function(){
    adminparameters('/admin/parameter/show/'+item_id, true);
});

$(document).on('change','#parameter select[name=parameter_id]', function(){
    var par = parseInt($(this).val());
    $('#value').empty();
    if (par>0)
        adminparameters('/admin/value/show/'+item_id+'/'+par, false);
});

$(document).on('change','#value select[name=value_id]', function(){
    var val = parseInt($(this).val());
    if (val>0)
        adminparameters('/admin/parameter/join/'+item_id+'/'+ val, true);
});

$(document).on('click','.joindel', function(){
    var id = $(this).parent().find('input[name=id]').val();
    adminparameters('/admin/parameter/deljoin/'+item_id+'/'+id, true);
});