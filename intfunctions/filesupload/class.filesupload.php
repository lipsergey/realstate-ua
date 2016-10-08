<?php
class filesupload {
 var $_package='Uploading Files';
 var $_needle='textfunct';
 var $_version=3.6;
 var $srvpath;

 function filesupload() {  global $SPUrl, $instances;
  $this->srvpath = $SPUrl . MAINIMGCAT;
  if (!isset($instances[$this->_needle])) {   $instances["errmsg"]->PrintErrorMsg("<h5>Невозможно использовать модуль, нет объязательного компонента - {$this->_needle}</h5>");
  }
 }

 /*
 Функция загрузки изображения с подгонкой по размерам
 Описание переменных:
 $vupl     - тип загрузки:
             1 - переименование в числовой код;
             2 - преобразование в транслит
 $istohn   - хранит название поля FILE в форме
 $mkprevew - создавать ли превью (по умолчанию - нет)
 $pathtsav - куда сохранять (имя папки внутри папки imgs)

 $bigim_w  - ширина большой картинки (обязательный параметр)
 $bigim_h  - высота большой картинки (обязательный параметр)
 $smim_w   - ширина маленькой картинки
 $smim_h   - высота маленькой картинки
 */

 function uplfile($vupl, $istohn, $uplfilepathsave, $insidename="", $bigim_w, $bigim_h, $mkprevew = "", $previewfilepathsave = "", $smim_w = "", $smim_h = "") {  global $instances, $SPUrl;
  if ($_FILES[$istohn]['type'] != "image/pjpeg" && $_FILES[$istohn]['type'] != "image/jpeg") {   $instances["errmsg"]->PrintErrorMsg("<h5>Ошибка: неверный тип загружаемого файла - это не JPG!</h5>");
   return false;
  }
  else {   if ($vupl == "2") {
    if (preg_match("/([а-яА-Я])/", $_FILES[$istohn]['name'])) {
     $filename = strtolower($instances["textfunct"]->translite($_FILES[$istohn]['name']));
    }
    else {$filename = strtolower($_FILES[$istohn]['name']);}
   }
   elseif ($vupl == "3" ) {
    $filename = $insidename;
   }
   else {
    $filename = preg_replace('| |si', '_', $_FILES[$istohn]['name']);
    preg_match_all("|(\.\D{3,4})$|", $_FILES[$istohn]['name'], $ex);
    $t = strtolower($ex[0][0]);
    $filename = time().$t;
   }

   $uplpath = $SPUrl . $uplfilepathsave . "/";

   //блок исключения возможностей дублей
   if (file_exists($uplpath.$filename)) {
     $bigwrong = "<H2>Ошибка: файл $filename со средней картинкой уже есть на сервере! Новый файл не загружался! Маленький не создавался!</H2>";
     return false;
   }

   $src = imagecreatefromjpeg($_FILES[$istohn]['tmp_name']);
   $w_src = imagesx($src);
   $h_src = imagesy($src);

   if ($mkprevew == "1" && $smim_w != "" && $smim_h != "" && $previewfilepathsave != "") {//создание превью
    //Проверка - нужно ли делать ресайз фотки для превью
    if (($w_src == $smim_w && $h_src == $smim_h) || ($w_src <= $smim_w && $h_src <= $smim_h)) {
     //размеры ок
     $ratio_sm = 1;
    }
    elseif ($w_src/$smim_w >= $h_src/$smim_h) {//берем за основу пропроцию по ширине
     $ratio_sm = $w_src/$smim_w;
    }
    else  { //значит по высоте
     $ratio_sm = $h_src/$smim_h;
    }
    $previewpath = $SPUrl . $previewfilepathsave . "/";

    $w_smdest = round($w_src/$ratio_sm);
    $h_smdest = round($h_src/$ratio_sm);

    $ressmim = imagecreatetruecolor($w_smdest,$h_smdest);
    imagecopyresampled ($ressmim, $src, 0,0,0,0, $w_smdest, $h_smdest, $w_src, $h_src);
    imagejpeg($ressmim, $previewpath.$filename);
    imagedestroy($ressmim);
    chmod($previewpath.$filename, 0644);
   }

   if (!is_numeric($bigim_w) || !is_numeric($bigim_h)) {
    $instances["errmsg"]->PrintErrorMsg("<h5>Ошибка: в загрузчике не заданы размеры картинки</h5>");
    return false;
   }
   else {
    //Проверка - нужно ли делать ресайз большой фотки
    if ($bigim_w == 0 && $bigim_h == 0) {//не делать ресайз     $ratio = 1;
    }
    elseif (($w_src == $bigim_w && $h_src == $bigim_h) || ($w_src <= $bigim_w && $h_src <= $bigim_h)) {
    //размеры ок
     $ratio = 1;
    }
    elseif ($w_src/$bigim_w >= $h_src/$bigim_h) {//берем за основу пропроцию по ширине
     $ratio = $w_src/$bigim_w;
    }
    else  { //значит по высоте
     $ratio = $h_src/$bigim_h;
    }
    if ($ratio > 1) {
     $w_dest = round($w_src/$ratio);
     $h_dest = round($h_src/$ratio);

     $resbigim = imagecreatetruecolor($w_dest,$h_dest);
     imagecopyresampled ($resbigim, $src, 0,0,0,0, $w_dest, $h_dest, $w_src, $h_src);
     imagejpeg($resbigim, $uplpath.$filename);
     imagedestroy($resbigim);
     chmod($uplpath.$filename, 0644);
    }
    else {
     if (move_uploaded_file($_FILES[$istohn]['tmp_name'], $uplpath.$filename)) {
      chmod($uplpath.$filename, 0644);
     }
     else {
      $instances["errmsg"]->PrintErrorMsg("<h5>Загрузка файла \"".$_FILES[$istohn]['name']."\" окончилась неудачей</h5>");
      return false;
     }
    }
    return $filename;
   }
  }
 }

