<?php
if(is_numeric($RefVal) && isset($Remont[$RefVal])) {
	$Now = __($Remont[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["Remont"] > 0) {
	$Was = __($Remont[$ObjOldInfo["Remont"]]);
}

?>