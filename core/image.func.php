<? 
//Функция обработки изобраизображения
//$source - путь к файлу источника
//$target - имя файла назначения
//$folder - папка назначения
//
//$newsize - двухмерный массив новых размеров вида array('ширина', 'высота'), 
//если задан один из размеров, то изображение масштабируется в соответствии с ним, а второй размер будет вычислен в соответсвии с пропорциями исходного изображения
//
//$insert - массив параметров вписывания источника в изображение-шаблон
//$insert[0] - путь к файлу изображения в которое будет вписан источник
//$insert[1] - массив координат смещения исходного изображения в шаблоне

function img($source, $target, $folder, $newsize){
	move_uploaded_file($source,$folder."original/".$target);
	imageCropToRect ($folder."original/".$target, $folder."small/".$target, $newsize[0]);
	imageCropToRect ($folder."original/".$target, $folder.$target, $newsize[1]);
	//imageCover("../../userfiles/config/base/ugolki.png", $imgFolder.$_FILES['new'.$val]['name'], $imgFolder.$_FILES['new'.$val]['name']);
}


//Функция изменения размера картинок вписыванием в шаблон
//$sourcefile - исходная картинка
//$namefile - измененная картинка
//$blank - шаблон
function imageResizeToRect ($sourcefile, $namefile, $newsize)
{
	$picsize=getimagesize($sourcefile);
    $source_x  = $picsize[0];
    $source_y  = $picsize[1];
	if($picsize[2]==1){
		$source_id = ImageCreateFromGif($sourcefile);
	}
	else if($picsize[2]==2) {
		$source_id = ImageCreateFromJpeg($sourcefile);
	}
	else if($picsize[2]==3){
		$source_id = imagecreatefrompng($sourcefile);
	}

	if(($source_x/$source_y)>($newsize[0]/$newsize[1])){
		$prop=$source_x/$newsize[0];
	}
	else{
		$prop=$source_y/$newsize[1];
	}
    $dest_x=round($source_x/$prop);
    $dest_y=round($source_y/$prop);

	if(($newsize[0]-$dest_x)<=2) $dest_x=$newsize[0];
	if(($newsize[1]-$dest_y)<=2) $dest_y=$newsize[1];

    $target_id=imagecreatetruecolor($newsize[0],$newsize[1]);
    $white = imagecolorallocate($target_id, 255, 255, 255);
    imagefill($target_id, 0, 0, $white);

	//if($picsize[2]==3) setTransparency($target_id, $source_id);
	
	$poz_x=round(($newsize[0]-$dest_x)/2);
	$poz_y=round(($newsize[1]-$dest_y)/2);

    //echo 'prop:'.$prop.', pos_x:'.$poz_x.', pos_y:'.$poz_y.', dest_x:'.$dest_x.', dest_y:'.$dest_y;

    $target_pic=imagecopyresampled($target_id,$source_id, $poz_x,$poz_y, 0,0, $dest_x,$dest_y,$source_x,$source_y);
	if($picsize[2]==1){
		imagegif($target_id,$namefile);
	}
	else if($picsize[2]==2) {
		imagejpeg($target_id,$namefile,"90");
	} 
	elseif($picsize[2]==3) {
		imagepng($target_id,$namefile);
	}
    imagedestroy($target_id);
    imagedestroy($source_id);
	return true;
}

//Функция изменения размера картинок c обрезанием по размеру
//$sourcefile - исходная картинка
//$namefile - измененная картинка
//$newsize - новые размеры
function imageCropToRect ($sourcefile, $namefile, $newsize)
{
	$picsize=getimagesize($sourcefile);
    $source_x  = $picsize[0];
    $source_y  = $picsize[1];
	
	if($picsize[2]==1){
		$source_id = imagecreatefromgif($sourcefile);
	}
	else if($picsize[2]==2) {
		$source_id = imagecreatefromjpeg($sourcefile);
	}
	else if($picsize[2]==3){
		$source_id = imagecreatefrompng($sourcefile);
	}

	if(($source_x/$source_y)>($newsize[0]/$newsize[1])){
		$prop=$source_y/$newsize[1];
	}
	else{
		$prop=$source_x/$newsize[0];
	}
	$dest_x=round($source_x/$prop);
	$dest_y=round($source_y/$prop);
	//echo "w:$dest_x=$source_x/$prop<br>";
	//echo "h:$dest_y=$source_y/$prop";

	$target_id=imagecreatetruecolor($newsize[0],$newsize[1]);
	
	$poz_x=round(($newsize[0]-$dest_x)/2);
	$poz_y=round(($newsize[1]-$dest_y)/2);
	
	/*In other words, imagecopyresampled() will take an rectangular area from $source_id of width $source_x and height $source_y at position (0,0) 
	and place it in a rectangular area of $target_id of width $dest_x and height $dest_y at position ($poz_x,$poz_y). */
    $target_pic=imagecopyresampled($target_id,$source_id, $poz_x,$poz_y, 0,0, $dest_x,$dest_y,$source_x,$source_y);
	
	if($picsize[2]==1){
		imagegif($target_id,$namefile);
	}
	elseif($picsize[2]==2) {
		imagejpeg($target_id,$namefile,"90");
	}
	elseif($picsize[2]==3) {
		imagepng($target_id,$namefile);
	}
	
	imagedestroy($target_id);
	imagedestroy($source_id);
	
	return true;
}

