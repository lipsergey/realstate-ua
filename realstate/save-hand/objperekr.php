<?php
if(is_numeric($RefVal) && isset($Perekrit[$RefVal])) {
	$Now = __($Perekrit[$RefVal]);
}
else {
	$RefVal = 0;
}

if ($ObjOldInfo["Overlaps"] > 0) {
	$Was = __($Perekrit[$ObjOldInfo["Overlaps"]]);
}

?>