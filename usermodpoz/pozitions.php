<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');
/* Управление порядком отображения */

switch ($ModAct) {
	case "":

		$rf = mysqli_query($hlnk, "SELECT MODSPIS.ua_modul_id, MODSPIS.ua_modul_nazv,
		MODSPIS.ua_modul_icon, MODRIGHTS.ua_modul_poz
		FROM ".SQLPRFX."_moduls_spis MODSPIS
		INNER JOIN ".SQLPRFX."_moduls_rights MODRIGHTS ON MODSPIS.ua_modul_id=MODRIGHTS.ua_modul_id
		WHERE MODRIGHTS.uadm_id='".ADMUZERID."' AND MODRIGHTS.uadm_flag='1'
		ORDER BY MODRIGHTS.ua_modul_poz;") or die ("All Rights :(");

		$ModPoz = "";

		while ($uzr = mysqli_fetch_array($rf)) {
			$Moduls[$uzr["ua_modul_id"]] = array(
				"modnazv" => $uzr["ua_modul_nazv"],
				"modpoz" => $uzr["ua_modul_poz"],
				"modicon" => $uzr["ua_modul_icon"]
			);
			$ModPoz .= "<OPTION VALUE=\"".$uzr["ua_modul_poz"]."\" %POZ".$uzr["ua_modul_poz"]."%>".$uzr["ua_modul_poz"]."</OPTION>\n";
		}

		echo "<p><FORM NAME=\"mdspis\">
		<TABLE CELLSPACING=0 CELLPADDING=6 border=1>";
		$i = 1;
		$v = 1;
		foreach ($Moduls as $Key => $ModData) {
			if ($i == "1") {echo "<TR>\n";}
			echo "<TD ALIGN=CENTER WIDTH=150><TABLE>\n<TR><TD ALIGN=CENTER>".$ModData["modpoz"]."</TD></TR>\n";
	        if ($ModData["modicon"] != "") {
	        	echo "<TR><TD ALIGN=CENTER><IMG SRC=\"modimgs/".$ModData["modicon"]."\" BORDER=0></TD></TR>";
	        }
	        echo "<TR><TD ALIGN=CENTER>".$ModData["modnazv"]."</TD></TR>
			<TR><TD ALIGN=CENTER><A HREF=\"#\" ONCLICK=\"ViewDiv($v)\"><IMG SRC=\"modimgs/move.gif\" ALT=\"Move\" BORDER=0 align=top></A></TD></TR>
	        </TABLE>\n
	        <DIV id=\"movediv$v\" style=\"z-index: 90; position: absolute; display: none;\"><TABLE STYLE=\"-moz-background-clip:border;-moz-background-inline-policy:continuous;-moz-background-origin:padding;background:transparent url(modimgs/bg_tab.gif) no-repeat scroll left top;color:#000000;height:94px;width:190px;marging-left:10px;\">
	        <TR><TD>".__("Выберите блок в который следует переместить").":<BR><SELECT NAME=\"mdpz$v\" ONCHANGE=\"document.location.href='$ModURL&modact=changepoz&pozmodid=$Key&pozid=".$ModData["modpoz"]."&newpozid='+document.mdspis.mdpz$v.value\">
			".str_replace("%POZ".$ModData["modpoz"]."%", " SELECTED", $ModPoz)."
            </SELECT></TD></TR></TABLE>
            </DIV></TD>\n";
			$v += 1;
			if ($i == ADMINICONSINLINE) {echo "</TR>\n"; $i = 1;}
			else {$i += 1;}
		}
		if ($i <= ADMINICONSINLINE || $i > 1) {echo "</TR>\n";}
		echo "</TABLE>
		<SCRIPT>
		function ViewDiv(id) {
			var t = $v;
			for (t = 1; t < $v; t++) {
				if (t != id && document.getElementById('movediv'+t).style.display == '') {
					document.getElementById('movediv'+t).style.display = 'none';
				}
			}
			document.getElementById('movediv'+id).style.display=(document.getElementById('movediv'+id).style.display=='none')? '' : 'none';
		}
		</SCRIPT></FORM>";
	break;


	case "changepoz": //смена позиции
		if (!isset($_GET["modid"]) || !is_numeric($_GET["modid"])
		|| !isset($_GET["pozid"]) || !is_numeric($_GET["pozid"])
		|| !isset($_GET["newpozid"]) || !is_numeric($_GET["newpozid"])) {die("<script>history.back();</script>");}
		$_MDDID = $_GET["pozmodid"];
		$NewPoz = $_GET["newpozid"];
		$CurPoz = $_GET["pozid"];

		$c1 = mysqli_query($hlnk, "SELECT ua_modul_id FROM ".SQLPRFX."_moduls_rights
		WHERE ua_modul_poz='$NewPoz' AND uadm_id='".ADMUZERID."';") or die("NPoz ID :(");
		$dest = mysqli_fetch_row($c1);

		$u1 = mysqli_query($hlnk, "UPDATE ".SQLPRFX."_moduls_rights
		SET ua_modul_poz='$NewPoz' WHERE ua_modul_id='$_MDDID' AND uadm_id='".ADMUZERID."';") or die("Move our :(");
		$u2 = mysqli_query($hlnk, "UPDATE ".SQLPRFX."_moduls_rights
		SET ua_modul_poz='$CurPoz' WHERE ua_modul_id='$dest[0]' AND uadm_id='".ADMUZERID."';") or die("Move dest :(");

		echo "<H2>".__("Сохранено")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href=\'$ModURL\'\", 500);\n</SCRIPT>";
	break;
}
?>