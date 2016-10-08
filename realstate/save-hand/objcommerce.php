<?php
if (!is_numeric($RefVal) || !isset($UserAccSel[$RefVal])) {
	$RefVal = 0;
}

$Was = __($UserAccSel[$ObjOldInfo["IsCommerce"]]);
$Now = __($UserAccSel[$RefVal]);

?>