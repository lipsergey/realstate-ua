<?php
// (c) Lipsits Sergey
// Класс crontime для вычисления времени запуска задачиы

class crontime {	var $Year;
	var $Month;
	var $Day;
	var $Hour;
	var $Minut;
	var $Second;

	//Вычисляемые (т.е. время след.запуска)
	var $cYear;
	var $cMonth;
	var $cDay;
	var $cHour;
	var $cMinut;
	var $cSecond;

	//Рассмотрение будущего периода
	//1 - активность флага "каждый"
	//2 - указан период в будущем
	var $nYear;
	var $nMonth;
	var $nDay;
	var $nHour;

	//Было ли увеличение значения по действию "следующий шаг"
	var $addYear;
	var $addMonth;
	var $addDay;
	var $addHour;

	var $MaxDays = array(
		"1" => 31,
		"2" => 28,
		"3" => 31,
		"4" => 30,
		"5" => 31,
		"6" => 30,
		"7" => 31,
		"8" => 31,
		"9" => 30,
		"10" => 31,
		"11" => 30,
		"12" => 31);

	function crontime() {		$this->Year = date("Y");
		$this->Month = date("n");
		$this->Day = date("j");
		$this->Hour = date("G");
		$this->Minut = date("i");
		$this->Second = date("s");
	}

	function TaskParams($tTaskArr) {		if (!is_array($tTaskArr)) {return false;}
		if ($tTaskArr["Task_Active"] == 0) {return "never";}

		$this->cYear = ""; $this->cMonth = ""; $this->cDay = ""; $this->cHour = ""; $this->cMinut = ""; $this->cSecond = 0;
		$this->nYear = ""; $this->nMonth = ""; $this->nDay = ""; $this->nHour = ""; $this->addYear = 0; $this->addMonth = 0;
		$this->addDay = 0; $this->addHour = 0;

		if ($this->CalcYear($tTaskArr["Task_Year"]) === false) {return "never";}
		if ($this->CalcMonth($tTaskArr["Task_Month"]) === false) {return "never";}
		if ($this->CalcDay($tTaskArr["Task_Day"]) === false) {return "never";}
		if ($this->CalcHour($tTaskArr["Task_Hour"]) === false) {return "never";}
		if ($this->CalcMinute($tTaskArr["Task_Minute"]) === false) {return "never";}

		//Теперь нарисуем текст
		$NextRun = strtotime($this->cDay.".".$this->cMonth.".".$this->cYear." ".$this->cHour.":".$this->cMinut.":00");

		$TmDiff = $NextRun - time();

		$TmText = "";

		if ($TmDiff < 60) {$TmText = "in ".$TmDiff." sec.";}
		elseif ($TmDiff < 3600) {$TmText = "in ".floor($TmDiff/60)." min.";}
		elseif ($TmDiff < 86400) {$TmText = "in ".floor($TmDiff/3600)." h.";}
		elseif ($TmDiff < 2592000) {$TmText = "in ".floor($TmDiff/86400)." d.";}
		else {$TmText = date("d.m.Y H:i:s", $NextRun);}

		return $TmText;
	}

	//Вычисляем год
	function CalcYear($tStrtYear) {		if ($tStrtYear != "*" && $tStrtYear < $this->Year) {return false;}

		if ($tStrtYear == "*") {$this->cYear = $this->Year; $this->nYear = 1;}
		else {			$this->cYear = $tStrtYear;
			if ($tStrtYear > $this->Year) {				$this->nYear = 2;			}
		}
	}

	//Добавить год
	function GetNextYear() {		if ($this->addYear == 0) {
			$this->cYear += 1;
			$this->addYear = 1;
		}
		return true;	}

	//вычисляем месяц
	function CalcMonth($tStrtMonth) {

		if ($tStrtMonth != "*" && $tStrtMonth < $this->Month && $this->cYear == $this->Year && $this->nYear != 1) {return false;}

		//Каждый месяц
		if ($tStrtMonth == "*") {$this->cMonth = $this->Month; $this->nMonth = 1;}

		else {			$this->cMonth = $tStrtMonth;

			//Месяц уже был, а будет ли следующий год?
			if ($tStrtMonth != "*" && $tStrtMonth < $this->Month && $this->nYear == 1) {
				$this->GetNextYear(); //Прибавим год
			}
			//Месяца не было, т.к. он или в будущем году, или в текущем, но не наступил
			elseif(($tStrtMonth < $this->Month && $this->nYear == 2) || $tStrtMonth > $this->Month) {$this->nMonth = 2;}
		}
	}

