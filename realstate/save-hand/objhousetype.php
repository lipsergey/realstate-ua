<?php

if(is_numeric($RefVal) && isset($HouseType[$RefVal])) {
	$Now = __($HouseType[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["TypeOfHouse"] > 0) {
	$Was = __($HouseType[$ObjOldInfo["TypeOfHouse"]]);
}
?>