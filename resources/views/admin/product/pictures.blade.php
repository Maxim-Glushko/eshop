@if (Session::has('message') || isset($message))
            <div class="alert alert-success">
                <p><span class="glyphicon glyphicon-thumbs-up"></span> {!! Session::has('message') ? Session::get('message') : $message !!}</p>
            </div>
@endif
            
@if (Session::has('error') || isset($error))
            <div class="alert alert-danger">
                <p><span class="glyphicon glyphicon-warning-sign"></span> {!! Session::has('error') ? Session::get('error') :  $error !!}</p>
            </div>
@endif


@if ($pictures)
    <ul>
        <li>Загрузка изображения:
            <ul>
                <li>deform - растягивание/сужение высоты и ширины картинки под новые размеры без сохранения пропорций;</li>
                <li>cut - пропорциональное изменение размеров с обрезанием выпирающих низа/верха или сторон.</li>
            </ul>
        </li>
        <li>Редактирование изображения:
            <ul>
                <li>крестик - файл изображения не удаляется с сервера, а открепляется от страницы; потеряется лишь описание рисунка;</li>
                <li>галка - изменить описание изображения;</li>
                <li>стрелки - изменить последовательность рисунков;</li>
                <?php /*<li>сердце - сделать рисунок лицевым, главным.</li>*/ ?>
            </ul>
        </li>
    </ul>

  @foreach ($pictures as $img)

    <div class="edit-img">
        <a id="a-elfinder-{{$img['id']}}" class="popup_selector" data-inputid="input-elfinder-{{$img['id']}}">
            <img id="img-input-elfinder-{{$img['id']}}" src="{{$img['sizes'][0]}}" />
        </a>
        {!! Form::hidden('input-elfinder', null, array('class' => 'form-control', 'id'=>'input-elfinder-' . $img['id'])) !!}
        {!! Form::text('imgtxt', $img['text'], array('class' => 'form-control', 'placeholder' => 'описание рисунка')) !!}
        
        <button class="btn btn-danger submit-button img-del"><span class="glyphicon glyphicon-remove"></span></button>
        <?php /*<button class="btn btn-{{$img['face'] ? 'success' : 'info'}} submit-button img-face"><span class="glyphicon glyphicon-heart"></span></button>*/?>
        <button class="btn btn-primary submit-button img-up"><span class="glyphicon glyphicon-chevron-left"></span></button>
        <button class="btn btn-primary submit-button img-down"><span class="glyphicon glyphicon-chevron-right"></span></button>
        <button class="btn btn-primary submit-button img-text"><span class="glyphicon glyphicon glyphicon-ok-sign"></span></button>
    </div>
  @endforeach
@endif

        
    <div class="edit-img">
        <a id="a-elfinder-0" class="popup_selector new" data-inputid="input-elfinder-0">
            <img id="img-input-elfinder-0" src="" />
            <span class="glyphicon glyphicon-picture"></span>
        </a>
        <div style="position: absolute; bottom:0;">
            <div class="radio-inline"><label>{!! Form::radio('imgtype', 'cut' , array('class' => 'form-control')) !!} cut</label></div>
            <div class="radio-inline"><label>{!! Form::radio('imgtype', 'deform' , array('class' => 'form-control')) !!} deform</label></div>
        </div>
        {!! Form::hidden('input-elfinder', null, array('class' => 'form-control', 'id'=>'input-elfinder-0')) !!}
    </div>
    
    <div style="clear:both;height:1px;"></div>