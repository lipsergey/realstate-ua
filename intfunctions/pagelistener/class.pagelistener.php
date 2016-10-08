<?php
class pagelistener {
	var $_package = 'Class for split data to pages. Split by SQL Limit function';
	var $_version = 3.7;
	var $PgNumberHTML;
	var $NumbersBlockHTML;
	var $CurPage = 1;
	var $RezPerPage = 40; //Значение по-умолчанию
	var $TotalPages = 1;
	var $FirstNumber;
	var $BlockKolv; //Количество блоков

	function pagelistener() {
		global $instances, $rezinpage;
		$this->PgNumberHTML = PAGENUMBERHTML;
		$this->NumbersBlockHTML = PAGEBLOCKHTML;
		if (isset($_GET["pags"]) && is_numeric($_GET["pags"])) {
			$this->CurPage = $_GET["pags"];
		}
	}

	//Создание HTML кода блока ссылок на страницы
	function MakePageBlockSimple($TotalQuerys, $PageUrl, $Dopurl="") {
		$_pglist = $PageUrl;
		if ($Dopurl == "") {$PageUrl .= "?pags=";}
		else {$PageUrl .= $Dopurl."&pags=";}
		$this->TotalPages = ceil($TotalQuerys/$this->RezPerPage);

		$_numbspis = "";
		for ($i=1; $i<=$this->TotalPages; $i++) {
			if ($_numbspis != "") {$_numbspis .= "&nbsp;&nbsp;";}
			$_numbcode = "<a href=\"".$PageUrl.$i."\">".$i."</a>";
            if ($this->CurPage == $i) {$_pgstyle = "page1";}
            else {$_pgstyle = "page";}

			$_numbspis .= str_replace(array("%PAGENUMBER%", "%PAGESTYLE%"), array($_numbcode, $_pgstyle), $this->PgNumberHTML);
		}

		$NumbBlock = str_replace("%PAGENUMBERS%", $_numbspis, $this->NumbersBlockHTML);
		return $NumbBlock;
	}

	//Нарезка номеров страниц на блоки
	function MakePageBlock($TotalQuerys, $PageUrl, $Dopurl="") {
		$this->TotalPages = ceil($TotalQuerys/$this->RezPerPage);
		if ($this->TotalPages <= 1) {return false;}
		$this->FirstNumber = $TotalQuerys - ($this->CurPage-1)*$this->RezPerPage;

		$this->BlockKolv = ceil($this->TotalPages/BLOCKOFVISIBLEPAGES);

		if (USEBLOCKSINPAGEBLOCK == 0 || $this->BlockKolv <= 1) {
			$RetVal = $this->MakePageBlockSimple($TotalQuerys, $PageUrl, $Dopurl);
			return $RetVal;
		}

		$_pglist = $PageUrl;
		if ($Dopurl == "") {$PageUrl .= "?pags=";}
		else {$PageUrl .= $Dopurl."&pags=";}

		$_curBlock = ceil($this->CurPage / BLOCKOFVISIBLEPAGES); //Рассчет текущего блока
		$_FirstPageInBlock = $_curBlock * BLOCKOFVISIBLEPAGES - (BLOCKOFVISIBLEPAGES - 1);

		$_LastPageInBlock = $_FirstPageInBlock + BLOCKOFVISIBLEPAGES - 1;
		if ($_LastPageInBlock > $this->TotalPages) {$_LastPageInBlock = $this->TotalPages;} //Количество страниц в блоке не может быть больше общего количества страниц

		$_tmpHTML = "";

		//Если блок не первый, то можно делать управление "назад"
		if ($_curBlock != 1) {
			$_tmpHTML .= str_replace("%FIRSTPAGEURL%", $PageUrl."1", BLOCKFIRSTPAGEHTML);
			if ($_curBlock > 1) {
				if ($_tmpHTML != "") {$_tmpHTML .= "&nbsp;&nbsp;";}
				$_tmpHTML .= str_replace("%PREVBLOCK%", $PageUrl.($_FirstPageInBlock-1), BLOCKPREVBLOCKHTML);
			}
		}

		for ($i=$_FirstPageInBlock; $i<=$_LastPageInBlock; $i++) {
			if ($_tmpHTML != "") {$_tmpHTML .= "&nbsp;&nbsp;";}

			$_numbcode = "<a href=\"".$PageUrl.$i."\">".$i."</a>";
            if ($this->CurPage == $i) {$_pgstyle = "page1";}
            else {$_pgstyle = "page";}

			$_tmpHTML .= str_replace(array("%PAGENUMBER%", "%PAGESTYLE%"), array($_numbcode, $_pgstyle), $this->PgNumberHTML);
		}

		//Управление "вперед"
		if ($_curBlock != $this->BlockKolv) {
			if ($_curBlock < $this->BlockKolv) {
				if ($_tmpHTML != "") {$_tmpHTML .= "&nbsp;&nbsp;";}
				$_tmpHTML .= str_replace("%NEXTBLOCK%", $PageUrl.($_LastPageInBlock+1), BLOCKNEXTBLOCKHTML);
			}
			if ($_tmpHTML != "") {$_tmpHTML .= "&nbsp;&nbsp;";}
			$_tmpHTML .= str_replace("%LASTPAGEURL%", $PageUrl.$this->TotalPages, BLOCKLASTPAGEHTML);
		}

		$NumbBlock = str_replace("%BLOCKCODE%", $_tmpHTML, BLOCKTOTALHTML);
		return $NumbBlock;
	}

	//Создание Limit условия для получения данных из БД
	function MakeSQLLimitCode() {
		$Start = ($this->CurPage-1) * $this->RezPerPage;

		return "LIMIT ".$Start.", ".$this->RezPerPage;	}
}
?>
