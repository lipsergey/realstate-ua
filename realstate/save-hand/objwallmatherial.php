<?php
if(is_numeric($RefVal) && isset($WallMaterial[$RefVal])) {
	$Now = __($WallMaterial[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["WallMatherial"] > 0) {
	$Was = __($WallMaterial[$ObjOldInfo["WallMatherial"]]);
}

?>