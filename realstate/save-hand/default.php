<?php
if ($SQFieldNM["SQL"] != "") {
	$Was = $ObjOldInfo[$SQFieldNM["SQL"]];
}

$RefVal = mysqli_real_escape_string($hlnk, $RefVal);
if ($RefVal != "") {
	$Now = $RefVal;
}

?>