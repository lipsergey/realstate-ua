<?php
include("../core.php");
if (ADMGROUP != 1 && ADMGROUP != 2) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-NO-ACCESS")."</b>";
	die();
}

if (!isset($_GET["objid"]) || !is_numeric($_GET["objid"])) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-NO-ID")."</b>";
	die();
}
$ObjID = $_GET["objid"];

if (!isset($_GET["img"]) || !is_numeric($_GET["img"])) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-NO-ID")." IMG</b>";
	die();
}
$ImgID = $_GET["img"];

$r = mysqli_query($hlnk, "SELECT `ImageName`
FROM ".$ppt."relastate_gallery WHERE `Object_ID`='".$ObjID."' AND `Image_ID`='".$ImgID."';") or die ("Get image :(");
if (mysqli_num_rows($r) > 0) {
	$ImgDT = mysqli_fetch_assoc($r);
	if ($ImgDT["ImageName"] != "" && file_exists($SPUrl.MAINIMGCAT."objects/big/".$ImgDT["ImageName"])) {
		unlink($SPUrl.MAINIMGCAT."objects/big/".$ImgDT["ImageName"]);
		unlink($SPUrl.MAINIMGCAT."objects/small/".$ImgDT["ImageName"]);
	}
	$r = mysqli_query($hlnk, "DELETE FROM ".$ppt."relastate_gallery WHERE `Object_ID`='".$ObjID."' AND `Image_ID`='".$ImgID."';") or die ("Del image :(");

	$r = mysqli_query($hlnk, "OPTIMIZE TABLE ".$ppt."relastate_gallery;") or die ("Optimaze table of img wrong :( ");
}
?>