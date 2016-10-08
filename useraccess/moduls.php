<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');
/* Связанные таблицы  */

$ltab = $ModURL."&modact=moduls";

if (!isset($ltact)) {
	$ltact = "";
	echo __("Список модулей сайта");
}
else {
	echo "<A HREF=\"".$ltab."\">".__("Список модулей сайта")."</A> | ";
}

switch ($ltact) {
	case "":
		echo "<P>&nbsp;<TABLE BORDER=1 class=\"table table-bordered\" style=\"width: auto;\">
		<TR BGCOLOR=SILVER>
		<TD ALIGN=CENTER><B>Modul</B></TD>
		<TD ALIGN=CENTER><B>Icon</B></TD>
		<TD ALIGN=CENTER><B>Edit</B></TD>
		<TD ALIGN=CENTER><B>Del</B></TD>
		</TR>
		<TR BGCOLOR=YELLOW><TD COLSPAN=5 ALIGN=CENTER><A HREF=\"".$ltab."&ltact=addnew\">Add new</A></TD></TR>";

		$rf = mysqli_query($hlnk, "SELECT ua_modul_id, ua_modul_nazv, ua_modul_icon
		FROM ".SQLPRFX."_moduls_spis
		ORDER BY BINARY(ua_modul_nazv);") or die ("All Mods :(");
		while ($MdSpisk = mysqli_fetch_array($rf)) {			if ($MdSpisk["ua_modul_icon"] == "") {$IMG = "&nbsp;";}
			else {$IMG = "<IMG SRC=\"modimgs/".$MdSpisk["ua_modul_icon"]."\">";}

			echo "<TR>
			<TD>".$MdSpisk["ua_modul_nazv"]."</TD>
			<TD ALIGN=CENTER>".$IMG."</TD>
			<TD ALIGN=CENTER><A HREF=\"".$ltab."&ltact=edit&mzid=".$MdSpisk["ua_modul_id"]."\">Edit</TD>
			<TD ALIGN=CENTER><A HREF=\"".$ltab."&ltact=del&mzid=".$MdSpisk["ua_modul_id"]."\" ONCLICK=\"javascript:if(confirm('".__("Действительно хотите удалить")."?')) {return true;} else{return false;}\">Del</A></TD>\n</TR>\n";
		}
		echo "\n</TABLE>\n";
	break;

	/*case "addnew": //Добавление модуля
		echo "Добавление модуля<P>
		<FORM NAME=main ACTION=\"".$ltab."&ltact=addnewmodul\" METHOD=POST enctype=\"multipart/form-data\">
		Архив (zip) с дистрибутивом модуля: <INPUT TYPE=\"FILE\" NAME=\"disfile\">&nbsp;<INPUT TYPE=SUBMIT VALUE=\"Загрузка\">
		</FORM>
		<P>или <A HREF=\"".$ltab."&ltact=manaddnew\">Ручное добавление модуля</A></P>";
	break;*/

	case "addnew": //Ручное добавление модуля
		echo "Add new modul<P>&nbsp;
		<FORM NAME=main ACTION=\"".$ltab."&ltact=savenew\" METHOD=POST enctype=\"multipart/form-data\">
		<TABLE BORDER=1 class=\"table table-bordered\" style=\"width: auto;\">
		<TR><TD BGCOLOR=SILVER><B>Name</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT SIZE=40 NAME=\"mod_name\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>Directory</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT SIZE=40 NAME=\"mod_dir\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>File</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT SIZE=40 NAME=\"mod_file\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>SQL prefix</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT SIZE=40 NAME=\"mod_sql\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>Icon</B></TD><TD ALIGN=CENTER><INPUT TYPE=FILE SIZE=30 NAME=\"iconfile\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>SQL-code</B><BR>%SQLPREFX% - префикс проекта</TD><TD ALIGN=CENTER><TEXTAREA NAME=\"modsqlcod\" COLS=80 ROWS=15></TEXTAREA></TD></TR>
		<TR><TD BGCOLOR=SILVER COLSPAN=2 ALIGN=CENTER><INPUT TYPE=SUBMIT VALUE=\"".__("Сохранить")."\"></TD></TR>
		</TABLE>\n";
	break;

	/*case "addnewmodul":
		include("modinstall.php");
	break;*/

	case "savenew": //Сохранить
		$_MODNazv = str_replace("'", '', $_POST["mod_name"]);
		$_MODNazv = str_replace('"', '&quot;', $_MODNazv);

		$_MODDir = str_replace("'", '', $_POST["mod_dir"]);
		$_MODDir = str_replace('"', '&quot;', $_MODDir);

		$_MODFile = str_replace("'", '', $_POST["mod_file"]);
		$_MODFile = str_replace('"', '&quot;', $_MODFile);

		$_MODSQL = str_replace("'", '', $_POST["mod_sql"]);
		$_MODSQL = str_replace('"', '&quot;', $_MODSQL);

		$_MODIcon = "";

		if (isset($_FILES['iconfile']['name']) && $_FILES['iconfile']['name'] != "") {
			addNewLibs('filesupload');
			$_MODIcon = $instances["filesupload"]->uplfilepodbor("iconfile", "/modimgs");
		}

		$res = mysqli_query($hlnk, "INSERT INTO ".SQLPRFX."_moduls_spis VALUES ('', '".$_MODNazv."', '".$_MODDir."', '".$_MODSQL."', '".$_MODFile."', '".$_MODIcon."');") or die ("Add new");
		$NewMod = mysqli_insert_id($hlnk);

		$r = mysqli_query($hlnk, "SELECT UM.uadm_id, MAX(ua_modul_poz)
		FROM ".SQLPRFX."_main UM
		INNER JOIN ".SQLPRFX."_moduls_rights UMDRG ON UM.uadm_id=UMDRG.uadm_id
		GROUP BY UM.uadm_id;") or die ("All Users :(");
		$UsSQL = "";
		while($UsSP = mysqli_fetch_row($r)) {			if ($UsSQL != "") {$UsSQL .= ", ";}
			$UsSQL .= "('".$NewMod."', '".$UsSP[0]."', '0', '".($UsSP[1] + 1)."')";		}

		if ($UsSQL != "") {			$r = mysqli_query($hlnk, "INSERT INTO ".SQLPRFX."_moduls_rights VALUES ".$UsSQL.";") or die ("Add raspred");
		}

		if (isset($_POST["modsqlcod"]) && $_POST["modsqlcod"] != "") {			$_MMSQL = $_POST["modsqlcod"];
			if (substr_count($_MMSQL, "DELETE") > 0 || substr_count($_MMSQL, "SELECT") > 0 || substr_count($_MMSQL, "DROP") > 0
			|| substr_count($_MMSQL, "UNION") > 0 ) {die("<h2>Недопустимый SQL код</h2>");}

			$_MMSQL = str_replace('%SQLPREFX%', $ppt, $_MMSQL);
			$_MMSQL = str_replace("\r\n", '', $_MMSQL);
			$_MMSQL = str_replace("#$$", '', $_MMSQL);
			$_MMSQL = mysqli_real_escape_string($_MMSQL);			$r = mysqli_query($hlnk, $_MMSQL) or die ("<hr>$_MMSQL<HR>Run SQL Code wrong: ".mysqli_error()."<HR>");		}

		echo "<H2>Добавлено!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href=\'".$ltab."\'\", 200);\n</SCRIPT>";
	break;

	case "edit": //редактирование
		if (!isset($_GET["mzid"]) || !is_numeric($_GET["mzid"])) {die("<script>history.back();</script>");}
		$_Mzid = $_GET["mzid"];

		$r = mysqli_query($hlnk, "SELECT ua_modul_nazv, ua_modul_dir, ua_modul_sqlpref, ua_modul_mfile, ua_modul_icon
		FROM ".SQLPRFX."_moduls_spis
		WHERE ua_modul_id='".$_Mzid."';") or die ("Get Mod :(");
		$ModData = mysqli_fetch_array($r);

		echo "Edit<P>&nbsp;
		<FORM NAME=main ACTION=\"".$ltab."&ltact=savchange&mzid=".$_Mzid."\" METHOD=POST enctype=\"multipart/form-data\">
		<TABLE BORDER=1 class=\"table table-bordered\" style=\"width: auto;\">
		<TR><TD BGCOLOR=SILVER><B>Name</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT SIZE=40 NAME=\"mod_name\" VALUE=\"".$ModData["ua_modul_nazv"]."\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>Directory</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT SIZE=40 NAME=\"mod_dir\" VALUE=\"".$ModData["ua_modul_dir"]."\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>File</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT SIZE=40 NAME=\"mod_file\" VALUE=\"".$ModData["ua_modul_mfile"]."\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>SQL prefix</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT SIZE=40 NAME=\"mod_sql\" VALUE=\"".$ModData["ua_modul_sqlpref"]."\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>Icon</B></TD><TD ALIGN=CENTER>";
		if ($ModData["ua_modul_icon"] == "") { echo "<INPUT TYPE=file NAME=\"iconfile\">";}
		else {
			echo "<INPUT TYPE=HIDDEN NAME=\"icondb\" VALUE=\"".$ModData["ua_modul_icon"]."\">
			<IMG SRC=\"modimgs/".$ModData["ua_modul_icon"]."\" BORDER=0><BR>
			<A HREF=\"".$ltab."&ltact=delfil&mzid=".$_Mzid."\" ONCLICK=\"javascript:if(confirm('".__("Действительно хотите удалить")."?')) {return true;} else{return false;}\">Del</A>";
		}
		echo "</TD></TR>
		<TR><TD BGCOLOR=SILVER COLSPAN=2 ALIGN=CENTER><INPUT TYPE=SUBMIT VALUE=\"".__("Сохранить")."\"></TD></TR>
		</TABLE>\n";
	break;

	case "savchange": //сохранение изменений
		if (!isset($_GET["mzid"]) || !is_numeric($_GET["mzid"])) {die("<script>history.back();</script>");}
		$_Mzid = $_GET["mzid"];

		$_MODNazv = str_replace("'", '', $_POST["mod_name"]);
		$_MODNazv = str_replace('"', '&quot;', $_MODNazv);

		$_MODDir = str_replace("'", '', $_POST["mod_dir"]);
		$_MODDir = str_replace('"', '&quot;', $_MODDir);

		$_MODFile = str_replace("'", '', $_POST["mod_file"]);
		$_MODFile = str_replace('"', '&quot;', $_MODFile);

		$_MODSQL = str_replace("'", '', $_POST["mod_sql"]);
		$_MODSQL = str_replace('"', '&quot;', $_MODSQL);

		$_MODIcon = "";

		if (isset($_FILES['iconfile']['name']) && $_FILES['iconfile']['name'] != "") {
			addNewLibs('filesupload');
			$_MODIcon = $instances["filesupload"]->uplfilepodbor("iconfile",  "/modimgs");
		}
		elseif (isset($_POST["icondb"]) && $_POST["icondb"] != "") {
			$_MODIcon = str_replace("'", "", $_POST["icondb"]);
		}

		$u1 = mysqli_query($hlnk, "UPDATE ".SQLPRFX."_moduls_spis
		SET ua_modul_nazv='".$_MODNazv."', ua_modul_dir='".$_MODDir."', ua_modul_sqlpref='".$_MODSQL."',
		ua_modul_mfile='".$_MODFile."', ua_modul_icon='".$_MODIcon."'
		WHERE ua_modul_id='".$_Mzid."';") or die("Save Changes :(");

		echo "<H2>".__("Сохранено")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href=\'".$ltab."\'\", 200);\n</SCRIPT>";
	break;

	case "delfil":
		if (!isset($_GET["mzid"]) || !is_numeric($_GET["mzid"])) {die("<script>history.back();</script>");}
		$_Mzid = $_GET["mzid"];

		$r = mysqli_query($hlnk, "SELECT ua_modul_icon
		FROM ".SQLPRFX."_moduls_spis WHERE ua_modul_id='".$_Mzid."';") or die ("Mod get :(");

		$FS = mysqli_fetch_row($r);
		if ($FS[0] != "") {
			@unlink($SPUrl.'/modimgs/'.$FS[0]);
		}
		$r=mysqli_query($hlnk, "UPDATE ".SQLPRFX."_moduls_spis
		SET ua_modul_icon='' WHERE ua_modul_id='".$_Mzid."';") or die("Clear File :(");

		echo "<H2>".__("Удалено")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href=\'".$ltab."\'\", 200);\n</SCRIPT>";
	break;

	case "del": //удалить модуль
		if (!isset($_GET["mzid"]) || !is_numeric($_GET["mzid"])) {die("<script>history.back();</script>");}
		$_Mzid = $_GET["mzid"];

		$r = mysqli_query($hlnk, "SELECT ua_modul_icon, ua_modul_sqlpref
		FROM ".SQLPRFX."_moduls_spis WHERE ua_modul_id='".$_Mzid."';") or die ("Mod get :(");

		$FS = mysqli_fetch_row($r);

		$AllTabs = array();
		$rf = mysqli_query($hlnk, "SHOW TABLES;") or die ("All tabs :(");
		while ($tbs = mysqli_fetch_row($rf)) {			if (substr_count($tbs[0], $FS[1]) > 0) {
				$AllTabs[] = $tbs[0];
			}
		}

		foreach($AllTabs as $TabSp) {
			$r=mysqli_query($hlnk, "DROP TABLE `".$TabSp."`;") or die ("Del ".$TabSp." :(");
		}

		if ($FS[0] != "") {
			@unlink($SPUrl.'/modimgs/'.$FS[0]);
		}

		$r=mysqli_query($hlnk, "DELETE FROM ".SQLPRFX."_moduls_rights WHERE ua_modul_id='".$_Mzid."';") or die ("Del1 :(");
		$r=mysqli_query($hlnk, "DELETE FROM ".SQLPRFX."_moduls_spis WHERE ua_modul_id='".$_Mzid."';") or die ("Del2 :(");
		$r=mysqli_query($hlnk, "OPTIMIZE TABLE ".SQLPRFX."_moduls_rights;") or die ("Opt1 :(");
		$r=mysqli_query($hlnk, "OPTIMIZE TABLE ".SQLPRFX."_moduls_spis;") or die ("Opt2 :(");

		echo "<H2>".__("Удалено")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href=\'".$ltab."\'\", 200);\n</SCRIPT>";
	break;
}
?>