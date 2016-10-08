<?php

if($ObjOldInfo["Object_Rajon"] > 0) {
	$r = mysqli_query($hlnk, "SELECT `RajonText`
	FROM ".$ppt."list_rajons WHERE RajonID='".$ObjOldInfo["Object_Rajon"]."';") or die ("Get rajon OLD :(");
	$Dt = mysqli_fetch_assoc($r);
	$Was = $Dt["RajonText"];
}

if(is_numeric($RefVal) && $RefVal > 0) {
	$r = mysqli_query($hlnk, "SELECT `RajonText`
	FROM ".$ppt."list_rajons WHERE RajonID='".$RefVal."';") or die ("Get rajon OLD :(");
	$Dt = mysqli_fetch_assoc($r);
	$Now = $Dt["RajonText"];
}
else {
	$RefVal = 0;
}

?>