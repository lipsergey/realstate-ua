<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');

$NotReq = "";

$ObjHouse = "";

if(isset($_POST["objstreet"]) && is_numeric($_POST["objstreet"]) && $_POST["objstreet"] > 0) {
	$ObjStreet = $_POST["objstreet"];
}
else {
	$NotReq .= "<li>".__("TRNSL-SELECT-STREET")."</li>\n";
}

if (isset($_POST["objaddr"]) && $_POST["objaddr"] != "") {
	$ObjHouse = mysqli_real_escape_string($hlnk, $_POST["objaddr"]);
}

$ObjRajon = ((isset($_POST["objaddrrajon"]) && is_numeric($_POST["objaddrrajon"])) ? $_POST["objaddrrajon"] : 0);
$ObjType = ((isset($_POST["objtype"]) && is_numeric($_POST["objtype"])) ? $_POST["objtype"] : 0);

if(isset($_POST["objfloor"]) && is_numeric($_POST["objfloor"])) {
	$ObjFloor = $_POST["objfloor"];
}
else {
	$NotReq .= "<li>".__("TRNSL-FLOOR")."</li>\n";
}

if(isset($_POST["objfloorsklv"]) && is_numeric($_POST["objfloorsklv"])) {
	$ObjFloorKlv = $_POST["objfloorsklv"];
}
else {
	$NotReq .= "<li>".__("TRNSL-FLOOR-KLV")."</li>\n";
}

if(isset($_POST["objtotarea"]) && is_numeric($_POST["objtotarea"])) {
	$ObjTotArea = $_POST["objtotarea"];
}
else {
	$NotReq .= "<li>".__("TRNSL-OBJ-INFO-TOT-AREA")."</li>\n";
}

if(isset($_POST["objarea"]) && $_POST["objarea"] != "") {
	$ObjArea = mysqli_real_escape_string($hlnk, $_POST["objarea"]);
}
else {
	$NotReq .= "<li>".__("TRNSL-OBJ-INFO-AREA")."</li>\n";
}

if(isset($_POST["objroomsklv"]) && is_numeric($_POST["objroomsklv"])) {
	$ObjRoomsKlv = $_POST["objroomsklv"];
}
else {
	$NotReq .= "<li>".__("TRNSL-KOLVO-ROOMS")."</li>\n";
}

if(isset($_POST["objownercontacts"]) && $_POST["objownercontacts"] != "") {
	$ObjOwnerContats = mysqli_real_escape_string($hlnk, $_POST["objownercontacts"]);
}
else {
	$NotReq .= "<li>".__("TRNSL-CONTACTS-OWNER")."</li>\n";
}

if(isset($_POST["objprice"]) && is_numeric($_POST["objprice"])) {
	$ObjPrice = $_POST["objprice"];
}
else {
	$NotReq .= "<li>".__("TRNSL-PRICE")."</li>\n";
}

$ObjPriceValut = ((isset($_POST["objpricevalut"]) && is_numeric($_POST["objpricevalut"])) ? $_POST["objpricevalut"] : 1);
$ObjPrePayValut = ((isset($_POST["objprepavalut"]) && is_numeric($_POST["objprepavalut"])) ? $_POST["objprepavalut"] : 1);

if(isset($_POST["objprepay"]) && $_POST["objprepay"] != "") {
	$ObjPrePay = mysqli_real_escape_string($hlnk, $_POST["objprepay"]);
}
else {
	$ObjPrePay = "";
}

$ObjTransType = ((isset($_POST["objtranstype"]) && is_numeric($_POST["objtranstype"])) ? $_POST["objtranstype"] : 1);
$ObjCommerce = ((isset($_POST["objcommerce"]) && is_numeric($_POST["objcommerce"])) ? $_POST["objcommerce"] : 0);

$LnkRieltor = ((isset($_POST["objlinkedrieltor"]) && is_numeric($_POST["objlinkedrieltor"])) ? $_POST["objlinkedrieltor"] : 0);
$FriendRieltor = ((isset($_POST["objfririelt"]) && is_numeric($_POST["objfririelt"])) ? $_POST["objfririelt"] : 0);

