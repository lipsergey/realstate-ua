<?php

if (is_numeric($RefVal) && $RefVal > 0) {
	$r = mysqli_query($hlnk, "SELECT `uadm_fio`
	FROM ".$ppt."ta_usrsadm_main WHERE `uadm_id`='".$RefVal."';") or die ("Get name new :(");
	$tRLT = mysqli_fetch_assoc($r);
	$Now = $tRLT["uadm_fio"];
}
else {
	$RefVal = 0;
}

if($ObjOldInfo["LnkRieltor"] > 0) {
	$r = mysqli_query($hlnk, "SELECT `uadm_fio`
	FROM ".$ppt."ta_usrsadm_main WHERE `uadm_id`='".$ObjOldInfo["LnkRieltor"]."';") or die ("Get name old :(");
	$tRLT = mysqli_fetch_assoc($r);
	$Was = $tRLT["uadm_fio"];
}

?>