 /*
  Функция загрузки файла
  $istohn   - хранит название поля FILE в форме
  $pathtsav - куда сохранять (имя папки внутри пути, устанавливаемого в $SPUrl в config)
 */
 public function nongrafupload($istohn, $pathtsav, $filetypetext) {  global $instances, $SPUrl, $bigwrong;
  if (preg_match("/([а-яА-Я])/", $_FILES[$istohn]['name'])) {
   $filename = strtolower($instances["textfunct"]->translite($_FILES[$istohn]['name']));
  }
  else {$filename = strtolower($_FILES[$istohn]['name']);}
  $filename = str_replace(" ", "_", $filename);

  $fuplpath = $SPUrl . $pathtsav . "/";

  if (file_exists($fuplpath.$filename)) {
   $bigwrong = "<H2>Ошибка: файл $filename с $filetypetext уже есть на сервере! Новый файл не загружался!</H2>";
   return false;
  }

  if (move_uploaded_file($_FILES[$istohn]['tmp_name'], $fuplpath . $filename)) {
   chmod($fuplpath . $filename, 0644);
   return $filename;
  }
  else {
   $instances["errmsg"]->PrintErrorMsg("<h5>Загрузка файла \"".$_FILES[$istohn]['name']."\" окончилась неудачей</h5>");
   return false;
  }
 }

	/*
	Функция загрузки файла с подбором имени
	$istohn   - хранит название поля FILE в форме
	$pathtsav - куда сохранять (имя папки внутри пути, устанавливаемого в $SPUrl в config)
	$type - 1 - брать имя файла из имени загружаемого файла
		  2 - брать имя файла из параметра функции ($insfilename)
	$insfilename - имя файла для сохранения
	*/
	public function uplfilepodbor($istohn, $pathtsav, $type = "1", $insfilename = "") {
		global $instances, $SPUrl;
		if ($type == "2" && $insfilename != "") {			$filename = $insfilename;
		}
		else {
			if (preg_match("/([а-яА-Я])/", $_FILES[$istohn]['name'])) {
				$filename = strtolower($instances["textfunct"]->translite($_FILES[$istohn]['name']));
			}
			else {$filename = strtolower($_FILES[$istohn]['name']);}
			$filename = str_replace(" ", "_", $filename);
		}

		$fuplpath = $SPUrl . $pathtsav . "/";
		$this->CheckPath($fuplpath, 1);

		if (file_exists($fuplpath.$filename)) {
			$i = 1;
			while (file_exists($fuplpath.$i."_".$filename)) {
				$i += 1;
			}
			$filename = $i."_".$filename;
		}

		if (move_uploaded_file($_FILES[$istohn]['tmp_name'], $fuplpath . $filename)) {
			chmod($fuplpath . $filename, 0644);
			return $filename;
		}
		else {
			$instances["errmsg"]->PrintErrorMsg("<h5>Загрузка файла \"".$_FILES[$istohn]['name']."\" окончилась неудачей</h5>");
			return false;
		}
	}

	//Функция для проверки наличия пути
	public function CheckPath($filepath, $isCreate = 0) {
		if(!file_exists($filepath)) {
			if ($isCreate == 1) {
				@mkdir($filepath, 0755,true);
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return true;
		}
	}
}
?>