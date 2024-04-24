<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Validator;


// сменил название, т.к. Image есть в Laravel
// возможно, он мне будет нужен
class Picture extends Eloquent {
    
    protected $table = 'pictures';
    
    protected static $foldermode = 0755; // на сервере 0755 // на локали 0777
    protected static $filemode = 0644; // на сервере 0644 // на локали 0777
    
    public static $defaultPicture = '/upload/default2.jpg';
    
    // размеры картинок
    protected static $sizes = [
        ['width' => 200,    'height' => 200],  // для админки 0
        ['width' => 600,    'height' => 600],
        ['width' => 0,      'height' => 600] // если одна из сторон 0, изменение размеров идёт без деформации и обрезания
    ];
    
    
    
    protected $fillable =
    [
        'src',
        'text',
        'item_id',
        'sequence',
        'type'
    ];
    
    
    
    public static function getValidationRules()
    { // $id нужен, чтобы игнорировать сравнение с адресом своей же статьи
        return [
            'src'           => ['regex:/^[-_\.\/0-9a-zA-Z]+$/', 'max:255'],
            'type'          => 'in:cut,deform',
            'text'          => 'max:64000'
        ];
    }
    
    
    public static function getFileUrls($picture)
    { // кушает массив, который должен содержать адрес рисунка от корня сайта и тип деформации
      // отдаёт этот же массив, обогащённый адресами миниатюр в субмассиве
      // миниатюры и папки не создаёт и не проверяет их наличие на диске
        
        if (is_object($picture))
            $picture = $picture->toArray();
        
        $picture['sizes'] = [];
        
        $src = substr($picture['src'], 8); // удаляю часть адреса из начала "/upload/"
        
        $parts = explode('/', $src);
        $filename = array_pop($parts);
        
        $folder = '/img/' . $picture['type'];
        if (count($parts))
            $folder .= '/' . implode('/', $parts);
        
        for ($i=0; $i<count(self::$sizes); $i++)
        {
            $w = self::$sizes[$i]['width'];
            $h = self::$sizes[$i]['height'];
            $sizefolder = ($w && $h) ? ($w.'x'.$h) : ($w ? ('w'.$w) : ('h'.$h));
            $picture['sizes'][$i] = $folder . '/' . $sizefolder . '/' . $filename;
        }
        return $picture;
    }
    
    
    public static function checkFiles($picture)
    { // проверка существования файлов в массиве $picture['sizes']
      // если папки не существует - создаётся
      // если файла не существует - создаётся
        if (!isset($picture['sizes']))
        {$picture = self::getFileUrls($picture);}
        
        $i = 0;
        foreach ($picture['sizes'] as $p)
        {
            $parts = explode('/', $p);
            $filename = array_pop($parts);
            $folder = $_SERVER['DOCUMENT_ROOT'] . '/' . implode('/', $parts);
            
            if (!file_exists($folder))
            {mkdir($folder, self::$foldermode, true);}
            
            $orig = $_SERVER['DOCUMENT_ROOT'] . $picture['src'];
            $copy = $folder . '/' . $filename;
            
            if (!file_exists($copy) && file_exists($orig))
            {self::resize($orig, $copy, self::$sizes[$i], $picture['type']);}
            
            $i++;
        }
        
        return $picture;
    }
    
    
//    protected static $files = null;
//    // эта функци и эта переменная заменены двумя функциями
//    public static function getFiles($orig, $type, $id)
//    { // первый - адрес рисунка от корня, второй - тип преобразования
//      // отдаёт адреса миниатюр [создаёт из оригинала, если их ещё нет]
//        if (!self::$files)
//            self::$files = [];
//        if (!isset(self::$files[$id]) || !is_array(self::$files[$id]))
//            self:$files[$id] = [];
//        
//        $src = substr($orig, 8); // удаляю часть адреса из начала "/upload/"
//        
//        $parts = explode('/', $src);
//        $filename = array_pop($parts);
//        
//        $folder = '/img/' . $type;
//        if (count($parts))
//            $folder .= '/' . implode('/', $parts);
//        
//        for ($i=0; $i<count(self::$sizes); $i++)
//        {
//            $temp_src = $_SERVER['DOCUMENT_ROOT'].$folder.'/'.self::$sizes[$i]['width'].'x'.self::$sizes[$i]['height'];
//            if (!file_exists($temp_src))
//                mkdir($temp_src, self::$foldermode, true);
//            
//            $temp_src .= '/' . $filename;
//            
//            if (!file_exists($temp_src) && file_exists($_SERVER['DOCUMENT_ROOT'].$orig))
//            {
//                $res = self::resize($_SERVER['DOCUMENT_ROOT'].$orig, $temp_src, self::$sizes[$i], $type);
//                if ($res)
//                    return false;
//            }
//            self::$files[$id][] = str_replace($_SERVER['DOCUMENT_ROOT'],'',$temp_src);
//        }
//        return self::$files[$id];
//    }
    
    
    
