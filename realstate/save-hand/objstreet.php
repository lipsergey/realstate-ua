<?php
if (!is_numeric($RefVal) || $RefVal == 0) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-ERROR-VALUE")."</b>";
	die();
}

$SV = array();
$r = mysqli_query($hlnk, "SELECT StreetID, StreetText
FROM ".$ppt."list_streets WHERE StreetID IN (".$ObjOldInfo["Object_Street"].", ".$RefVal.");") or die (mysqli_error($hlnk)."<HR> List streets :(");
while($tST = mysqli_fetch_assoc($r)) {
	$SV[$tST["StreetID"]] = $tST["StreetText"];
}

if (!isset($SV[$RefVal])) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-ERROR-VALUE")."</b>";
	die();
}

if ($RefVal != $ObjOldInfo["Object_Street"]) {
	$Was = $SV[$ObjOldInfo["Object_Street"]];
	$Now = $SV[$RefVal];
}

?>