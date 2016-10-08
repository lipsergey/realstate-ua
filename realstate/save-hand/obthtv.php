<?php
if ($RefVal == "true") {
	$RefVal = 1;
}
else {
	$RefVal = 0;
}

$Now = __($UserViewFL[$RefVal]);

$OldDT = json_decode($ObjOldInfo["TechInObj"], true);
$Was = __($UserViewFL[$OldDT["TV"]]);

$OldDT["TV"] = $RefVal;
$RefVal = mysqli_real_escape_string($hlnk, json_encode($OldDT));
?>