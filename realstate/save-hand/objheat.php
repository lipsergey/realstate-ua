<?php
if(is_numeric($RefVal) && isset($HeatType[$RefVal])) {
	$Now = __($HeatType[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["TypeOfHeat"] > 0) {
	$Was = __($HeatType[$ObjOldInfo["TypeOfHeat"]]);
}
?>