//Изменение размеров изображеий .jpg, .gif, .png
//$newsize - массив ширины и высоты
//использует функцию setTransparency для сохранения прозрачности в .png
function imageResize ($sourcefile, $newfile, $newsize)
{
		$picsize=getimagesize($sourcefile);
		
		if($picsize[2]==1){
			if(file_exists($sourcefile)) $source_id = imagecreatefromgif($sourcefile);
		}
		elseif($picsize[2]==2){
			if(file_exists($sourcefile)) $source_id = imagecreatefromjpeg($sourcefile);
		}
		elseif($picsize[2]==3){
			if(file_exists($sourcefile)) $source_id = imagecreatefrompng($sourcefile);
		}
		
		if(!isset($newsize[1])){
			$newsize[1]=$newsize[0]*$picsize[1]/$picsize[0];
		}
		elseif(!isset($newsize[0])){
			$newsize[0]=$newsize[1]*$picsize[0]/$picsize[1];
		}
		
		$target_id = imagecreatetruecolor ($newsize[0], $newsize[1]);
		
		if($picsize[2]==3) setTransparency($target_id, $source_id); 
		
		$target_pic=imagecopyresampled($target_id,$source_id, 0,0, 0,0, $newsize[0],$newsize[1],$picsize[0],$picsize[1]);
	
		if($picsize[2]==1){
			imagegif($target_id,$newfile);
		}
		else if($picsize[2]==2) {
			imagejpeg($target_id,$newfile,"90");
		}    
		else if($picsize[2]==3) {
			imagepng($target_id,$newfile);
		}
		imagedestroy($source_id);
		imagedestroy($target_id);
		return true;
}


//Функция наклыдывания изображения .png
//$sourcefile - исходная картинка
//$namefile - измененная картинка
//$blank - изображение в которое нужно вписать
function imageCover($copyright, $namefile, $sourcefile)
{
	//Размеры логотипа
	$picsize=getimagesize($copyright);
    $logo_x  = $picsize[0];
    $logo_y  = $picsize[1];
	$logo_id=ImageCreateFromPNG($copyright);
	
	//Размеры исходного файла
	$picsize_sourcefile=getimagesize($sourcefile);
    $sourcefile_x  = $picsize_sourcefile[0];
    $sourcefile_y  = $picsize_sourcefile[1];
	if($logo_x > $logo_y){
		$prop=$logo_x/$sourcefile_x; 
	}
	else{
		$prop=$logo_y/$sourcefile_y;
	}
	$dest_x=$logo_x/$prop;
	$dest_y=$logo_y/$prop;
	if($picsize_sourcefile[2]==1){
		$sourcefile_id = ImageCreateFromGif($sourcefile);
	}
	else if($picsize_sourcefile[2]==2) {
		$sourcefile_id = ImageCreateFromJpeg($sourcefile);
	}
	$poz_x=($sourcefile_x-$dest_x)/2;
	$poz_y=($sourcefile_y-$dest_y)/2;
	
    $target_pic=imagecopyresampled($sourcefile_id, $logo_id, $poz_x,$poz_y, 0,0, $dest_x,$dest_y,$logo_x,$logo_y);
	if($picsize_sourcefile[2]==1){
		imagegif($sourcefile_id,$namefile);
	}
	else if($picsize_sourcefile[2]==2) {
		imagejpeg($sourcefile_id,$namefile,"90");
	}
	return true;
}
			
//Используется для сохранения прозрачности в .png
function setTransparency($new_image, $image_source)
{
        $transparencyIndex = imagecolortransparent($image_source);
        $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);
       
        if ($transparencyIndex >= 0)
            $transparencyColor = imagecolorsforindex($image_source, $transparencyIndex);   
       
        $transparencyIndex = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
        imagefill($new_image, 0, 0, $transparencyIndex);
        imagecolortransparent($new_image, $transparencyIndex);
}

/**
* Генерация превьюшек для больших изображений
*
* @param string $src путь от корня сайта к исходной картинке
* @param int $size размер изображения (сторона квадрата в пикселях)
* @param int $lifeTime время жизни превьюшки в секундах (по дефолту месяц)
* @return string
*/
function MakeImage($src, $size = array(100, 100), $method = 'crop', $lifeTime = 2592000)
{
    $sourcefile = $_SERVER['DOCUMENT_ROOT'] . $src;
    $sourcefile = str_replace('//', '/', $sourcefile);
    if (file_exists($sourcefile)) {
        $ext = end(explode(".", $src)); // Расширение файла картинки
        $base_name = basename($src, "." . $ext); // Основное имя файла
        $target_file['name'] = dirname($src)."/".$base_name."_thumb_". $size[0].'x'.$size[1].".".$ext;
        $target_file['path'] = $_SERVER['DOCUMENT_ROOT'].$target_file['name'];
        if (file_exists($target_file['path']) AND filesize($target_file['path']) > 0) {
            if (filemtime($target_file['path']) + $lifeTime > time()) return $target_file['name']; // Файл есть, новый
        }
        if (file_exists($target_file['path']) AND filesize($target_file['path']) == 0) @unlink($target_file['path']); // удаление файла нулевой длины
        if($method=='crop'){
            if (imageCropToRect($sourcefile, $target_file['path'], $size)) return $target_file['name'];
        }
        elseif($method=='fit'){
            if (imageResizeToRect($sourcefile, $target_file['path'], $size)) return $target_file['name'];
        }
        elseif($method=='resize'){
            if(imageResize ($sourcefile, $target_file['path'], $size)) return $target_file['name'];
        }
    }
    return false;
}
?>