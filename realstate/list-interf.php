<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');

echo $SearchHTML;

//$SearchSQL = $SearchURL = "";
/*

define("ADMGROUP", $User["uadm_group"]);

*/

if (ADMGROUP == 1 || ADMGROUP == 2) {
	echo "<h3><a href=\"".$ModURL."&modact=addnewobj\">".__("TRNSL-LIST-ADD-OBJ")."</a></h3>";
}

echo "<h3>".__("TRNSL-LIST-ACTIVE-OBJ")."</h3>
<style>
.carousel-control {
  padding-top:20%;
  width:5%;
}
</style>
<table id=tabcont class=\"table table-bordered\" style=\"margin-left:10px;\">
<thead>
<tr style=\"background: silver; text-align:center;\">
	<th>Дата</th>
	<th>К</th>
	<th>".__("TRNSL-OBJ-TYPE")."</th>
	<th>Адреса</th>
	<th>".__("TRNSL-FLOOR")."</th>
	<th>".__("TRNSL-PRICE")."</th>
	<th>".__("TRNSL-PREPAY")."</th>
	<th>".__("TRNSL-OBJ-ANOTHER")."</th>

	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
";

/*
	<th>Ремонт</th>
	<th>".__("TRNSL-MEBEL")."</th>
	<th>".__("TRNSL-HEAT")."</th>
*/
addNewLibs('pagelistener');
$instances["pagelistener"]->RezPerPage = OBJECTSPERPAGE;

