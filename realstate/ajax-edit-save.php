<?php
include("../core.php");
if (ADMGROUP != 1 && ADMGROUP != 2) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-NO-ACCESS")."</b>";
	die();
}
if (!isset($_GET["objid"]) || !is_numeric($_GET["objid"])) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-NO-ID")."</b>";
	die();
}
$ObjID = $_GET["objid"];

$Fields = array(
	"objstreet" => array("SQL" => "Object_Street", "Lable" => "TRNSL-SELECT-STREET"),
	"objaddrrajon" => array("SQL" => "Object_Rajon", "Lable" => "TRNSL-RAJON"),
	"objtype" => array("SQL" => "Object_Type", "Lable" => "TRNSL-OBJ-TYPE"),
	"objpricevalut" => array("SQL" => "Price_Valut", "Lable" => "TRNSL-PRICE-VALUT"),
	"objprepavalut" => array("SQL" => "Predopalt_Valut", "Lable" => "TRNSL-PREPAY-VALUT"),
	"objtranstype" => array("SQL" => "ContractType", "Lable" => "TRNSL-TRANS-TYPE"),
	"objcommerce" => array("SQL" => "IsCommerce", "Lable" => "TRNSL-OBJ-COMMERCE"),
	"objlinkedrieltor" => array("SQL" => "LnkRieltor", "Lable" => "TRNSL-LINKED-RIELTOR"),
	"objhousetype" => array("SQL" => "TypeOfHouse", "Lable" => "TRNSL-FL-TYPE"),
	"objwallmatherial" => array("SQL" => "WallMatherial", "Lable" => "TRNSL-MATERAL"),
	"objflatenter" => array("SQL" => "EnterToFlat", "Lable" => "TRNSL-FL-ENT"),
	"objheat" => array("SQL" => "TypeOfHeat", "Lable" => "TRNSL-HEAT"),
	"objsantech" => array("SQL" => "Santech", "Lable" => "TRNSL-SNT"),
	"objremont" => array("SQL" => "Remont", "Lable" => "TRNSL-REMONT"),
	"objmebel" => array("SQL" => "Furniture", "Lable" => "TRNSL-MEBEL"),
	"objkuhni" => array("SQL" => "Kitchen", "Lable" => "TRNSL-KUHNI"),
	"objperekr" => array("SQL" => "Overlaps", "Lable" => "TRNSL-PEREKR"),
	"objaddr" => array("SQL" => "Object_Addr", "Lable" => "TRNSL-ADDRESS"),
	"objfloor" => array("SQL" => "Floor", "Lable" => "TRNSL-FLOOR"),
	"objfloorsklv" => array("SQL" => "NumbOfFloors", "Lable" => "TRNSL-FLOOR-KLV"),
	"objtotarea" => array("SQL" => "AreaTot", "Lable" => "TRNSL-OBJ-INFO-TOT-AREA"),
	"objarea" => array("SQL" => "AreaExt", "Lable" => "TRNSL-OBJ-INFO-AREA"),
	"objroomsklv" => array("SQL" => "NumbOfRooms", "Lable" => "TRNSL-KOLVO-ROOMS"),
	"objownercontacts" => array("SQL" => "OwnerContacts", "Lable" => "TRNSL-CONTACTS-OWNER"),
	"objprice" => array("SQL" => "Object_Price", "Lable" => "TRNSL-PRICE"),
	"objprepay" => array("SQL" => "Predopalt", "Lable" => "TRNSL-PREPAY"),
	"objanotherinf" => array("SQL" => "OtherInf", "Lable" => "TRNSL-OBJ-ANOTHER"),
	"objwilllive" => array("SQL" => "WhoWillLive", "Lable" => "TRNSL-OBJ-WILL-LIVE"),
	"objfririelt" => array("SQL" => "FriendRieltor", "Lable" => "TRNSL-FRIEND-RIELTOR"),
	"obthtv" => array("SQL" => "TechInObj", "Lable" => "TRNSL-TECH-TV"),
	"objthholod" => array("SQL" => "TechInObj", "Lable" => "TRNSL-TECH-HOLOD"),
	"objthstir" => array("SQL" => "TechInObj", "Lable" => "TRNSL-TECH-STIRAL"),
	"objthinet" => array("SQL" => "TechInObj", "Lable" => "TRNSL-TECH-INET"),
);


if (!isset($_GET["fld"]) || $_GET["fld"] == "" || !isset($Fields[$_GET["fld"]])) {
	header('HTTP/1.0 403 Forbidden');
	echo "<b>".__("TRNSL-ERROR-TECH")." - ".$_GET["fld"]."</b>";
	die();
}
$Fld = str_replace(array('..', '/'), array('',''), $_GET["fld"]);
$SQFieldNM = $Fields[$_GET["fld"]];

if (!isset($_GET["val"])) {
	header('HTTP/1.0 403 Forbidden');
	die();
}
$RefVal = str_replace(array("'", '"'), array('', '&quot;'),  $_GET["val"]);


$r = mysqli_query($hlnk, "SELECT Object_Type, Object_Rajon, Object_Street, Object_Addr, Object_Price, Price_Valut,
Predopalt, Predopalt_Valut, AreaTot, AreaExt, Floor, NumbOfFloors, NumbOfRooms,
WallMatherial, EnterToFlat, TypeOfHouse, TypeOfHeat, Santech, Overlaps, Furniture, Remont,
Kitchen, OtherInf, OwnerContacts, TechInObj,
WhoWillLive, LnkRieltor, FriendRieltor, Operator, IsCommerce, ContractType
FROM ".$ppt."relastate_live WHERE Object_ID='".$ObjID."';") or die ("Get object info :(");
$ObjOldInfo = mysqli_fetch_assoc($r);

$Was = $Now = "null";

if (file_exists($SPUrl."realstate/save-hand/".$Fld.".php")) {
	include_once($SPUrl."realstate/save-hand/".$Fld.".php");
}
else {
	include_once($SPUrl."realstate/save-hand/default.php");
}

if ($Was != $Now) {//Save to Log
	$r = mysqli_query($hlnk, "INSERT INTO ".$ppt."realstate_changes_log SET ChangeID='',
	ChangeDateTime='".time()."', Object_ID='".$ObjID."', EditAutor='".ADMUZERID."',
	ChangeField='".$SQFieldNM["Lable"]."', ChangeWas='".$Was."', ChangeNew='".$Now."';") or die ("Save history :(");
}

$r = mysqli_query($hlnk, "UPDATE ".$ppt."relastate_live SET `".$SQFieldNM["SQL"]."`='".$RefVal."'
 WHERE `Object_ID`='".$ObjID."';") or die ("Update object wrong :( ".mysqli_error($hlnk));

$r = mysqli_query($hlnk, "OPTIMIZE TABLE ".$ppt."relastate_live;") or die ("Optimaze table of object wrong :( ");

?>