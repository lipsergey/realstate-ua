<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');

$AllStreets = array();
$r = mysqli_query($hlnk, "SELECT StreetID, StreetText
FROM ".$ppt."list_streets ORDER BY BINARY (StreetText);") or die (mysqli_error($hlnk)."<HR> List streets :(");
while($tST = mysqli_fetch_assoc($r)) {
	$AllStreets[$tST["StreetID"]] = $tST["StreetText"];
}

$AllRieltors = array();
$r = mysqli_query($hlnk, "SELECT `uadm_id`, `uadm_fio`, `contacts`
FROM ".$ppt."ta_usrsadm_main WHERE uadm_group=4 ORDER BY BINARY(`uadm_fio`);") or die ("Get spis of rieltors :(");
while($tRielt = mysqli_fetch_assoc($r)) {
	$AllRieltors[$tRielt["uadm_id"]] = $tRielt;
}

$AllUsers = array();
$r = mysqli_query($hlnk, "SELECT `uadm_id`, `uadm_fio`
FROM ".$ppt."ta_usrsadm_main WHERE 1 ORDER BY BINARY(`uadm_fio`);") or die ("Get spis of rieltors :(");
while($tRielt = mysqli_fetch_assoc($r)) {
	$AllUsers[$tRielt["uadm_id"]] = $tRielt["uadm_fio"];
}

$AllRajons = array();
$r = mysqli_query($hlnk, "SELECT `RajonID`, `RajonText`
FROM ".$ppt."list_rajons WHERE 1 ORDER BY BINARY(RajonText);") or die ("Get spis of rajons :(");
while($tRns = mysqli_fetch_assoc($r)) {
	$AllRajons[$tRns["RajonID"]] = $tRns["RajonText"];
}

$SearchSQL = $SearchURL = "";


include_once("search-interf.php");

switch ($ModAct) {
	case "":
		include_once("list-interf.php");
	break;
	
	case "addnewobj":
		echo __("TRNSL-LIST-ADD-OBJ");
		if (ADMGROUP != 1 && ADMGROUP != 2) {
			echo "<h2>".__("TRNSL-NO-ACCESS")."</h2>";
			die();
		}
		include_once("add-new-interf.php");
	break;

	case "savenewobj":
		echo __("TRNSL-LIST-ADD-OBJ");
		if (ADMGROUP != 1 && ADMGROUP != 2) {
			echo "<h2>".__("TRNSL-NO-ACCESS")."</h2>";
			die();
		}
		include_once("save-new-object.php");
	break;

	case "editobj":
		echo __("TRNSL-EDIT-OBJECT");
		if (ADMGROUP != 1 && ADMGROUP != 2) {
			echo "<h2>".__("TRNSL-NO-ACCESS")."</h2>";
			die();
		}
		if (!isset($_GET["objid"]) || !is_numeric($_GET["objid"])) {
			echo "<h2>".__("TRNSL-NO-ID")."</h2>";
			die();
		}
		$ObjID = $_GET["objid"];
		include_once("edit-interf.php");
	break;
	
}



?>