$r = mysqli_query($hlnk, "SELECT COUNT(*)
FROM ".$ppt."relastate_live WHERE 1 ".$SearchSQL.";") or die ("Count obj :(");
$ObjCnt = mysqli_fetch_row($r);
if ($ObjCnt[0] == 0) {
	echo "<tr><td colspan=9 align=center>".__("TRNSL-NO-OBJECT")."</td></tr>\n";
}
else {
	$PgSPis = $instances["pagelistener"]->MakePageBlock($ObjCnt[0], "", $ModURL.$SearchURL); //Get Page Spis
	$ImgJS = array();

	$r = mysqli_query($hlnk, "SELECT Object_ID
	FROM ".$ppt."relastate_live WHERE 1 ".$SearchSQL."
	".$instances["pagelistener"]->MakeSQLLimitCode().";") or die ("Get objects IDs :(");
	if (mysqli_num_rows($r) == 0) {
		echo "<tr><td colspan=9 align=center>".__("TRNSL-NO-OBJECT")."</td></tr>\n";
	}
	else {
		$ObjIDs = array();
		while($tObj = mysqli_fetch_assoc($r)) {
			$ObjIDs[] = $tObj["Object_ID"];
		}

		$ImgList = array();
		$r = mysqli_query($hlnk, "SELECT Object_ID, Image_ID, ImageName
		FROM ".$ppt."relastate_gallery WHERE Object_ID IN (".implode(",", $ObjIDs).");") or die ("Get images :(");
		while ($tIm = mysqli_fetch_assoc($r)) {
			$ImgList[$tIm["Object_ID"]][] = $tIm["ImageName"];
		}

		$r = mysqli_query($hlnk, "SELECT Object_ID, Object_Type, DATE_FORMAT(Object_Date, '%d.%m.%y') OBJDT,
		Object_Created, Object_Rajon, Object_Street, Object_Addr, Object_Price, Price_Valut,
		Predopalt, Predopalt_Valut, AreaTot, AreaExt, Floor, NumbOfFloors, NumbOfRooms,
		WallMatherial, EnterToFlat, TypeOfHouse, TypeOfHeat, Santech, Overlaps, Furniture, Remont,
		Kitchen, OtherInf, OwnerContacts, TechInObj,
		WhoWillLive, LnkRieltor, FriendRieltor, Operator, IsCommerce, ContractType
		FROM ".$ppt."relastate_live WHERE Object_ID IN (".implode(",", $ObjIDs).") ".$SearchSQL."
		ORDER BY Object_Date DESC;") or die ("Get list of objects :(");
		if ($PgSPis != "") {
			echo "<tr><td colspan=9>".$PgSPis."</td></tr>\n";
		}
		while ($SpisObjects = mysqli_fetch_assoc($r)) {
			$CurSTR = ((isset($SpisObjects["Object_Rajon"]) && isset($AllRajons[$SpisObjects["Object_Rajon"]])) ? $AllRajons[$SpisObjects["Object_Rajon"]].", " : "").((isset($SpisObjects["Object_Street"]) && isset($AllStreets[$SpisObjects["Object_Street"]])) ? $AllStreets[$SpisObjects["Object_Street"]].", " : "") . $SpisObjects["Object_Addr"];
			echo "<tr>
				<td>".$SpisObjects["OBJDT"]."</td>
				<td>".$SpisObjects["NumbOfRooms"]."</td>
				<td>".__($ObjTypes[$SpisObjects["Object_Type"]])."</td>
				<td>".$CurSTR."</td>
				<td>".$SpisObjects["Floor"]."/".$SpisObjects["NumbOfFloors"]."</td>
				<td>".$SpisObjects["Object_Price"]." ".$ValutChars[$SpisObjects["Price_Valut"]]."</td>
				<td>".($SpisObjects["Predopalt"] != "" ? (is_numeric($SpisObjects["Predopalt"]) ? $SpisObjects["Predopalt"] . " ". $ValutChars[$SpisObjects["Predopalt_Valut"]] : $SpisObjects["Predopalt"]) : "&nbsp;")."</td>
				<td>".((isset($SpisObjects["OtherInf"]) && $SpisObjects["OtherInf"] != "") ? "".$SpisObjects["OtherInf"]."<br />" : "&nbsp;")."</td>
				<td>
				".((ADMGROUP == 1 || ADMGROUP == 2) ? '<input type="button" value="Edit" class="btn btn-warning" style="margin-left:10px;" onclick="document.location.href=\''.$ModURL.'&modact=editobj&objid='.$SpisObjects["Object_ID"].'\'"><br />' : "")."
				<input type=\"button\" value=\"View\" class=\"btn btn-info\" style=\"margin-left:10px;\" onclick=\"DopInfo(".$SpisObjects["Object_ID"].");return false;\">
				</td>
			</tr>
			<tr><td colspan=10>
				<table id=\"tabcont".$SpisObjects["Object_ID"]."\" class=\"table table-bordered addoninfo\" style=\"display: none;\">
				<tr>
					<td>
						<b>".__("TRNSL-OBJ-INFO-TOT-AREA")."</b>: ".$SpisObjects["AreaTot"]."<br />
						<b>".__("TRNSL-OBJ-INFO-AREA")."</b>: ".$SpisObjects["AreaExt"]."<br />
						".((isset($SpisObjects["TypeOfHouse"]) && is_numeric($SpisObjects["TypeOfHouse"]) && $SpisObjects["TypeOfHouse"] > 0) ? "<b>".__("TRNSL-FL-TYPE")."</b>: ".__($HouseType[$SpisObjects["TypeOfHouse"]])."<br />" : "")."
						";
						//Gallery
						if (isset($ImgList[$SpisObjects["Object_ID"]]) && count($ImgList[$SpisObjects["Object_ID"]]) > 0) {
							$ImgJS[] = $SpisObjects["Object_ID"];
							echo '<div id="obimgs-'.$SpisObjects["Object_ID"].'" class="carousel slide objcarusel" style="width: 220px;padding-left:10px;">
							<div class="carousel-inner">';
								foreach ($ImgList[$SpisObjects["Object_ID"]] as $tNM => $tImgPath) {
									echo '<div class="item'.($tNM == 0 ? " active" : "").'" style="width: 200px;"><a href="'.$SRVUrl.'imgs/objects/big/'.$tImgPath.'" class="gal'.$SpisObjects["Object_ID"].'" title="'.str_replace('"', "&quot;", $CurSTR).'"><img src="'.$SRVUrl.'imgs/objects/small/'.$tImgPath.'" alt="" class="img-thumbnail"></a></div>';
								}
							echo '</div>
							 <a class="carousel-control left" href="#obimgs-'.$SpisObjects["Object_ID"].'" data-slide="prev">&lsaquo;</a>
							<a class="carousel-control right" href="#obimgs-'.$SpisObjects["Object_ID"].'" data-slide="next">&rsaquo;</a>
							</div>';
						}
						
						echo "
						
					</td>
					<td>
						".((isset($SpisObjects["WallMatherial"]) && is_numeric($SpisObjects["WallMatherial"]) && $SpisObjects["WallMatherial"] > 0) ? "<b>".__("TRNSL-MATERAL")."</b>: ".__($WallMaterial[$SpisObjects["WallMatherial"]])."<br />" : "")."
						".((isset($SpisObjects["Remont"]) && is_numeric($SpisObjects["Remont"]) && $SpisObjects["Remont"] > 0) ? "<b>".__("TRNSL-REMONT")."</b>: ".__($Remont[$SpisObjects["Remont"]])."<br />" : "")."
						".((isset($SpisObjects["EnterToFlat"]) && is_numeric($SpisObjects["EnterToFlat"]) && $SpisObjects["EnterToFlat"] > 0) ? "<b>".__("TRNSL-FL-ENT")."</b>: ".__($EnterIntoFlat[$SpisObjects["EnterToFlat"]])."<br />" : "")."
						".((isset($SpisObjects["TypeOfHeat"]) && is_numeric($SpisObjects["TypeOfHeat"]) && $SpisObjects["TypeOfHeat"] > 0) ? "<b>".__("TRNSL-HEAT")."</b>: ".__($HeatType[$SpisObjects["TypeOfHeat"]])."<br />" : "")."
						".((isset($SpisObjects["Santech"]) && is_numeric($SpisObjects["Santech"]) && $SpisObjects["Santech"] > 0) ? "<b>".__("TRNSL-SNT")."</b>: ".__($Santehtype[$SpisObjects["Santech"]])."<br />" : "")."

						".((isset($SpisObjects["Furniture"]) && is_numeric($SpisObjects["Furniture"]) && $SpisObjects["Furniture"] > 0) ? "<b>".__("TRNSL-MEBEL")."</b>: ".__($Mebel[$SpisObjects["Furniture"]])."<br />" : "")."
						".((isset($SpisObjects["Kitchen"]) && is_numeric($SpisObjects["Kitchen"]) && $SpisObjects["Kitchen"] > 0) ? "<b>".__("TRNSL-KUHNI")."</b>: ".__($Kuhny[$SpisObjects["Kitchen"]])."<br />" : "")."
						".((isset($SpisObjects["Overlaps"]) && is_numeric($SpisObjects["Overlaps"]) && $SpisObjects["Overlaps"] > 0) ? "<b>".__("TRNSL-PEREKR")."</b>: ".__($Perekrit[$SpisObjects["Overlaps"]])."<br />" : "");
						
						if (isset($SpisObjects["TechInObj"]) && $SpisObjects["TechInObj"] != "") {
							$Tech = json_decode($SpisObjects["TechInObj"], true);
							
							echo ((isset($Tech["TV"]) && is_numeric($Tech["TV"]) && $Tech["TV"] == 1) ? "<b>".__("TRNSL-TECH-TV")."</b>: ".__("TRNSL-YES")."<br />" : "").
							((isset($Tech["HOLOD"]) && is_numeric($Tech["HOLOD"]) && $Tech["HOLOD"] > 0) ? "<b>".__("TRNSL-TECH-HOLOD")."</b>: ".__("TRNSL-YES")."<br />" : "").
							((isset($Tech["STIR"]) && is_numeric($Tech["STIR"]) && $Tech["STIR"] > 0) ? "<b>".__("TRNSL-TECH-STIRAL")."</b>: ".__("TRNSL-YES")."<br />" : "").
							((isset($Tech["INET"]) && is_numeric($Tech["INET"]) && $Tech["INET"] > 0) ? "<b>".__("TRNSL-TECH-INET")."</b>: ".__("TRNSL-YES")."<br />" : "");
						}
						
						echo "
					</td>
					<td>
						".((isset($SpisObjects["WhoWillLive"]) && $SpisObjects["WhoWillLive"] != "") ? "<b>".__("TRNSL-OBJ-WILL-LIVE")."</b>: ".$SpisObjects["WhoWillLive"]."<br />" : "")."
					</td>
					<td style=\"width:48px;\"><a href=\"realstate/ajax-view-contacts.php?&objid=".$SpisObjects["Object_ID"]."\" class='ajax'><img src=\"img/owner-contact.png\" border=0 /></a></td>
				</tr>";
				if (ADMGROUP == 1 || ADMGROUP == 2) {
					echo "<tr><td colspan=4>".__("TRNSL-ADDED-BY").": ".(isset($AllUsers[$SpisObjects["Operator"]]) ? $AllUsers[$SpisObjects["Operator"]]." (".date("d.m.y H:i:s", $SpisObjects["Object_Created"]).")" : "n/a")."</td></tr>\n";
				}

				echo "</table>
			</td></tr>\n";
		}
		if ($PgSPis != "") {
			echo "<tr><td colspan=10>".$PgSPis."</td></tr>\n";
		}
	}
}

//$(".group1").colorbox({rel:'group1'});

echo '</tbody></table>
<link href="css/colorbox.css" rel="stylesheet">
<script src="js/jquery.colorbox.js"></script>
<script>
$(document).ready(function(){
//	$("#tabcont").tablesorter({widgets: ["zebra"]}).tablesorterPager({container: $("#pager"), size: 100});
	$(".objcarusel").carousel("pause");
	$(".ajax").colorbox();
});
';
foreach($ImgJS as $tObjID) {
	echo "\t$('.gal".$tObjID."').colorbox({rel:'gal".$tObjID."'});\r\n";
}
echo '
function DopInfo(ObjId) {
	if($("#tabcont"+ObjId).is(":visible") == true) {
		$("#tabcont"+ObjId).hide();
	}
	else {
		$("#tabcont"+ObjId).show();
	}
}

</script>

';

?>