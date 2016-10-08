<?php
if(is_numeric($RefVal) && isset($Mebel[$RefVal])) {
	$Now = __($Mebel[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["Furniture"] > 0) {
	$Was = __($Mebel[$ObjOldInfo["Furniture"]]);
}

?>