$HType = ((isset($_POST["objhousetype"]) && is_numeric($_POST["objhousetype"])) ? $_POST["objhousetype"] : 0);
$ObjWType = ((isset($_POST["objwallmatherial"]) && is_numeric($_POST["objwallmatherial"])) ? $_POST["objwallmatherial"] : 0);
$FlEnter = ((isset($_POST["objflatenter"]) && is_numeric($_POST["objflatenter"])) ? $_POST["objflatenter"] : 0);
$ObjHeat = ((isset($_POST["objheat"]) && is_numeric($_POST["objheat"])) ? $_POST["objheat"] : 0);
$ObjSantech = ((isset($_POST["objsantech"]) && is_numeric($_POST["objsantech"])) ? $_POST["objsantech"] : 0);
$ObjRemont = ((isset($_POST["objremont"]) && is_numeric($_POST["objremont"])) ? $_POST["objremont"] : 0);
$ObjMebel = ((isset($_POST["objmebel"]) && is_numeric($_POST["objmebel"])) ? $_POST["objmebel"] : 0);
$ObjKuhni = ((isset($_POST["objkuhni"]) && is_numeric($_POST["objkuhni"])) ? $_POST["objkuhni"] : 0);
$ObjPerekr = ((isset($_POST["objperekr"]) && is_numeric($_POST["objperekr"])) ? $_POST["objperekr"] : 0);

$TechN = array(
	"TV" => ((isset($_POST["obthtv"]) && is_numeric($_POST["obthtv"])) ? $_POST["obthtv"] : 0),
	"HOLOD" => ((isset($_POST["objthholod"]) && is_numeric($_POST["objthholod"])) ? $_POST["objthholod"] : 0),
	"STIR" => ((isset($_POST["objthstir"]) && is_numeric($_POST["objthstir"])) ? $_POST["objthstir"] : 0),
	"INET" => ((isset($_POST["objthinet"]) && is_numeric($_POST["objthinet"])) ? $_POST["objthinet"] : 0)
);

if(isset($_POST["objanotherinf"]) && $_POST["objanotherinf"] != "") {
	$ObjAnotherInf = mysqli_real_escape_string($hlnk, $_POST["objanotherinf"]);
}
else {
	$ObjAnotherInf = "";
}

if(isset($_POST["objwilllive"]) && $_POST["objwilllive"] != "") {
	$ObjWillLive = mysqli_real_escape_string($hlnk, $_POST["objwilllive"]);
}
else {
	$ObjWillLive = "";
}

if ($NotReq != "") {
	echo "<h2>Error! ".__("TRNSL-EMPTY-REQ-FIELDS").":</h2>
	<ol>".$NotReq."</ol>\n";
	die();
}

$r = mysqli_query($hlnk, "INSERT INTO ".$ppt."relastate_live SET `Object_Date`=CURRENT_DATE(),
	`Object_Type`='".$ObjType."', `Object_Rajon`='".$ObjRajon."', `Object_Street`='".$ObjStreet."',
	`Object_Addr`='".$ObjHouse."', `Object_Price`='".$ObjPrice."', `Price_Valut`='".$ObjPriceValut."',
	`Predopalt`='".$ObjPrePay."', `Predopalt_Valut`='".$ObjPrePayValut."', `AreaTot`='".$ObjTotArea."',
	`AreaExt`='".$ObjArea."', `Floor`='".$ObjFloor."', `NumbOfFloors`='".$ObjFloorKlv."',
	`NumbOfRooms`='".$ObjRoomsKlv."', `WallMatherial`='".$ObjWType."', `EnterToFlat`='".$FlEnter."',
	`TypeOfHouse`='".$HType."', `TypeOfHeat`='".$ObjHeat."', `Santech`='".$ObjSantech."',
	`Overlaps`='".$ObjPerekr."', `Furniture`='".$ObjMebel."', `Remont`='".$ObjRemont."',
	`Kitchen`='".$ObjKuhni."', `OtherInf`='".$ObjAnotherInf."', `OwnerContacts`='".$ObjOwnerContats."',
	`TechInObj`='".mysqli_real_escape_string($hlnk, json_encode($TechN))."',
	`WhoWillLive`='".$ObjWillLive."', `LnkRieltor`='".$LnkRieltor."', `FriendRieltor`='".$FriendRieltor."',
	`Operator`='".ADMUZERID."', `IsCommerce`='".$ObjCommerce."', `ContractType`='".$ObjTransType."',
	`Object_Created`='".time()."';") or die ("Save new object in DB :( "); //.mysqli_error($hlnk)
$NewID = mysqli_insert_id($hlnk);

echo "<H2>".__("TRNSL-SAVED")."!</H2><SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."&modact=editobj&objid=".$NewID."'\", 1000);\n</SCRIPT>";

?>