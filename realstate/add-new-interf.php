<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');


echo '
<script src="js/jquery/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/bootstrapValidator.css"/>
<script type="text/javascript" src="js/bootstrapValidator.min.js"></script>
<link rel="stylesheet" href="js/jquery/chosen.css">
<form name="mdata" id="mdata" method="post" action="'.$ModURL.'&modact=savenewobj" enctype="multipart/form-data" class="form-horizontal">
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
<div class="tabs-box">
	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#tabs-main">'.__("TRNSL-OBJ-INFO-MAIN").' <i class="fa"></i></a></li>
		<li><a data-toggle="tab" href="#tabs-dopinf">'.__("TRNSL-OBJ-INFO-DOP").' <i class="fa"></i></a></li>
		<li><input type="submit" value="'.__("TRNSL-SAVE-CONTINUE").'" class="btn btn-success" style="margin-left:10px;"></li>
		<li><input type="button" value="'.__("TRNSL-CANCEL").'" class="btn btn-danger" style="margin-left:10px;" onclick="document.location.href=\''.$ModURL.'\';"></li>
	</ul>
	 
	<div class="tab-content">

		<div id="tabs-main" class="tab-pane fade in active">
			<h3>'.__("TRNSL-OBJ-INFO-MAIN").'</h3>

			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-STREET").'*</td>
				<td><div class="col-lg-4"><select data-placeholder="'.__("TRNSL-SELECT-STREET").'..." name="objstreet" class="form-control chosen-select" style="width: 200px;">
				<option value=""></option>';
				foreach($AllStreets as $tSTID => $tSTNM) {
					echo "<option value=\"".$tSTID."\">".$tSTNM."</option>\n";
				}

				echo '</select></div></td>
				<td>&nbsp;</td>
				<td>'.__("TRNSL-ADDRESS").'</td>
				<td><input type="text" style="width: 200px;" name="objaddr" class="form-control"></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-RAJON").'</td>
				<td><select name="objaddrrajon" class="form-control">
				<option value="n"></option>';
				foreach($AllRajons as $tRjID => $tRjTxt) {
					echo "<option value=\"".$tRjID."\">".$tRjTxt."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-OBJ-TYPE").'</td>
				<td><select name="objtype" class="form-control">';
				foreach($ObjTypes as $ObjTpID => $ObjTpTxt) {
					echo "<option value=\"".$ObjTpID."\">".__($ObjTpTxt)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-FLOOR").'*</td>
				<td><div class="col-lg-4"><input type="number" style="width: 60px;" name="objfloor" class="form-control" data-bv-icon-for="objfloor"></div></td>
				<td>'.__("TRNSL-FLOOR-KLV").'*</td>
				<td><div class="col-lg-4"><input type="number" style="width: 60px;" name="objfloorsklv" class="form-control" data-bv-icon-for="objfloorsklv"></div></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-OBJ-INFO-TOT-AREA").'*</td>
				<td><div class="col-lg-4"><input type="text" style="width: 60px;" name="objtotarea" class="form-control"></div></td>
				<td>'.__("TRNSL-OBJ-INFO-AREA").'*</td>
				<td><div class="col-lg-4"><input type="text" style="width: 130px;" name="objarea" class="form-control"></div></td>
				<td>'.__("TRNSL-KOLVO-ROOMS").'*</td>
				<td><div class="col-lg-4"><input type="number" style="width: 60px;" name="objroomsklv" class="form-control"></div></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-CONTACTS-OWNER").'*</td>
				<td><div class="col-lg-4"><textarea style="width: 300px !important; height: 80px !important;" name="objownercontacts" class="form-control" rows="10"></textarea></div></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-PRICE").'*</td>
				<td><div class="col-lg-4"><input type="text" style="width: 60px;" name="objprice" class="form-control"></div></td>
				<td>'.__("TRNSL-PRICE-VALUT").'</td>
				<td><select name="objpricevalut" class="form-control">';
				foreach($Valuts as $ValID => $ValT) {
					echo "<option value=\"".$ValID."\">".__($ValT)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-PREPAY").'</td>
				<td><input type="text" style="width: 70px;" name="objprepay" class="form-control"></td>
				<td>'.__("TRNSL-PREPAY-VALUT").'</td>
				<td><select name="objprepavalut" class="form-control">';
				foreach($Valuts as $ValID => $ValT) {
					echo "<option value=\"".$ValID."\">".__($ValT)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-TRANS-TYPE").'</td>
				<td><select style="width: 100px;" name="objtranstype" class="form-control">
				<option value="1">'.__("TRNSL-SEE-SALE").'</option>
				<option value="2">'.__("TRNSL-SEE-RENT").'</option>
				</select></td>
				<td>'.__("TRNSL-OBJ-COMMERCE").':</td>
				<td><select style="width: 100px;" name="objcommerce" class="form-control">';
				foreach($UserAccSel as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<p>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-LINKED-RIELTOR").':</td>
				<td><select style="width: 100px;" name="objlinkedrieltor" class="form-control">
				<option value="n">'.__("TRNSL-LINKED-NO-RIELTOR").'</option>';
				foreach($AllRieltors as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".$tNM["uadm_fio"]."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-FRIEND-RIELTOR").'</td>
				<td><input type="checkbox" name="objfririelt" value="1"></td>
			</tr>
			</table>
		</div>

		<div id="tabs-dopinf" class="tab-pane fade">
			<h3>'.__("TRNSL-OBJ-INFO-DOP").'</h3>

			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-FL-TYPE").':</td>
				<td><select style="width: 100px;" name="objhousetype" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($HouseType as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-MATERAL").':</td>
				<td><select style="width: 100px;" name="objwallmatherial" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($WallMaterial as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-FL-ENT").':</td>
				<td><select style="width: 100px;" name="objflatenter" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($EnterIntoFlat as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-HEAT").':</td>
				<td><select style="width: 100px;" name="objheat" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($HeatType as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-SNT").':</td>
				<td><select style="width: 100px;" name="objsantech" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($Santehtype as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-REMONT").':</td>
				<td><select style="width: 100px;" name="objremont" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($Remont as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-MEBEL").':</td>
				<td><select style="width: 100px;" name="objmebel" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($Mebel as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-KUHNI").':</td>
				<td><select style="width: 100px;" name="objkuhni" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($Kuhny as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
				<td>'.__("TRNSL-PEREKR").':</td>
				<td><select style="width: 100px;" name="objperekr" class="form-control">
				<option value="n"> -------------- </option>';
				foreach($Perekrit as $tID => $tNM) {
					echo "<option value=\"".$tID."\">".__($tNM)."</option>\n";
				}
				echo '</select></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-TECH-TV").'</td>
				<td><input type="checkbox" name="obthtv" value="1"></td>
				<td>'.__("TRNSL-TECH-HOLOD").'</td>
				<td><input type="checkbox" name="objthholod" value="1"></td>
				<td>'.__("TRNSL-TECH-STIRAL").'</td>
				<td><input type="checkbox" name="objthstir" value="1"></td>
				<td>'.__("TRNSL-TECH-INET").'</td>
				<td><input type="checkbox" name="objthinet" value="1"></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-OBJ-ANOTHER").'</td>
				<td><textarea style="width: 500px; height: 80px;" name="objanotherinf"></textarea></td>
			</tr>
			</table>
			<table border=0 class="table" style="width: auto;">
			<tr>
				<td>'.__("TRNSL-OBJ-WILL-LIVE").'</td>
				<td><textarea style="width: 500px; height: 80px;" name="objwilllive"></textarea></td>
			</tr>
			</table>
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
});
</script>
';

?>