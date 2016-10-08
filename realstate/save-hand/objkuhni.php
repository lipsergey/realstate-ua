<?php
if(is_numeric($RefVal) && isset($Kuhny[$RefVal])) {
	$Now = __($Kuhny[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["Kitchen"] > 0) {
	$Was = __($Kuhny[$ObjOldInfo["Kitchen"]]);
}

?>