	//Добавить месяц
	function GetNextMonth() {
		if ($this->addMonth == 0) {
			if ($this->cMonth == 12) {				$this->GetNextYear();
				$this->cMonth = 1;
			}
			else {				if ($this->MaxDays[$this->cMonth+1] < $this->cDay) {					$this->cMonth += 2;
				}
				else {
					$this->cMonth += 1;
				}
			}
			$this->addMonth = 1;
		}
		return true;
	}

	//вычисляем день
	function CalcDay($tStrtDay) {
		if ($tStrtDay != "*" && $tStrtDay < $this->Day && $this->cMonth == $this->Month &&
		$this->nMonth != 1 && $this->nYear != 1) {return false;}

		//Каждый день
		if ($tStrtDay == "*") {			if ($this->cMonth == $this->Month) {$this->cDay = $this->Day;}
			else {$this->cDay = 1;}
			$this->nDay = 1;
		}
		else {			$this->cDay = $tStrtDay;

			//День уже был. А будет ли он в следующем месяце или в том же месяце в другом году?
			if ($tStrtDay != "*" && $tStrtDay < $this->Day) {
				if ($this->nMonth == 1) {$this->GetNextMonth();}
				elseif ($this->nYear == 1) {$this->GetNextYear();}			}
			elseif(($tStrtDay < $this->Day && $this->nMonth == 2) || $tStrtDay > $this->Day) {//Дня не было
				$this->nDay = 2;
			}
		}
	}

	//Добавить день
	function GetNextDay() {		if ($this->addDay == 0) {			if ($this->cDay == $this->MaxDays[$this->cMonth]) {				$this->GetNextMonth();
				$this->cDay = 1;
			}
			else {
				$this->cDay += 1;
			}
			$this->addDay = 1;
		}
		return true;
	}


	//вычисляем час
	function CalcHour($tStrtHour) {
		if ($tStrtHour != "*" && $tStrtHour < $this->Hour && $this->cDay == $this->Day &&
		$this->nMonth != 1 && $this->nYear != 1 && $this->nDay != 1) {return false;}

		if ($tStrtHour == "*") {			if ($this->cDay == $this->Day) {$this->cHour = $this->Hour;}
			else {$this->cHour = 0;}

			$this->nHour = 1;
		}
		else {			$this->cHour = $tStrtHour;

			//Этот час уже был, а будет ли он в следующем дне и т.п
			if ($tStrtHour != "*" && $tStrtHour < $this->Hour && $this->cDay == $this->Day) {
				if ($this->nDay == 1) {$this->GetNextDay();}
				elseif ($this->nMonth == 1) {$this->GetNextMonth();}
				elseif ($this->nYear == 1) {$this->GetNextYear();}
			}

			elseif(($tStrtHour < $this->Hour && $this->nDay == 2) || $tStrtHour > $this->Hour) {//Часа еще не было
				$this->nHour = 2;
			}

		}
	}

	//Добавить час
	function GetNextHour() {		if ($this->addHour == 0) {			if ($this->cHour == 23) {
				$this->GetNextDay();
				$this->cHour = 0;
			}
			else {
				$this->cHour += 1;
			}
			$this->addHour = 1;
		}
		return true;
	}

	//вычисляем минуту
	function CalcMinute($tStrtMinute) {
		$TimeArr = array();
		if (substr_count($tStrtMinute, "@") > 0) {			$TimeArr = $this->getAvalMinut($tStrtMinute);
		}
		else {			$TimeArr[0] = $tStrtMinute;		}

		if ($this->nHour != 2) {//Это не будущий час..

			foreach ($TimeArr as $stTime) {				if ($this->cHour >= $this->Hour && $stTime > $this->Minut) {//Наше время
					$this->cMinut = $stTime;
					break;
				}
			}
		}

		if ($this->cMinut == "") {//Минуту не нашли, пойдем в следующий час			$this->cMinut = $TimeArr[0];
			if ($this->nHour != 2 && $this->cHour == $this->Hour) {
				if ($this->nHour == 1) {$this->GetNextHour();}				elseif ($this->nDay == 1) {$this->GetNextDay();}
				elseif ($this->nMonth == 1) {$this->GetNextMonth();}
				elseif ($this->nYear == 1) {$this->GetNextYear();}
			}
		}
	}

	//Получить возможные значения минут
	function getAvalMinut($tcMinute) {		switch($tcMinute) {			case "@10":
				return array("0", "10", "20", "30", "40", "50");
			break;

			case "@20":
				return array("0", "20", "40");
			break;

			case "@30":
				return array("0", "30");
			break;

			default:
				return false;
			break;
		}	}
}
?>