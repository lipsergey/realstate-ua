<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');


//Фильтры поиска
$tAddr =  $tHType = $tHeat = $tKuhni = $tRemont = 0;
$tRooms = $tMinPR = $tMaxPR = $tFloor = ""; 
$Areas = array();

if (isset($_GET["srch-addr"]) && is_numeric($_GET["srch-addr"])) {
	$tAddr = $_GET["srch-addr"];
	$SearchSQL .= " AND `Object_Street`='".$tAddr."'";
	$SearchURL .= "&srch-addr=".$tAddr;
}

if (isset($_GET["srch-htype"]) && is_numeric($_GET["srch-htype"])) {
	$tHType = $_GET["srch-htype"];
	$SearchSQL .= " AND `TypeOfHouse`='".$tHType."'";
	$SearchURL .= "&srch-htype=".$tHType;
}

if (isset($_GET["srch-heat"]) && is_numeric($_GET["srch-heat"])) {
	$tHeat = $_GET["srch-heat"];
	$SearchSQL .= " AND `TypeOfHeat`='".$tHeat."'";
	$SearchURL .= "&srch-heat=".$tHeat;
}


if (isset($_GET["srch-area"]) && is_array($_GET["srch-area"])) {
	
	foreach($_GET["srch-area"] as $tArea) {
		if (!is_numeric($tArea)) {continue;}
		$Areas[$tArea] = 1;
		$SearchURL .= "&srch-area[]=".$tArea;
	}
	if (count($Areas) > 0) {
		$SearchSQL .= " AND `Object_Rajon` IN (".implode(",",array_keys($Areas)).")";
	}
}

if (isset($_GET["srch-kuhni"]) && is_numeric($_GET["srch-kuhni"])) {
	$tKuhni = $_GET["srch-kuhni"];
	$SearchSQL .= " AND `Kitchen`='".$tKuhni."'";
	$SearchURL .= "&srch-kuhni=".$tKuhni;
}

if (isset($_GET["srch-remont"]) && is_numeric($_GET["srch-remont"])) {
	$tRemont = $_GET["srch-remont"];
	$SearchSQL .= " AND `Remont`='".$tRemont."'";
	$SearchURL .= "&srch-remont=".$tRemont;
}

if (isset($_GET["srch-rooms"]) && $_GET["srch-rooms"] != "") {
	$tRooms = $_GET["srch-rooms"];
	$SearchSQL .= " AND `NumbOfRooms`='".$tRooms."'";
	$SearchURL .= "&srch-rooms=".$tRooms;
}

if (isset($_GET["srch-min-price"]) && is_numeric($_GET["srch-min-price"])) {
	$tMinPR = $_GET["srch-min-price"];
	if (defined("ADMFILTERMIN") && ADMFILTERMIN > 0 && $tMinPR < ADMFILTERMIN) {$tMinPR = ADMFILTERMIN;}
	$SearchURL .= "&srch-min-price=".$tMinPR;

	if (isset($_GET["srch-max-price"]) && is_numeric($_GET["srch-max-price"])) {
		$tMaxPR = $_GET["srch-max-price"];
		if (defined("ADMFILTERMAX") && ADMFILTERMAX > 0 && $tMaxPR > ADMFILTERMAX) {$tMaxPR = ADMFILTERMAX;}
		$SearchSQL .= " AND (`Object_Price` BETWEEN ".$tMinPR." AND ".$tMaxPR.")";
		$SearchURL .= "&srch-max-price=".$tMaxPR;
	}
	else {
		$SearchSQL .= " AND (`Object_Price` BETWEEN ".$tMinPR."".((defined("ADMFILTERMAX") && ADMFILTERMAX > 0) ? " AND ".ADMFILTERMAX."" : "").")";
	}
}
elseif(isset($_GET["srch-max-price"]) && is_numeric($_GET["srch-max-price"])) {
	$tMaxPR = $_GET["srch-max-price"];
	if (defined("ADMFILTERMAX") && ADMFILTERMAX > 0 && $tMaxPR > ADMFILTERMAX) {$tMaxPR = ADMFILTERMAX;}
	$SearchSQL .= " AND (".((defined("ADMFILTERMIN") && ADMFILTERMIN > 0) ? "`Object_Price` BETWEEN ".ADMFILTERMIN." AND " : "")."".$tMaxPR.")";
	$SearchURL .= "&srch-max-price=".$tMaxPR;
}
else {
	if (defined("ADMFILTERMIN") && ADMFILTERMIN > 0) {
		if (defined("ADMFILTERMAX") && ADMFILTERMAX > 0) {
			$SearchSQL .= " AND (`Object_Price` BETWEEN ".ADMFILTERMIN." AND ".ADMFILTERMAX.")";
		}
		else {
			$SearchSQL .= " AND `Object_Price` > ".ADMFILTERMIN;
		}
	}
	elseif (defined("ADMFILTERMAX") && ADMFILTERMAX > 0) {
		$SearchSQL .= " AND `Object_Price` < ".ADMFILTERMAX;
	}
}

