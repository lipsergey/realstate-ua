<?php
if(is_numeric($RefVal) && isset($Santehtype[$RefVal])) {
	$Now = __($Santehtype[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["Santech"] > 0) {
	$Was = __($Santehtype[$ObjOldInfo["Santech"]]);
}


?>