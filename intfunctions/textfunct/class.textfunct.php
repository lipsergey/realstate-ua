<?php
class textfunct {
	var $_package='Text processing functions';
	var $_version=1.01;
	var $SRVHost;

	//Функция глубокой очистки
	public function clearit($string) {
		if(empty($string)) {return false;}
		else {
			$result = str_replace(array('+', '*', "'"), array('','','',), $string);
			$result = strip_tags($result);
			$result = htmlspecialchars($result);
			if(!$result) {return false;}
			else {return $result;}
		}
	}

	//Функция транслитерации
	public function translite($cyr_str) {
		$tr = array("а"=>"a", "б"=>"b", "в"=>"v", "г"=>"g", "д"=>"d", "е"=>"e", "ё"=>"e",
		"ж"=>"zh", "з"=>"z", "и"=>"i", "й"=>"i", "к"=>"k", "л"=>"l","м"=>"m",
		"н"=>"n", "о"=>"o", "п"=>"p", "р"=>"r", "с"=>"s", "т"=>"t", "у"=>"u",
		"ф"=>"f", "х"=>"kh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"shch", "ъ"=>'',
		"ы"=>"y", "ь"=>"", "э"=>"e", "ю"=>"iu", "я"=>"ia", "А"=>"A", "Б"=>"B",
		"В"=>"V", "Г"=>"G", "Д"=>"D", "Е"=>"E", "Ё"=>"E", "Ж"=>"Zh", "З"=>"Z",
		"И"=>"I", "Й"=>"I", "К"=>"K", "Л"=>"L", "М"=>"M", "Н"=>"N", "О"=>"O",
		"П"=>"P", "Р"=>"R", "С"=>"S", "Т"=>"T", "У"=>"U", "Ф"=>"F", "Х"=>"Kh",
		"Ц"=>"Ts", "Ч"=>"Ch", "Ш"=>"Sh", "Щ"=>"Shch", "Ъ"=>'', "Ы"=>"Y", "Ь"=>"",
		"Э"=>"E", "Ю"=>"Iu", "Я"=>"Ia");
		return strtr($cyr_str,$tr);
	}

	//Перекодирование текста с одной кодировки в другую
	public function TextConvert($text, $CodFrom = "w", $CodTo = "k") {
		return convert_cyr_string($text, $CodFrom, $CodTo);
	}

	//Разбор адреса (конфиг) на составляющие
	public function URLProc() {
		global $SRVUrl;
		$hostparts = parse_url($SRVUrl);

		$this->SRVHost["fullname"] = $hostparts["host"];
		if (substr_count($hostparts["host"], "www.") > 0) {
			$this->SRVHost["2levdomen"] = substr($hostparts["host"], 4, strlen($hostparts["host"]));
		}
		else {$this->SRVHost["2levdomen"] = $hostparts["host"];}

		$dataz = explode('.', $hostparts["host"]);
		if ($dataz[0] != "www") {$this->SRVHost["1levdomen"] = $dataz[0];}
		else {$this->SRVHost["1levdomen"] = $dataz[1];}
	}

	//Проверка Email на валидность
	public function ValidMail($checkinmail) {
		if (preg_match("/([\.\w]+)*\w@\w((\.\w)*(\-)*\w+)*\.\w{2,3}$/", $checkinmail)) {
			$this->URLProc();
			if (substr_count($checkinmail, $this->SRVHost["1levdomen"]) == 0) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	/*
	   Генерация нормализованного пароля
	   $number - количество знаков в пароле
	*/
	public function generate_normalize_passw($number) {
		$arr = array('a','b','c','d','e','f','g','h','i','j','k',
		'l','m','n','o','p','r','s','t','u','v','x','y','z',
		'A','B','C','D','E','F','G','H','I','J','K','L',
		'M','N','O','P','R','S','T','U','V','X','Y','Z',
		'1','2','3','4','5','6','7','8','9','0','.',',',
		'(',')','[',']','!','?','&','^','%','@','*','$',
		'<','>','/','|','+','-','{','}','`','~');

		$pass = "";
		$n = count($arr) - 1;
		for($i = 0; $i < $number; $i++) {
			$index = rand(0, $n);
			$pass .= $arr[$index];
		}
		return $pass;
	}

	/*
	Генерация пароля определенного типа
	$number - количество знаков в пароле
	*/
	public function generate_sometype_passw($number, $p_dec = 1, $p_zagl = 0, $p_str = 0) {
		$pawd = "";
		$chrstart = array();
		$chrfin = array();

		if ($p_dec == 1) { // цифры
			$chrstart[] = 48;
			$chrfin[] = 57;
		}
		if ($p_zagl == 1) {// заглавные буквы
			$chrstart[] = 65;
			$chrfin[] = 90;
		}
		if ($p_str == 1) { // строчные буквы
			$chrstart[] = 97;
			$chrfin[] = 122;
		}

		$l = count($chrstart) - 1;

		for ($i = 0 ; $i < $number ; $i++) {
			$j=rand(0,$l);
			$pawd.=chr(rand($chrstart[$j], $chrfin[$j]));
		}
		return $pawd;
	}

	/*
		Function clearing extra line breaks. 
		Allowed line break - is after point (.) symbol
	*/
	public function ClearExtraLB($Text) {
		$Text = str_replace(array(".\r\n", ".\n", ".\r"), array(".<br>", ".<br>", ".<br>"), $Text);
		$Text = str_replace(array("\r\n", "\n", "\r"), array(" ", " ", " "), $Text);
		$Text = str_replace("<br>", "\r\n", $Text);
		return $Text;
	}

	//Function clearing double spaces
	public function ClearDoubleSpace($Text) {
		return str_replace("  ", " ", $Text);
		
	}
}
?>