if (isset($_GET["srch-floor"]) && is_numeric($_GET["srch-floor"])) {
	$tFloor = $_GET["srch-floor"];
	$SearchSQL .= " AND `Floor`='".$tFloor."'";
	$SearchURL .= "&srch-floor=".$tFloor;
}

if (!defined("ADMSEESALE")) {define("ADMSEESALE", 0);}
if (!defined("ADMSEERENT")) {define("ADMSEERENT", 0);}
if (!defined("ADMSEECOMMSALE")) {define("ADMSEECOMMSALE", 0);}
if (!defined("ADMSEECOMMRENT")) {define("ADMSEECOMMRENT", 0);}

if ((ADMSEESALE == 0 && ADMSEERENT == 0) || (ADMSEECOMMSALE == 0 && ADMSEECOMMRENT == 0)) {
	echo "<h2>".__("TRNSL-NO-ACCESS")."</h2>";
	die();
}

if (!defined("ADMGROUP") || ADMGROUP >= 2) {

	if (ADMSEESALE == 1 && ADMSEERENT == 0) {
		$SearchSQL .= " AND `ContractType`='1'";
		if (ADMSEECOMMSALE == 1) {
			$SearchSQL .= " AND `IsCommerce`='1'";
		}
		elseif(ADMSEECOMMRENT == 0) {
			$SearchSQL .= " AND `IsCommerce`='0'";
		}
	}
	elseif (ADMSEESALE == 0 && ADMSEERENT == 1) {
		$SearchSQL .=" AND `ContractType`='0'";
		if (ADMSEECOMMRENT == 1) {
			$SearchSQL .= " AND `IsCommerce`='1'";
		}
		elseif(ADMSEECOMMSALE == 0) {
			$SearchSQL .= " AND `IsCommerce`='0'";
		}
	}



// || ADMSEESALE == 0 || ADMSEERENT == 0 || ADMSEECOMMSALE == 0

/*
//-------------------------------------------------------------------------------------
	if (ADMSEESALE == 1 || ADMSEECOMMSALE == 1) { //See SALE
		if (ADMSEERENT == 0 && ADMSEECOMMRENT == 0) {
			$SearchSQL .= " AND `ContractType`='1'";
		}
		if (ADMSEECOMMSALE == 1) {
			$SearchSQL .= " AND `IsCommerce`='1'";
		}
		else {
			$SearchSQL .= " AND `IsCommerce`='0'";
		}
	}
	else {
		if (ADMSEERENT == 1 || ADMSEECOMMRENT == 1) {
			$SearchSQL .= " AND `ContractType`='2'";
		}
		if(ADMSEECOMMRENT == 1) {
			$SearchSQL .= " AND `IsCommerce`='1'";
		}
		else {
			$SearchSQL .= " AND `IsCommerce`='0'";
		}
	}
	*/
}

//Инетрфейс для поиска объектов
//<a href="#" onclick="ShowTab();"></a>
$SearchHTML = '<h4>'.__("TRNSL-SEARCH-OBJECT").'</h4>
<script src="js/jquery/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/jquery/chosen.css">
<form name="search" method=get action="index.php">
<input type=hidden name="modid" value="'.$ModID.'">
<table id="searchtable" class="table table-bordered" style="width: auto; margin-left: 20px;">
<tr align="center" bgcolor="silver" style="font-size: 12px;">
	<td><b>'.__("TRNSL-ADDRESS").'</b></td>
	<td><b>'.__("TRNSL-KOLVO-ROOMS").'</b></td>
	<td><b>'.__("TRNSL-MIN-PRICE").'</b></td>
	<td><b>'.__("TRNSL-MAX-PRICE").'</b></td>
	<td><b>'.__("TRNSL-FLOOR").'</b></td>
