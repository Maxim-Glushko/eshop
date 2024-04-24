<!DOCTYPE html>
<html>

<head>
    <title></title>
    
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
  
    
    <script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
    
    <link rel="stylesheet" href="/js/jquery-ui-1.11.4/jquery-ui.css" />
    <script type="text/javascript" src="/js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-theme.min.css" />
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="/js/ckeditor/ckeditor.js" charset="utf-8" ></script>
    
    <?php /*<link rel="stylesheet" href="/packages/barryvdh/elfinder/css/theme.css" /> */ ?>
    <link rel="stylesheet" href="/packages/barryvdh/elfinder/css/elfinder.min.css" />
    <script type="text/javascript" src="/packages/barryvdh/elfinder/js/elfinder.min.js" charset="utf-8" ></script>
    
    <?php /*
    <script type="text/javascript" src="/packages/barryvdh/elfinder/js/i18n/elfinder.ru.js" charset="utf-8" ></script>
    
    <script type="text/javascript" src="/colorbox-master/jquery.colorbox-min.js" charset="utf-8" ></script>
    <script type="text/javascript" src="/packages/barryvdh/elfinder/js/standalonepopup.js" charset="utf-8" ></script>
    */ ?>
</head>


<body>


{!! Form::open(['url' => 'куда-нибудь', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal']) !!}
    <div class="form-group">
        <label for="text" class="col-xs-2 control-label">Текст</label>
        <div class="col-xs-10">
            {!! Form::textarea('text', isset($content)?$content->text:null, array('class' => 'form-control', 'id' => 'editor1')) !!}<br />
        </div>
    </div>
    
    <div class="form-group">
        <label for="input-elfinder" class="col-xs-2 control-label">
            <a id="a-elfinder" href="javascript:{}">Изображения</a>
        </label>
        <div class="col-xs-10">
            {!! Form::text('input-elfinder', null, array('class' => 'form-control', 'id'=>'input-elfinder')) !!}
        </div>
    </div>
    
    <div id="container-elfinder"></div>     
{!! Form::close() !!}

    <script type="text/javascript">
        
        var editor = CKEDITOR.replace( 'editor1',{
            filebrowserBrowseUrl : '/elfinder/ckeditor'
        });

        
        $(document).ready(function() {
            function load_elfinder($id) {
                $('#container-elfinder').elfinder({
                    url : '/elfinder/connector',
                    lang : 'ru',
                    dialog : { width : 900, modal : true },
                    editorCallback : function(url) {
                        document.getElementById($id).value = url;
                    }
                });
            }
            $('#a-elfinder').click(function(){
                load_elfinder('input-elfinder');}
            );
        });
        
    </script>


    
</body>
</html>