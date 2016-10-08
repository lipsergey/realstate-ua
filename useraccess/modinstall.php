<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');

$InstURL = $ltab."&ltact=addnewmodul";

if (!isset($_GET["buldact"])) {
	$BildAct = "";
}
else {
	$BildAct = $_GET["buldact"];
}


//Функция записи файлов
function writeContent($FileContent, $FileName) {
	$SFile = $FileName;
	$ft = fopen($SFile, "w");
	@fwrite($ft, $FileContent);
	@fclose($ft);
	@chmod($SFile, 0644);
}

switch($BildAct) {	case "":
		echo "Инсталяция нового модуля";
		if (!isset($_FILES) || $_FILES["disfile"]["name"] == "") {die("<script>history.back();</script>");}

		move_uploaded_file($_FILES["disfile"]['tmp_name'], "./".MODDIR."/newmod.zip");
		addNewLibs('zipfunct');
		$instances["zipfunct"]->read_zip("./".MODDIR."/newmod.zip");

		$Work = "";

		foreach ($instances["zipfunct"]->files as $FData) {			if ($FData["name"] == "about.txt") {$Work = $FData["data"]; break;}		}

		if($Work == "") {die("<h3>Не найдены инструкции по установке!</h3>");}

		$NewModParams = array();
		$TW = explode("\n", $Work);
		foreach($TW as $_ttw) {			$_ttw = trim($_ttw);
			$_tmp = explode("=", $_ttw);
			$NewModParams[$_tmp[0]] = $_tmp[1];		}

		echo "<h3>В загруженном архиве обнаружен модуль: <FONT COLOR=RED>".$NewModParams["nazvan"]."</FONT> (версия ".$NewModParams["version"]."):
		".$NewModParams["opis"]."</h3>";

		$r = mysqli_query($hlnk, "SELECT ua_modul_nazv
		FROM ".$ppt."ta_usrsadm_moduls_spis
		WHERE ua_modul_dir='".$NewModParams["moddir"]."';") or die ("Check Is It new :(");
		if (mysqli_num_rows($r) > 0) {			$ModNm = mysqli_fetch_row($r);			echo "<h3>Внимание! Обнаружен уже установленный модуль который похож на тот что вы собираетесь установить:
			<FONT COLOR=RED>".$ModNm[0]."</FONT><BR><A HREF=\"".$InstURL."&buldact=install\">Продолжите установку</A> или <A HREF=\"".$InstURL."&buldact=delete\">откажетесь</A>?</h3>";
		}
		else {			echo "<A HREF=\"".$InstURL."&buldact=install\">Установить модуль</A> или <A HREF=\"".$InstURL."&buldact=delete\">отказаться от установки</A>?</h3>";
		}
	break;
	case "delete":
		unlink("./".MODDIR."/newmod.zip");
 		echo "<SCRIPT>\n\nvar i = setTimeout(\"window.location.href=\'".$ltab."\'\", 200);\n</SCRIPT>";
	break;

	case "install":
		echo "Установка модуля";
 		addNewLibs('zipfunct');
		$instances["zipfunct"]->read_zip("./".MODDIR."/newmod.zip");

        $Work = "";
		foreach ($instances["zipfunct"]->files as $FData) {			if ($FData["name"] == "about.txt") {				$Work = $FData["data"];
				break;
			}
		}

		if($Work == "") {die("<h3>Не найдены инструкции по установке!</h3>");}
		echo "<UL>";

		$NMParams = array();
		$TW = explode("\n", $Work);
		foreach($TW as $_ttw) {			$_ttw = trim($_ttw);
			$_tmp = explode("=", $_ttw);
			$NMParams[$_tmp[0]] = $_tmp[1];
		}

		if (!file_exists("./".$NMParams["moddir"])) {			mkdir("./".$NMParams["moddir"], 0755);		}

		if (!file_exists("./modimgs")) {
			mkdir("./modimgs", 0755);
		}

        //Write Admin Scripts
		foreach ($instances["zipfunct"]->files as $FData) {
			if ($FData["dir"] == "admin/".$NMParams["moddir"]) {				writeContent($FData["data"], "./".$NMParams["moddir"]."/".$FData["name"]);
			}
			elseif ($FData["dir"] == "admin/modimgs") {
				writeContent($FData["data"], "./modimgs/".$FData["name"]);
			}
		}

		echo "<LI>Файлы модуля скопированы в панель администратора</LI>";

		$AllCl = explode(";", $NMParams["classes"]);
		if (is_array($AllCl)) {
			foreach($AllCl as $ClassNM) {				if (!file_exists("../intfunctions/".$ClassNM)) {
					mkdir("../intfunctions/".$ClassNM, 0755);
				}

				foreach ($instances["zipfunct"]->files as $FData) {
					if ($FData["dir"] == "intfunctions/".$ClassNM) {
						writeContent($FData["data"], "../intfunctions/$ClassNM/".$FData["name"]);
					}
				}
			}
			echo "<LI>Необходимые для работы модуля классы инсталлированы</LI>";
		}

		$UD = "";
		foreach ($instances["zipfunct"]->files as $FData) {
			if ($FData["dir"] == "users") {
				writeContent($FData["data"], "../".$FData["name"]);
				$UD = 1;
			}
		}
		if ($UD != "") {			echo "<LI>Установлены файлы для отображения модуля посетителю сайта. Внимание! В файлах необходимо настраивать дизайн. Без этого отображение не будет корректным </LI>";
		}

		foreach ($instances["zipfunct"]->files as $FData) {
			if ($FData["name"] == "modsql.sql") {				set_time_limit(1000);
				$RedSQL = str_replace("%SQLPREFX%", $ppt, $FData["data"]);
				$RDquery = explode("#$$\n", $RedSQL);
				$tot = count($RDquery)-1;
				foreach($RDquery as $RQDat) {
					$RQDat = trim($RQDat);
					if ($RQDat != "") {
						$tmp = mysqli_query($hlnk, $RQDat) or die("Wrong request to write commands");
					}
				}
				echo "<LI>Таблицы SQL установлены</LI>";
				break;
			}
		}

		$res = mysqli_query($hlnk, "INSERT INTO ".SQLPRFX."_moduls_spis VALUES ('', '".$NMParams["nazvan"]."', '".$NMParams["moddir"]."', '".$NMParams["modpref"]."', '".$NMParams["modexec"]."', '".$NMParams["modicon"]."');") or die ("Add new");
		$NewMod = mysqli_insert_id();

		$r = mysqli_query($hlnk, "SELECT UM.uadm_id, MAX(ua_modul_poz)
		FROM ".SQLPRFX."_main UM
		INNER JOIN ".SQLPRFX."_moduls_rights UMDRG ON UM.uadm_id=UMDRG.uadm_id
		GROUP BY UM.uadm_id;") or die ("All Users :(");
		$UsSQL = "";
		while($UsSP = mysqli_fetch_row($r)) {
			if ($UsSQL != "") {$UsSQL .= ", ";}
			$UsSQL .= "('".$NewMod."', '".$UsSP[0]."', '0', '".($UsSP[1] + 1)."')";
		}
		if ($UsSQL != "") {
			$r = mysqli_query($hlnk, "INSERT INTO ".SQLPRFX."_moduls_rights VALUES ".$UsSQL.";") or die ("Add raspred");
		}
		echo "<LI>Модуль инсталлирован в панель администратора</LI>";

		//Поиск файловых условий
		foreach ($instances["zipfunct"]->files as $FData) {
			if ($FData["name"] == "file_operation.txt") {$FOperations = $FData["data"];break;}
		}

		if (isset($FOperations) && $FOperations != "") {
			$AllLines = explode('*', $FOperations);
			$i = 1;
			foreach($AllLines as $Line) {
				list($ArchPATH, $RealPATH, $FileName) = explode(';', $Line);
				if ($RealPATH != "" && !file_exists($RealPATH)) {					@mkdir($RealPATH, 0755);
				}

				if ($ArchPATH != "" && $FileName != "") {

					foreach ($instances["zipfunct"]->files as $FData) {
						if ($FData["name"] == $FileName && $FData["dir"] == $ArchPATH) {							writeContent($FData["data"], $RealPATH.$FileName);
						}
					}
				}
			}
		}

		echo "<LI>Файловые операции проведены</LI>
		<LI>Установка модуля успешно завершена</LI>
		</OL>";

		foreach ($instances["zipfunct"]->files as $FData) {
			if ($FData["name"] == "for_config.txt") {				echo "<h3>Для правильной работы модуля, пожалуйста, добавьте в файл \"config.php\" следующий код:</h3>
				<P><TEXTAREA COLS=80 ROWS=20>";
				print($FData["data"]);
				echo "</TEXTAREA></P>";
			}
		}
		unlink("./".MODDIR."/newmod.zip");
		echo "<A HREF=\"".$ltab."\">Вернуться к списку модулей</A>";
	break;
}
?>