</tr>
<tr>
	<td><select data-placeholder="'.__("TRNSL-SELECT-STREET").'..." name="srch-addr" class="chosen-select" style="width: 200px;">
	<option value=""></option>';
	foreach($AllStreets as $tSTID => $tSTNM) {
		$SearchHTML .= "<option value=\"".$tSTID."\"";
		if ($tAddr == $tSTID) {$SearchHTML .= " selected";}
		$SearchHTML .= ">".$tSTNM."</option>\n";
	}

	$SearchHTML .= '</select>
	</td>
	<td><input type="text" style="width:90px;" class="form-control" name="srch-rooms" value="'.$tRooms.'"  /></td>
	<td><input type="text" style="width:60px;" class="form-control" name="srch-min-price" value="'.$tMinPR.'" /></td>
	<td><input type="text" style="width:60px;" class="form-control" name="srch-max-price" value="'.$tMaxPR.'" /></td>
	<td><input type="text" style="width:50px;" class="form-control" name="srch-floor" value="'.$tFloor.'" /></td>
</tr>
<tr align="center" bgcolor="silver" style="font-size: 12px;">
	<td><b>'.__("TRNSL-RAJON").'</b></td>
	<td><b>'.__("TRNSL-FL-TYPE").'</b></td>
	<td><b>'.__("TRNSL-HEAT").'</b></td>
	<td><b>'.__("TRNSL-KUHNI").'</b></td>
	<td><b>'.__("TRNSL-REMONT").'</b></td>
</tr>
<tr>
	<td><select data-placeholder="'.__("TRNSL-SELECT-AREA").'..." name="srch-area[]" class="chosen-select" multiple="multiple" style="width:200px;">
            <option value=""></option>';
	foreach ($AllRajons as $tRajID => $tRajNM) {
		$SearchHTML .= "<option value=\"".$tRajID."\"";
		if (isset($Areas[$tRajID])) {$SearchHTML .= " selected";}
		$SearchHTML .= ">".$tRajNM."</option>\n";
	}
	$SearchHTML .= '
	</select></td>
	<td><select style="width:70px;" name="srch-htype" class="form-control">
		<option value="n">'.__("TRNSL-NO-MATTER").'</option>';
	foreach($HouseType as $hID => $hText) {
		$SearchHTML .= "<option value=\"".$hID."\"";
		if ($tHType == $hID) {$SearchHTML .= " selected";}
		$SearchHTML .= ">".__($hText)."</option>\n";
	}
	$SearchHTML .= '
	</select></td>
	<td><select style="width:70px;" class="form-control" name="srch-heat">
		<option value="n">'.__("TRNSL-NO-MATTER").'</option>';
	foreach($HeatType as $hID => $hText) {
		$SearchHTML .= "<option value=\"".$hID."\"";
		if ($tHeat == $hID) {$SearchHTML .= " selected";}
		$SearchHTML .= ">".__($hText)."</option>\n";
	}

	$SearchHTML .= '
	</select></td>
	<td><select style="width:70px;" class="form-control" name="srch-kuhni">
		<option value="n">'.__("TRNSL-NO-MATTER").'</option>';
	foreach($Kuhny as $hID => $hText) {
		$SearchHTML .= "<option value=\"".$hID."\"";
		if ($tKuhni == $hID) {$SearchHTML .= " selected";}
		$SearchHTML .= ">".__($hText)."</option>\n";
	}
	$SearchHTML .= '
	</select></td>
	<td><select style="width:70px;" class="form-control" name="srch-remont">
		<option value="n">'.__("TRNSL-NO-MATTER").'</option>';
	foreach($Kuhny as $hID => $hText) {
		$SearchHTML .= "<option value=\"".$hID."\"";
		if ($tRemont == $hID) {$SearchHTML .= " selected";}
		$SearchHTML .= ">".__($hText)."</option>\n";
	}
	$SearchHTML .= '
	</select></td>
</tr>
<tr>
	<td align=center colspan=9>
		<input type=submit value="'.__("TRNSL-SEARCH-OBJECT").'" class="btn btn-lg btn-primary">
		<input type=button onclick="document.location.href=\''.$ModURL.'\'" value="'.__("TRNSL-CLEAR").'" class="btn btn-lg btn-warning">
	
	</td>
</tr>
</table></form>

<script>
function ShowTab() {
	if($("#searchtable").is(":visible") == true) {
		$("#searchtable").hide();
	}
	else {
		$("#searchtable").show();
	}
}
$(".chosen-select").chosen({no_results_text:"Oops, nothing found!"});
//$("#searchtable").hide();

</script>

';

?>