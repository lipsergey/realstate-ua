<?php
if(is_numeric($RefVal) && isset($EnterIntoFlat[$RefVal])) {
	$Now = __($EnterIntoFlat[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["EnterToFlat"] > 0) {
	$Was = __($EnterIntoFlat[$ObjOldInfo["EnterToFlat"]]);
}
?>