$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    }
});

//$('.edit-img button').elfinder({
//   costomData: {
//     _token:  <?php echo csrf_token(); ?>
//   },
//    url : '<?= URL::action('Barryvdh\Elfinder\ElfinderController@showConnector') ?>'
//});



function adminpictures(url, data, scrtop)
{
    var height = $('#images').height();
    $('#images').height(height+'px');
    $('#images').empty();
    $('#images').load(url, {'data' : $.toJSON(data)});
    if (scrtop)
        $('html, body').animate({scrollTop: $('#images').offset().top}, 800);
}



$(document).ajaxComplete(function() {
    $('#images').height('auto');
});



$(document).ready(function(){
    adminpictures('/admin/' + workingpictureitem + '/show/'+item_id, false);
});



$(document).on('click', '.popup_selector', function (event) {
    event.preventDefault();
    var updateID = $(this).attr('data-inputid'); // Btn id clicked
    var elfinderUrl = '/elfinder/popup/';

    // trigger the reveal modal with elfinder inside
    var triggerUrl = elfinderUrl + updateID;
    $.colorbox({
        href: triggerUrl,
        fastIframe: true,
        iframe: true,
        width: '70%',
        height: '80%'
    });
});

// function to update the file selected by elfinder
function processSelectedFile(filePath, requestingField) {
    filePath = '/' + filePath.replace(/\\/, '/'); // на линуксах это уже не нужно будет
    
    //$('#' + requestingField).parent().find('.popup_selector').removeClass('new');
    //$('#' + requestingField).parent().find('img').attr('src', '/' + filePath);
    //$('#' + requestingField).val(filePath);
    
    var data = {'src' : filePath};
    if ($('#' + requestingField).parent().find('.popup_selector').hasClass('new'))
    {
        var id = item_id
        var type = 'add';
        var data = {'src' : filePath, 'type' : $('#' + requestingField).parent().find('input:radio:checked').val()};
        // type в data это тип преобразования: деформация или обрезание
    }
    else
    {
        var id = $('#' + requestingField).parent().find('.popup_selector').attr('id').substr(11);
        var type = 'src';
        var data = {'src' : filePath};
    }
    adminpictures('/admin/' + workingpictureitem + '/' + type + '/' + id, data, true);
}


$(document).on('click', '.edit-img button', function(){
    var data = {};
        
    if ($(this).hasClass('img-up'))
        var type = 'up';
    else if ($(this).hasClass('img-down'))
        var type = 'down';
    else if ($(this).hasClass('img-text'))
    {
        var type = 'text';
        data = {'text':$(this).parent().find('input[name="imgtxt"]').val()};
    }
    else if ($(this).hasClass('img-face'))
        var type = 'face';
    else if ($(this).hasClass('img-del'))
        var type = 'del';
        
    var id = $(this).parent().find('.popup_selector').attr('id').substr(11);
        
    adminpictures('/admin/' + workingpictureitem + '/' + type + '/' + id, data, true);
});
