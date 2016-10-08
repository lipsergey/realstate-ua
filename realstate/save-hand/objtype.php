<?php

$Was = __($ObjTypes[$ObjOldInfo["Object_Type"]]);
if(is_numeric($RefVal) && isset($ObjTypes[$RefVal])) {
	$Now = __($ObjTypes[$RefVal]);
}
else {
	$RefVal = 0;
}

?>