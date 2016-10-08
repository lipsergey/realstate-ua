<?php
class fileresizeimagick {
	var $_package='Resize by Imagick';
	var $_needle='textfunct';
	var $_version=1.0;
	var $srvpath;

	function __construct() {
		global $SPUrl, $instances;
		$this->srvpath = $SPUrl . MAINIMGCAT;
		if (!isset($instances[$this->_needle])) {
		$instances["errmsg"]->PrintErrorMsg("<h5>Невозможно использовать модуль, нет обязательного компонента - {$this->_needle}</h5>");
		}
	}

	/*
		Для загрузки изображений через Ajax
		$istohnPath - путь к файлу, загруженному через AJAX на сервер
	*/
	function uploadByAjax($istohnPath, $prevPath, $prevWidth, $prevHeight,
	$bigPath="", $bigWidth="", $bigHeight="") {
	
		if (!file_exists($istohnPath)) {
			return "";
		}

		//echo "$istohnPath, $prevPath, $prevWidth, $prevHeight, $bigPath, $bigWidth, $bigHeight";
		
		//Разберемся с путями
		if (!file_exists($this->srvpath . $prevPath)) {@mkdir($this->srvpath . $prevPath, 0755, true);}
		if ($bigPath != "" && !file_exists($this->srvpath . $bigPath)) {@mkdir($this->srvpath . $bigPath, 0755, true);}

		$filename = "file".time().".jpg";
		
		if (file_exists($this->srvpath . $prevPath . $filename)) {
			$i = 1;
			while (file_exists($this->srvpath . $prevPath . $i."_".$filename)) {
				$i = $i + 1;
			}
			$filename = $i."_".$filename;
		}
		$newFileName = $filename;

		$FileRes = $istohnPath;

		$imageSmall = new Imagick($FileRes);
		$imageSmall->cropThumbnailImage($prevWidth, $prevHeight);
		$imageSmall->setImageFormat('jpeg');
		$imageSmall->setImageCompressionQuality(90);

		@touch($this->srvpath . $prevPath . $newFileName);
		file_put_contents($this->srvpath . $prevPath . $newFileName, $imageSmall);
		@chmod($this->srvpath . $prevPath . $newFileName, 0644);
		unset($imageSmall);


		/*
		//Когда нет Imagick, TMP VERSION
		$src = imagecreatefromjpeg($FileRes);
		$w_src = imagesx($src);
		$h_src = imagesy($src);
		if (($w_src == $prevWidth && $h_src == $prevHeight) || ($w_src <= $prevWidth && $h_src <= $prevHeights)) {
			//размеры ок
			$ratio_sm = 1;
		}
		elseif ($w_src/$prevWidth >= $h_src/$prevHeight) {//берем за основу пропроцию по ширине
			$ratio_sm = $w_src/$prevWidth;
		}
		else { //значит по высоте
			$ratio_sm = $h_src/$prevHeight;
		}

		$w_smdest = round($w_src/$ratio_sm);
		$h_smdest = round($h_src/$ratio_sm);

		$rsmim = imagecreatetruecolor($w_smdest,$h_smdest);
		imagecopyresampled ($rsmim, $src, 0,0,0,0, $w_smdest, $h_smdest, $w_src, $h_src);
		imagejpeg($rsmim, $this->srvpath . $prevPath . $newFileName);
		imagedestroy($rsmim);
		chmod($this->srvpath . $prevPath . $newFileName, 0644);
		*/

		if ($bigPath != "") {

			$imageBig = new Imagick($FileRes);
			$imageBig->thumbnailImage($bigWidth, $bigHeight);
			$imageBig->setImageFormat('jpeg');
			$imageBig->setImageCompressionQuality(90);
			@touch($this->srvpath . $bigPath . $newFileName);
			file_put_contents($this->srvpath . $bigPath . $newFileName, $imageBig);
			@chmod($this->srvpath . $bigPath . $newFileName, 0644);
			unset($imageBig);

			/* Когда нет Imagick: 
			if ($bigWidth == 0 && $bigHeight == 0) {//не делать ресайз
				$ratio = 1;
			}
			elseif (($w_src == $bigWidth && $h_src == $bigHeight) || ($w_src <= $bigWidth && $h_src <= $bigHeight)) {
			//размеры ок
				$ratio = 1;
			}
			elseif ($bigWidth > 0 && $w_src/$bigWidth >= $h_src/$bigHeight) {//берем за основу пропроцию по ширине
				$ratio = $w_src/$bigWidth;
			}
			else  { //значит по высоте
				$ratio = $h_src/$bigHeight;
			}
			if ($ratio > 1) {
				$w_dest = round($w_src/$ratio);
				$h_dest = round($h_src/$ratio);

				$rbigim = imagecreatetruecolor($w_dest,$h_dest);
				imagecopyresampled ($rbigim, $src, 0,0,0,0, $w_dest, $h_dest, $w_src, $h_src);
				imagejpeg($rbigim, $this->srvpath . $bigPath . $newFileName);
				imagedestroy($rbigim);
				chmod($this->srvpath . $bigPath . $newFileName, 0644);
			}
			else {
				if (rename($istohnPath, $this->srvpath . $bigPath . $newFileName)) {
					chmod($this->srvpath . $bigPath . $newFileName, 0644);
				}
				else {
					echo "Uploading false: ".$istohnPath."";
					return false;
				}
			}
			*/
		}
		return $newFileName;
	}
}
?>