    protected static function resize($from, $to, $size, $type)
    { // принимает полные адреса: исходника и копии
      // size - массив двух размеров, type - деформация или обрезание
        $os = getimagesize($from);
                
        if (!in_array($os[2], [1,2,3/*,6*/]))
            return 'неправильный формат рисунка';
        
        if (!$size['height'])// если не задана высота - пропорциональное уменьшение по ширине
        {$size['height'] = intval($size['width'] * $os[1] / $os[0]);}
        elseif(!$size['width'])// если не задана ширина - пропорциональное уменьшение по высоте
        {$size['width'] = intval($size['height'] * $os[0] / $os[1]);}
            
        $de = ImageCreateTrueColor($size['width'], $size['height']);
        
        // если будем деформировать, втискиваем рисунок в новые координаты от начала до конца
        $x = 0; // от какого x начинать отсчёт при вырезании куска из исходника
        $y = 0; // от какого y начинать отсчёт при вырезании куска из исходника
        $w = $os[0]; // какой ширины кусок вырезать из исходника
        $h = $os[1]; // какой высоты кусок вырезать из исходника
        
        // если будем обрезать, вычисляем координаты обрезания
        if ($type == 'cut')
        {
            if ($size['width'] / $size['height'] > $os[0] / $os[1])
            { // если пропорции исходника уже
                $h = intval($os[0] * $size['height'] / $size['width']);
                $y = intval(($os[1] - $h) / 2);
            }
            else
            { // если пропорции исходника шире
                $w = intval($os[1] * $size['width'] / $size['height']);
                $x = intval(($os[0] - $w) / 2);
            }
        }
        
        if ($os[2] == 3) // PNG
        {
            $orig = ImageCreateFromPNG($from);
            imagealphablending($de, false); //Отключаем режим сопряжения цветов
            imagesavealpha($de, true); //Включаем сохранение альфа канала
            imagecopyresampled($de, $orig, 0, 0, $x, $y, $size['width'], $size['height'], $w, $h); //Ресайз
            imagepng($de, $to); //Сохранение
        }
        elseif ($os[2] == 1) // GIF
        {
            $orig = ImageCreateFromGIF($from);
            $transparent_source_index = imagecolortransparent($orig); // Получаем прозрачный цвет
            if($transparent_source_index !== -1) // Проверяем наличие прозрачности
            {
                $transparent_color=imagecolorsforindex($orig, $transparent_source_index);
                //Добавляем цвет в палитру нового изображения, и устанавливаем его как прозрачный
                $transparent_destination_index = imagecolorallocate($de, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagecolortransparent($de, $transparent_destination_index);
                //На всякий случай заливаем фон этим цветом
                imagefill($de, 0, 0, $transparent_destination_index);
            }
            imagecopyresampled($de, $orig, 0, 0, $x, $y, $size['width'], $size['height'], $w, $h);//Ресайз
            imagegif($de, $to); //Сохранение
        }
        elseif ($os[2] == 2) // JPEG
        {
            $orig = ImageCreateFromJPEG($from);
            imagecopyresampled($de, $orig, 0, 0, $x, $y, $size['width'], $size['height'], $w, $h);//Ресайз
            imagejpeg($de, $to, 95);
        }/*
        else // BMP
        {
            $orig = ImageCreateFromBMP($from);
            imagecopyresampled($de, $orig, 0, 0, $x, $y, $size['width'], $size['height'], $w, $h);//Ресайз
            imagebmp($de, $to);
        }*/
        
        chmod($to,self::$filemode); 
        imagedestroy($orig);
        imagedestroy($de);
        
        return false;
    }

    
    public static function getMenuFaces()
    {
        $pictures = [];
        $rows = self::whereSequence(1)
                ->select('id','src','type','item_id')
                ->get();
        if (!$rows)
            return false;
        
        for ($i=0; $i<count($rows); $i++)
        {
            $pictures[$i]['item_id'] = $rows[$i]['item_id'];
            //$pictures[$i]['sizes'] = self::getFiles($rows[$i]['src'], $rows[$i]['type'], $rows[$i]['id']);
            $pictures[$i] = self::checkFiles($rows[$i]);
        }
        return $pictures;
    }
    
    
    public static function getForItems($ids)
    {
        $rows = self::select('id','src','type','item_id')
            ->where('sequence', 1)
            ->whereIn('item_id', $ids)
            ->get();

//        if (!$rows)
//            return false;
//        $rows = $rows->toArray();
//
//        $pictures = [];
//        foreach ($rows as $r)
//            $pictures[] = self::checkFiles($r);
        
        if ($rows)
            $rows = $rows->toArray();

        $pictures = [];
        foreach ($ids as $item_id)
            $pictures[]['item_id'] = $item_id;
        
        foreach ($pictures as $k=>$v)
        {
            foreach ($rows as $r)
                if ($r['item_id'] == $v['item_id'])
                    $pictures[$k] = $r;
            if (!isset($pictures[$k]['src']))
            {
                $pictures[$k]['src'] = self::$defaultPicture;
                $pictures[$k]['type'] = 'deform';
            }
            $pictures[$k] = self::checkFiles($pictures[$k]);
        }
//dd($ids);
        return $pictures;
    }
    
    
    //http://stackoverflow.com/questions/280658/can-i-detect-animated-gifs-using-php-and-gd
    public static function is_ani($filename)
    { // является ли файл анимацией
        $filename = $_SERVER['DOCUMENT_ROOT'].$filename;
        if(!($fh = @fopen($filename, 'rb')))
            return false;
        $count = 0;
        while(!feof($fh) && $count < 2)
        {
            $chunk = fread($fh, 1024 * 100); //read 100kb at a time
            $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00\x2C#s', $chunk, $matches);
        }
        fclose($fh);
        return $count > 1;
    }
    
    
    private static $sequence = NULL;
    
    public static function getSequence($item_id)
    { // получение порядка для вставки новой картинки
        if (!self::$sequence)
            self::$sequence = self::where('item_id',$item_id)->max('sequence') + 1;
        return self::$sequence;
    }
    
    
    
//    private static $face = NULL;
//    
//    public static function getFace($item_id)
//    { // получение лицевой картинки для данного предмета
//        if (!self::$face)
//        {
//            self::$face = self::where('item_id',$item_id)
//                ->where('sequence',1)->count() ? false : true;
//        }
//        return self::$face;
//    }
    
    
    
    public static function getForItem($item_id)
    { // получение всех картинок для данного предмета + всех их размеров
        $pictures = self::select('id','text','src','type','sequence')
            ->where('item_id',$item_id)
            ->orderBy('sequence','asc')
            ->get();
        if (!$pictures || !count($pictures))
            return false;
        $pictures = $pictures->toArray();
        for ($i = 0; $i < count($pictures); $i++)
            //$pictures[$i]['sizes'] = self::getFiles($pictures[$i]['src'], $pictures[$i]['type'], $pictures[$i]['id']);
            $pictures[$i] = self::checkFiles($pictures[$i]);

        return $pictures;
    }
    
    
    
//    public static function getRandPictures()
//    { // остатки старого проекта; здесь вряд ли будет нужно
//        // берёт по одной случайной картинке от каждой страницы
//        // + используется Content
//        $pictures = self::select('id','text','src','type','item_id')
//            ->where('item_id','>',1)
//            ->orderByRaw("RAND()")
//            ->skip(0)->take(6)
//            ->get();
//        if ($pictures)
//        {
//            for ($i = 0; $i < count($pictures); $i++)
//                //$pictures[$i]['sizes'] = self::getFiles($pictures[$i]['src'], $pictures[$i]['type'], $pictures[$i]['id']);
//                $pictures[$i] = self::chekFiles($pictures[$i]);
//            $texts['randimg'] = $pictures;
//        }
//        $menu = Content::getMenu();
//        foreach($menu as $m)
//            for ($i=0; $i<count($pictures); $i++)
//                if ($m['id'] == $pictures[$i]['item_id'])
//                   $pictures[$i]['address'] = $m['address'];
//        return $pictures;
//    }
    
    
    
    public static function add($item_id, $data)
    {
        
        if (!$data['src'])
            return ['error' => 'Не получен адрес картинки'];
        
        if (self::is_ani($data['src']))
            return ['error' => 'Анимацию не получится преобразовать в размерах.'];
        
        $os = getimagesize($_SERVER['DOCUMENT_ROOT'].$data['src']);
        if (!in_array($os[2],array(1,2,3)))
            return ['error' => 'Неправильный формат рисунка: нам нужны jpeg, jpg, gif, png.'];
        
        $validation = Validator::make($data, self::getValidationRules());
        if ($validation->fails())
            return ['error' => 'В адресе и имени файла могут быть ЛИШЬ латиница, цифры, дефис и подчёркивание.'];
        
        self::create([
            'type' => $data['type'],
            'sequence' => self::getSequence($item_id),
            'item_id' => $item_id,
            'src' => $data['src']
        ]);
        
        return ['message' => 'Рисунок прикреплен'];
    }
    
    
    
    public static function src($id, $pre_data)
    {
        if (!$pre_data['src'])
            return ['error' => 'Не получен адрес картинки'];
        $data['src'] = $pre_data['src'];
        
        if (self::is_ani($data['src']))
            return ['error' => 'Анимацию не получится преобразовать в размерах.'];
        
        $os = getimagesize($_SERVER['DOCUMENT_ROOT'].$data['src']);
        if (!in_array($os[2],array(1,2,3)))
            return ['error' => 'Неправильный формат рисунка: нам нужны .jpeg, .jpg, .gif, .png.'];
        
        $validation = Validator::make($data, self::getValidationRules());
        if ($validation->fails())
            return ['error' => 'В адресе и имени файла могут быть ЛИШЬ латиница, цифры, дефис и подчёркивание.'];
        
        self::where('id',$id)->update($data);
        
        return ['message' => 'Картинка заменена успешно'];
    }
    
    
    
    public static function text($id, $pre_data)
    {
        if (!$pre_data['text'])
            return ['error' => 'Не получен текст картинки'];
        $data['text'] = $pre_data['text'];
        
        self::where('id',$id)->update($data);
        
        return ['message' => 'Описание изменено успешно'];
    }
    
    
    
    public static function up($id, $pre_data=null)
    { // повышение порядка img
        $row = self::whereId($id)->select('item_id','sequence')->first();
        if (!$row)
            return ['error' => 'Нет такого изображения.'];
        if ($row['sequence'] < 2)
            return ['error' => 'Это изображение уже первое на данной странице.'];
        self::where('item_id', $row['item_id'])
            ->whereSequence($row['sequence']-1)
            ->increment('sequence');
        self::whereId($id)->decrement('sequence');
        return ['message' => 'Порядок изображения повышен!'];
    }
    
    
    
    public static function down($id, $pre_data=null)
    { // понижение порядка img
        $row = self::whereId($id)->select('item_id','sequence')->first();
        if (!$row)
            return ['error' => 'Нет такого изображения.'];
        $maxSequence = self::where('item_id', $row['item_id'])
            ->max('sequence');
        if ($row['sequence'] >= $maxSequence)
            return ['error' => 'Это изображение уже последнее.'];
        self::where('item_id', $row['item_id'])
            ->whereSequence($row['sequence']+1)
            ->decrement('sequence');
        self::whereId($id)->increment('sequence');
        return ['message' => 'Порядок изображения понижен!'];
    }
    
    
    
    public static function face($id, $pre_data=null)
    { // раньше был boolean(face), но это был бред; сейчас кто первый - тот и лицо
        $row = self::whereId($id)
            ->select('item_id', 'sequence')
            ->first();
        if (!$row)
            return ['error' => 'Изображение не найдено.'];
        
        // старших сестёр смещаем
        self::where('item_id', $row['item_id'])
            ->where('sequence', '<', $row['sequence'])
            ->increment('sequence');
        // делаем самой старшей
        self::whereId($id)
            ->update(['sequence'=>1]);
        
        return ['message' => 'Картинка стала первой.'];
    }
    
    
    
    public static function del($id)
    { // открепление img от страницы
        $row = self::whereId($id)->select('item_id','sequence', 'src', 'type')->first();
        if (!$row)
        {return ['error' => 'Изображение не найдено. Видимо, оно уже было откреплено ранее.'];}
        
        self::where('item_id', $row['item_id'])
            ->where('sequence','>',$row['sequence'])
            ->decrement('sequence');
        self::whereId($id)
            ->delete();
        
        // удалить все миниатюры, чтобы потом, если возникнет одноимённая
        // главная картинка, не получилось путаницы с их копиями
        $r = self::getFileUrls($row);
        foreach ($r['sizes'] as $file)
        {@unlink($_SERVER['DOCUMENT_ROOT'] . $file);}
        
        return ['message' => 'Изображение откреплено!'];
    }
    
    
    public static function itemDel($item_id)
    { // удаление всех картинок, прикреплёных к item
      // т.е. удаление в базе, плюс удаление миниатюр
        
        $rows = self::where('item_id', $item_id)
            ->select('src','type','id')->get();
        
        if ($rows)
        {
            foreach ($rows as $row)
            {
                $r = self::getFileUrls($row);
                foreach ($r['sizes'] as $file)
                {@unlink($_SERVER['DOCUMENT_ROOT'] . $file);}
            }
            //self::where('item_id',$item_id)->delete();
            // по идее оно и само должно удалиться
        }
    }
}