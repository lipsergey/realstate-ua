<?php
include("../core.php");
if (!isset($_GET["objid"]) || !is_numeric($_GET["objid"])) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-NO-ID")."</b>";
	die();
}
$ObjID = $_GET["objid"];


$r = mysqli_query($hlnk, "SELECT RELIVE.LnkRieltor, RELIVE.FriendRieltor, RELIVE.OwnerContacts,
USRMN.uadm_fio, USRMN.contacts

FROM ".$ppt."relastate_live RELIVE
LEFT JOIN ".$ppt."ta_usrsadm_main USRMN ON RELIVE.LnkRieltor=USRMN.uadm_id
WHERE RELIVE.Object_ID='".$ObjID."';") or die ("Get object info :(");
$ObjInfo = mysqli_fetch_assoc($r);



if ((ADMGROUP == 1 || ADMGROUP == 2) && ($ObjInfo["LnkRieltor"] == 0 || $ObjInfo["FriendRieltor"] == 0)) {
	echo __("TRNSL-CONTACTS-OWNER")." ".$ObjInfo["OwnerContacts"]."<hr>";
}
if ($ObjInfo["LnkRieltor"] > 0 && $ObjInfo["contacts"] != "") {
	echo __("TRNSL-LINKED-RIELTOR").": ".$ObjInfo["uadm_fio"].", ".$ObjInfo["contacts"];
}
elseif($ObjInfo["FriendRieltor"] == 1) {
	echo __("TRNSL-FRIEND-RIELTOR");
}
else {
	echo __("TRNSL-NO-DATA");
}

?>