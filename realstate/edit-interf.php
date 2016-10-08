<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');

$r = mysqli_query($hlnk, "SELECT Object_Type, DATE_FORMAT(Object_Date, '%d.%m.%y') OBJDT,
Object_Created, Object_Rajon, Object_Street, Object_Addr, Object_Price, Price_Valut,
Predopalt, Predopalt_Valut, AreaTot, AreaExt, Floor, NumbOfFloors, NumbOfRooms,
WallMatherial, EnterToFlat, TypeOfHouse, TypeOfHeat, Santech, Overlaps, Furniture, Remont,
Kitchen, OtherInf, OwnerContacts, TechInObj,
WhoWillLive, LnkRieltor, FriendRieltor, Operator, IsCommerce, ContractType
FROM ".$ppt."relastate_live WHERE Object_ID='".$ObjID."';") or die ("Get object info :(");
$ObjInfo = mysqli_fetch_assoc($r);

$Tech = json_decode($ObjInfo["TechInObj"], true);

/*
		<li><input type="submit" value="'.__("TRNSL-SAVE-CONTINUE").'" class="btn btn-success" style="margin-left:10px;"></li>
		<li><input type="button" value="'.__("TRNSL-CANCEL").'" class="btn btn-danger" style="margin-left:10px;" onclick="document.location.href=\''.$ModURL.'\';"></li>

*/

echo '
<script src="js/jquery/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/bootstrapValidator.css"/>
<script type="text/javascript" src="js/bootstrapValidator.min.js"></script>
<link rel="stylesheet" href="js/jquery/chosen.css">
<p>
<form name="mdata" id="mdata" method="post" action="'.$ModURL.'&modact=savechange&objid='.$ObjID.'" enctype="multipart/form-data" class="form-horizontal">
<style>
td {
	border-top: none !important;
}
.col-lg-4 {
	width: auto !important;
}
@font-face {
  font-family: "FontAwesome";
  src: url("fonts/fontawesome-webfont.eot?v=4.1.0");
  src: url("fonts/fontawesome-webfont.eot?#iefix&v=4.1.0") format("embedded-opentype"), url("fonts/fontawesome-webfont.woff?v=4.1.0") format("woff"), url("fonts/fontawesome-webfont.ttf?v=4.1.0") format("truetype"), url("fonts/fontawesome-webfont.svg?v=4.1.0#fontawesomeregular") format("svg");
  font-weight: normal;
  font-style: normal;
}

.fa {
  display: inline-block;
  font-family: FontAwesome;
  font-style: normal;
  font-weight: normal;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.fa-check:before {
  content: "\f00c";
}
.fa-times:before {
  content: "\f00d";
}
</style>
<script>
function LoadObjHistory() {
	$.ajax({
		url: "realstate/ajax-get-history.php?objid='.$ObjID.'",
		cache: false,
		success: function(html){
			$("#history-content").html(html);
		},
	});
}

function LoadImgTab() {
	$.ajax({
		url: "realstate/ajax-image-list.php?objid='.$ObjID.'",
		cache: false,
		success: function(html){
			$("#images-content").html(html);
		},
	});
}
</script>
<div id="ajaxtext"></div>
<div class="alert alert-warning" role="alert">'.__("TRNSL-SAVE-BY-AJAX").'</div>
<div class="alert alert-success" role="alert" style="display: none;" id="oksave">'.__("TRNSL-SAVED").'</div>
<div class="alert alert-danger" role="alert" style="display: none;" id="wrsave">'.__("TRNSL-SAVE-ERROR").'<div id="errtext"></div></div>

<div class="tabs-box">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tabs-main">'.__("TRNSL-OBJ-INFO-MAIN").' <i class="fa"></i></a></li>
		<li><a data-toggle="tab" href="#tabs-dopinf">'.__("TRNSL-OBJ-INFO-DOP").' <i class="fa"></i></a></li>
		<li><a data-toggle="tab" href="#tabs-imgoperat" onclick="LoadImgTab();">'.__("TRNSL-IMAGES").' <i class="fa"></i></a></li>
		<li><a data-toggle="tab" href="#tabs-histinf">'.__("TRNSL-HISTORY").' <i class="fa"></i></a></li>
	</ul>

	<div class="tab-content">

		<div id="tabs-main" class="tab-pane fade in active">
			<h3>'.__("TRNSL-OBJ-INFO-MAIN").'</h3>

			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-STREET").'*</td>
				<td><div class="col-lg-4"><select data-placeholder="'.__("TRNSL-SELECT-STREET").'..." name="objstreet" class="form-control chosen-select" style="width: 200px;" onchange="SaveSelect(this.value, this.name);">
				<option value=""></option>';
				foreach($AllStreets as $tSTID => $tSTNM) {
					echo "<option value=\"".$tSTID."\"".($ObjInfo["Object_Street"] == $tSTID ? " selected" : "").">".$tSTNM."</option>\n";
				}

				echo '</select></div></td>
				<td>&nbsp;</td>
				<td>'.__("TRNSL-ADDRESS").'</td>
				<td><input type="text" style="width: 200px;" value="'.$ObjInfo["Object_Addr"].'" name="objaddr" class="form-control objtextdata"></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-RAJON").'</td>
				<td><select name="objaddrrajon" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"></option>';

				foreach($AllRajons as $tRjID => $tRjTxt) {
					echo "<option value=\"".$tRjID."\"".($ObjInfo["Object_Rajon"] == $tRjID ? " selected" : "").">".$tRjTxt."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-OBJ-TYPE").'</td>
				<td><select name="objtype" class="form-control" onchange="SaveSelect(this.value, this.name);">';
				foreach($ObjTypes as $ObjTpID => $ObjTpTxt) {
					echo "<option value=\"".$ObjTpID."\"".($ObjInfo["Object_Type"] == $ObjTpID ? " selected" : "").">".__($ObjTpTxt)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-FLOOR").'*</td>
				<td><div class="col-lg-4"><input type="number" style="width: 60px;" value="'.$ObjInfo["Floor"].'" name="objfloor" class="form-control objtextdata" data-bv-icon-for="objfloor"></div></td>
				<td>'.__("TRNSL-FLOOR-KLV").'*</td>
				<td><div class="col-lg-4"><input type="number" style="width: 60px;" value="'.$ObjInfo["NumbOfFloors"].'" name="objfloorsklv" class="form-control objtextdata" data-bv-icon-for="objfloorsklv"></div></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-OBJ-INFO-TOT-AREA").'*</td>
				<td><div class="col-lg-4"><input type="text" style="width: 60px;" value="'.$ObjInfo["AreaTot"].'" name="objtotarea" class="form-control objtextdata"></div></td>
				<td>'.__("TRNSL-OBJ-INFO-AREA").'*</td>
				<td><div class="col-lg-4"><input type="text" style="width: 130px;" value="'.$ObjInfo["AreaExt"].'" name="objarea" class="form-control objtextdata"></div></td>
				<td>'.__("TRNSL-KOLVO-ROOMS").'*</td>
				<td><div class="col-lg-4"><input type="number" style="width: 60px;" value="'.$ObjInfo["NumbOfRooms"].'" name="objroomsklv" class="form-control objtextdata"></div></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-CONTACTS-OWNER").'*</td>
				<td><div class="col-lg-4"><textarea style="width: 300px !important; height: 80px !important;" name="objownercontacts" class="form-control objtextdata" rows="10">'.$ObjInfo["OwnerContacts"].'</textarea></div></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-PRICE").'*</td>
				<td><div class="col-lg-4"><input type="text" style="width: 60px;" value="'.$ObjInfo["Object_Price"].'" name="objprice" class="form-control objtextdata"></div></td>
				<td>'.__("TRNSL-PRICE-VALUT").'</td>
				<td><select name="objpricevalut" class="form-control" onchange="SaveSelect(this.value, this.name);">';

				foreach($Valuts as $ValID => $ValT) {
					echo "<option value=\"".$ValID."\"".($ObjInfo["Price_Valut"] == $ValID ? " selected" : "").">".__($ValT)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-PREPAY").'</td>
				<td><input type="text" style="width: 70px;" name="objprepay" value="'.$ObjInfo["Predopalt"].'" class="form-control objtextdata"></td>
				<td>'.__("TRNSL-PREPAY-VALUT").'</td>
				<td><select name="objprepavalut" class="form-control" onchange="SaveSelect(this.value, this.name);">';
				foreach($Valuts as $ValID => $ValT) {
					echo "<option value=\"".$ValID."\"".($ObjInfo["Predopalt_Valut"] == $ValID ? " selected" : "").">".__($ValT)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-TRANS-TYPE").'</td>
				<td><select style="width: 100px;" name="objtranstype" class="form-control"  onchange="SaveSelect(this.value, this.name);">
				<option value="1"'.($ObjInfo["ContractType"] == 1 ? " selected" : "").'>'.__("TRNSL-SEE-SALE").'</option>
				<option value="2"'.($ObjInfo["ContractType"] == 2 ? " selected" : "").'>'.__("TRNSL-SEE-RENT").'</option>
				</select></td>
				<td>'.__("TRNSL-OBJ-COMMERCE").':</td>
				<td><select style="width: 100px;" name="objcommerce" class="form-control" onchange="SaveSelect(this.value, this.name);">';

				foreach($UserAccSel as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["IsCommerce"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<p>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-LINKED-RIELTOR").':</td>
				<td><select style="width: 100px;" name="objlinkedrieltor" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n">'.__("TRNSL-LINKED-NO-RIELTOR").'</option>';
				foreach($AllRieltors as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["LnkRieltor"] == $tID ? " selected" : "").">".$tNM["uadm_fio"]."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-FRIEND-RIELTOR").'</td>
				<td><input type="checkbox" name="objfririelt" value="1"'.($ObjInfo["FriendRieltor"] == 1 ? " checked" : "").' onchange="SaveSelect(this.checked, this.name);"></td>
			</tr>
			</table>
		</div>

		<div id="tabs-dopinf" class="tab-pane fade">
			<h3>'.__("TRNSL-OBJ-INFO-DOP").'</h3>

			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-FL-TYPE").':</td>
				<td><select style="width: 100px;" name="objhousetype" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($HouseType as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["TypeOfHouse"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-MATERAL").':</td>
				<td><select style="width: 100px;" name="objwallmatherial" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($WallMaterial as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["WallMatherial"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}

				echo '</select></td>
				<td>'.__("TRNSL-FL-ENT").':</td>
				<td><select style="width: 100px;" name="objflatenter" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($EnterIntoFlat as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["EnterToFlat"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-HEAT").':</td>
				<td><select style="width: 100px;" name="objheat" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($HeatType as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["TypeOfHeat"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-SNT").':</td>
				<td><select style="width: 100px;" name="objsantech" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($Santehtype as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["Santech"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-REMONT").':</td>
				<td><select style="width: 100px;" name="objremont" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($Remont as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["Remont"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-MEBEL").':</td>
				<td><select style="width: 100px;" name="objmebel" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($Mebel as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["Furniture"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-KUHNI").':</td>
				<td><select style="width: 100px;" name="objkuhni" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($Kuhny as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["Kitchen"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-PEREKR").':</td>
				<td><select style="width: 100px;" name="objperekr" class="form-control" onchange="SaveSelect(this.value, this.name);">
				<option value="n"> -------------- </option>';
				foreach($Perekrit as $tID => $tNM) {
					echo "<option value=\"".$tID."\"".($ObjInfo["Overlaps"] == $tID ? " selected" : "").">".__($tNM)."</option>\n";
				}

				echo '</select></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-TECH-TV").'</td>
				<td><input type="checkbox" name="obthtv" value="1"'.($Tech["TV"] == 1 ? " checked" : "").' onchange="SaveSelect(this.checked, this.name);"></td>
				<td>'.__("TRNSL-TECH-HOLOD").'</td>
				<td><input type="checkbox" name="objthholod" value="1"'.($Tech["HOLOD"] == 1 ? " checked" : "").' onchange="SaveSelect(this.checked, this.name);"></td>
				<td>'.__("TRNSL-TECH-STIRAL").'</td>
				<td><input type="checkbox" name="objthstir" value="1"'.($Tech["STIR"] == 1 ? " checked" : "").' onchange="SaveSelect(this.checked, this.name);"></td>
				<td>'.__("TRNSL-TECH-INET").'</td>
				<td><input type="checkbox" name="objthinet" value="1"'.($Tech["INET"] == 1 ? " checked" : "").' onchange="SaveSelect(this.checked, this.name);"></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-OBJ-ANOTHER").'</td>
				<td><textarea style="width: 500px; height: 80px;" class="objtextdata" name="objanotherinf">'.$ObjInfo["OtherInf"].'</textarea></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-OBJ-WILL-LIVE").'</td>
				<td><textarea style="width: 500px; height: 80px;" class="objtextdata"  name="objwilllive">'.$ObjInfo["WhoWillLive"].'</textarea></td>
			</tr>
			</table>
		</div>

		<div id="tabs-imgoperat" class="tab-pane fade">
			<h3>'.__("TRNSL-IMAGES").'</h3>
			<div id="images-content"></div>
		</div>

		<div id="tabs-histinf" class="tab-pane fade">
			<h3>'.__("TRNSL-HISTORY").' <a href="#" onclick="LoadObjHistory();return false;"><img src="modimgs/refresh.png" border=0></a></h3>
			<div id="history-content"></div>
		</div>
	</div>
</div>
</form>
<script>
$(document).ready(function() {
	$(".chosen-select").chosen({no_results_text:"Oops, nothing found!"});

	$("#mdata").bootstrapValidator({
		excluded: [":disabled"],
		framework: "bootstrap",
		feedbackIcons: {
			valid: "glyphicon glyphicon-ok",
			invalid: "glyphicon glyphicon-remove",
			validating: "glyphicon glyphicon-refresh"
		},
		fields: {
			objstreet: {
				group: ".col-lg-4",
				validators: {
					notEmpty: {
						message: "'.__("TRNSL-FORM-NEED-VALUE").'"
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: "'.__("TRNSL-FORM-NEED-VALUE").'"
					}

				}
			},
			objfloor: {
				group: ".col-lg-4",
				validators: {
					notEmpty: {
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					}
				}
			},
			objfloorsklv: {
				group: ".col-lg-4",
				validators: {
					notEmpty: {
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					}
				}
			},
			objprice: {
				group: ".col-lg-4",
				validators: {
					notEmpty: {
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					}
				}
			},
			objtotarea: {
				group: ".col-lg-4",
				validators: {
					notEmpty: {
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					},
					regexp: {
						regexp: /^[0-9\.,]+$/,
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					}
				}
			},
			objarea: {
				group: ".col-lg-4",
				validators: {
					notEmpty: {
						message: "'.__("TRNSL-FORM-NEED-VALUE").'"
					},
				}
			},
			objownercontacts: {
				group: ".col-lg-4",
				validators: {
					notEmpty: {
						message: "'.__("TRNSL-FORM-NEED-VALUE").'"
					},
				}
			},
			objroomsklv: {
				group: ".col-lg-4",
				validators: {
					notEmpty: {
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					},
					regexp: {
						regexp: /^[0-9]+$/,
						message: "'.__("TRNSL-FORM-NEED-DIGIT").'"
					}
				}
			},
			
			
		}
	}).on("status.field.bv", function(e, data) {
		var $form     = $(e.target),
			validator = data.bv,
			$tabPane  = data.element.parents(".tab-pane"),
			tabId     = $tabPane.attr("id");
		
		if (tabId) {
			var $icon = $(\'a[href="#\' + tabId + \'"][data-toggle="tab"]\').parent().find("i");

			// Add custom class to tab containing the field
			if (data.status == validator.STATUS_INVALID) {
				$icon.removeClass("fa-check").addClass("fa-times");
			} else if (data.status == validator.STATUS_VALID) {
				var isValidTab = validator.isValidContainer($tabPane);
				$icon.removeClass("fa-check fa-times")
					 .addClass(isValidTab ? "fa-check" : "fa-times");
			}
		};
	});

	$(".objtextdata").bind({
		focus: function() {
			$( this ).css( "background", "#FF8080");
		},
		focusout: function() {
			var Field = $( this ).attr("name");
			var Val = $( this ).val();

			$.ajax({
				url: "realstate/ajax-edit-save.php?objid='.$ObjID.'&fld="+Field+"&val="+Val,
				cache: false,
				success: function(html){
					$("#oksave").show();
					setTimeout(function() { $("#oksave").hide(); }, 2000);
				},
				error: function(xhr, status, error){
					$("#wrsave").show();
					$("#errtext").html(xhr.responseText);
				}
			});
			$( this ).css( "background", "");
		}
	});
});


LoadObjHistory();

function SaveSelect(Val, Field) {
	$.ajax({
		url: "realstate/ajax-edit-save.php?objid='.$ObjID.'&fld="+Field+"&val="+Val,
		cache: false,
		success: function(html){
			$("#oksave").show();
			setTimeout(function() { $("#oksave").hide(); }, 2000);
		},
		error: function(xhr, status, error){
			$("#wrsave").show();
			$("#errtext").html(xhr.responseText);
		}
	});
}
</script>
';

?>