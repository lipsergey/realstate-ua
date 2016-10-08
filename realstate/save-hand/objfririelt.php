<?php
if ($RefVal == "true") {
	$RefVal = 1;
}
else {
	$RefVal = 0;
}
$Now = __($UserViewFL[$RefVal]);
$Was = __($UserViewFL[$ObjOldInfo["FriendRieltor"]]);

?>