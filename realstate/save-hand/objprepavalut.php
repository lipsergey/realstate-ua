<?php
if(is_numeric($RefVal) && isset($Valuts[$RefVal])) {
	$Now = __($Valuts[$RefVal]);
}
else {
	$RefVal = 0;
}

$Was = __($Valuts[$ObjOldInfo["Price_Valut"]]);

?>