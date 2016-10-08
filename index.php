<?php
header("Content-Type: text/html; charset=utf-8");
define("SHP_VALID", "OK");
include("core.php");
$ModSel = "usermodpoz";

$rfnm = "index.php";

if (defined("ADMUZERID")) {
	$Moduls = array(); //список всех модулей

	$rf = mysqli_query($hlnk, "SELECT MODRIGHTS.uadm_flag
	FROM ".$ppt."ta_usrsadm_moduls_spis MODSPIS
	INNER JOIN ".$ppt."ta_usrsadm_moduls_rights MODRIGHTS ON MODSPIS.ua_modul_id=MODRIGHTS.ua_modul_id
	WHERE MODRIGHTS.uadm_id='".ADMUZERID."' AND MODSPIS.ua_modul_dir='usermodpoz';") or die ("All Rights :(");
	$_mmorder = mysqli_fetch_row($rf);
	if ($_mmorder[0] == "1") {$_MsOr = "MODRIGHTS.ua_modul_poz";}
	else {$_MsOr = "BINARY(MODSPIS.ua_modul_nazv)";}

	$rf = mysqli_query($hlnk, "SELECT MODSPIS.ua_modul_id, MODSPIS.ua_modul_nazv,
	MODSPIS.ua_modul_dir, MODSPIS.ua_modul_sqlpref, MODSPIS.ua_modul_mfile,
	MODSPIS.ua_modul_icon
	FROM ".$ppt."ta_usrsadm_moduls_spis MODSPIS
	INNER JOIN ".$ppt."ta_usrsadm_moduls_rights MODRIGHTS ON MODSPIS.ua_modul_id=MODRIGHTS.ua_modul_id
	WHERE MODRIGHTS.uadm_id='".ADMUZERID."' AND MODRIGHTS.uadm_flag='1'
	ORDER BY ".$_MsOr.";") or die ("All Rights :(");

	while ($uzr = mysqli_fetch_assoc($rf)) {
		$Moduls[$uzr["ua_modul_id"]] = array(
			"modnazv" => $uzr["ua_modul_nazv"],
			"moddir" => $uzr["ua_modul_dir"],
			"modpref" => $uzr["ua_modul_sqlpref"],
			"modfile" => $uzr["ua_modul_mfile"],
			"modicon" => $uzr["ua_modul_icon"]
		);
	}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<TITLE><?php echo $citename; ?>: admin-panel</TITLE>
<link rel="stylesheet" type="text/css" href="style-jscore.css"/>
<STYLE>
	FORM { margin: 0; }
	th {text-align: center !important;}
	.btn-lg {
		font-size: 16px !important;
		padding: 5px !important;
	}
	.form-control {height: 25px !important; padding: 0px !important;}
	.tabs-box {
		padding: 15px;
		margin-bottom: 20px;
		border: 1px solid transparent;
		margin-top: 10px;
		background-color: #fff;
		border-color: #ddd;
		border-width: 1px;
		border-radius: 4px;
		box-shadow: none;
		box-sizing: border-box;
	}
</STYLE>

<script src="js/jquery1.11.js"></script>
<?php /* <script src="js/jquery/jquery-3.1.0.min.js" type="text/javascript"></script> */ ?>
<script type="text/javascript" src="js/jquery/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="js/jquery/ui/jquery.ui.widget.min.js"></script>
<script src='js/jquery/jquery.tools.min.js' type='text/javascript'></script>
<link href="css/bootstrap.min.css" rel="stylesheet">
<script src="js/bootstrap.min.js"></script>

</HEAD>
<BODY VLINK="BLUE">
<div style="width: 90%; margin-left: 20px;">
<p><?php echo $userLBL;?></p>
<?php
	if ((!isset($_POST["modid"]) || !is_numeric($_POST["modid"])) && (!isset($_GET["modid"]) || !is_numeric($_GET["modid"])) && count($Moduls) > 1) {

		echo "<H2>".__("TRNSL-SELECT-MODUL")."</H2>
		<TABLE CELLSPACING=3 CELLPADDING=3>";
		$i = 1;
		foreach ($Moduls as $Key => $ModData) {
			if ($i == "1") {echo "<TR>\n";}
			if ($ModData["modicon"] != "") {
				echo "<TD ALIGN=CENTER WIDTH=150><TABLE>
				<TR><TD ALIGN=CENTER><A HREF=\"".$rfnm."?modid=".$Key."\"><IMG SRC=\"modimgs/".$ModData["modicon"]."\" BORDER=0 ALT=\"".$ModData["modnazv"]."\"></A></TD></TR>
				<TR><TD ALIGN=CENTER><A HREF=\"".$rfnm."?modid=".$Key."\">".$ModData["modnazv"]."</A></TD></TR></TABLE></TD>";
			}
			else {
				echo "<TD ALIGN=CENTER><A HREF=\"".$rfnm."?modid=".$Key."\">".$ModData["modnazv"]."</A></TD>";
			}

			if ($i == ADMINICONSINLINE) {echo "</TR>\n"; $i = 1;}
			else {$i += 1;}
		}
		if ($i <= ADMINICONSINLINE || $i > 1) {echo "</TR>\n";}
		echo "</TABLE>";
	}
	else { //Разбор настроек модуля
		if (!isset($_POST["modid"]) && !isset($_GET["modid"])) {
			$ModID = array_shift(array_keys($Moduls));
		}
		else {
			if (isset($_POST["modid"]) && is_numeric($_POST["modid"])) {
				$ModID = $_POST["modid"];
			}
			else {
				$ModID = $_GET["modid"];
			}
		}
		if (!isset($Moduls[$ModID])) {die("<h3>Нет прав на доступ к этому модулю</h3>");}
		define("SQLPRFX", $ppt.$Moduls[$ModID]["modpref"]); //Префикс раздела
		define("MODDIR", $Moduls[$ModID]["moddir"]); //Директория раздела
		define("MODFILE", $Moduls[$ModID]["modfile"]); //Файл раздела

		function getSQLByCode($_Code) {
			global $Moduls, $ppt;
			foreach($Moduls as $_MDKey => $_MDData) {
				if ($_MDData["moddir"] == $_Code) {return $ppt.$_MDData["modpref"];}
			}
		}

		if (count($Moduls) > 1) {
			echo "<A HREF=\"".$rfnm."\">".__("TRNSL-MAINMENU")."</A> | ";
		}

		if (!isset($_GET["modact"])) {
			if (count($Moduls) > 1) {
				echo $Moduls[$ModID]["modnazv"];
			}
			$ModAct = "";
		}
		else {
			$ModAct = $_GET["modact"];
			echo "<A HREF=\"".$rfnm."?modid=".$ModID."\">".$Moduls[$ModID]["modnazv"]."</A> | ";
		}
		$ModURL = $rfnm."?modid=".$ModID; //URL модуля
		include(MODDIR . "/" .MODFILE);
	}
}
?>
</div>
</BODY>
</HTML>