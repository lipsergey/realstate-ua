<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');

switch ($ModAct) {
	case "":
		echo "<p>
		<div id=\"ajaxrz\"></div>
		<table border=1 cellspacing=0 cellpadding=3 align=center class=\"table table-bordered\" style=\"width: auto; margin-left: 20px;\">
		<tr bgcolor=\"silver\" align=center>
			<td><b>Index</b></td>
			<td><b>Ukrainian</b></td>
			<td><b>Russian</b></td>
			<td><b>Save</b></td>
		</tr>
		<form name=\"addnew\" method=\"post\" action=\"".$ModURL."&modact=addnew\">
		<tr bgcolor=\"yellow\">
			<td><input type=\"text\" name=\"newindex\" style=\"width:200px;\"></td>
			<td><input type=\"text\" name=\"newua\" style=\"width:300px;\"></td>
			<td><input type=\"text\" name=\"newrus\" style=\"width:300px;\"></td>
			<td><input type=\"submit\" value=\"Add\" class=\"btn btn-lg btn-primary\"></td>
		</tr>
		</form>";

		$r=mysqli_query($hlnk, "SELECT KeyText, RusText, UaText
		FROM ".SQLPRFX."
		WHERE 1 ORDER BY BINARY(KeyText);") or die ("Wrong get translate");
		while($TrList = mysqli_fetch_assoc($r)) {
			echo '<tr>
			<td>'.$TrList["KeyText"].'</td>
			<td><input class="svdata" type="text" name="ua-'.$TrList["KeyText"].'" id="ua-'.$TrList["KeyText"].'"  kindex="'.$TrList["KeyText"].'" ftype="1" style="width:300px;" value="'.$TrList["UaText"].'"></td>
			<td><input class="svdata" type="text" name="rus-'.$TrList["KeyText"].'" id="rus-'.$TrList["KeyText"].'" kindex="'.$TrList["KeyText"].'" ftype="2" style="width:300px;" value="'.$TrList["RusText"].'"></td>
			</tr>'."\n";
		}
		echo '
		</table>
		<script>
			$(".svdata").bind({
				focus: function() {
					$( this ).css( "background", "#FF8080");
				},
				focusout: function() {
					var ElmId = this.id;
					var RefType = $( this ).attr("ftype");
					var RefID = $( this ).attr("kindex");
					var RefVal = $( this ).val();

					$.ajax({
						url: "translate/ajax-save.php?refid="+RefID+"&type="+RefType+"&refvalue="+RefVal,
						cache: false,
						success: function(html){
							$("#"+ElmId).css( "background", "#99ff99");
							$("#ajaxrz").html(html);
						}
					});
				}
			});
		</script>
		';
	break;

	case "addnew":
		if (!isset($_POST["newindex"]) || $_POST["newindex"] == "") {
			echo "<h2>".__("TRNSL-NOTRANSL-INDEX")."</h2>";
			die();
		}
		
		$NUa = str_replace(array("'", '"'), array('', '&quot;'),  $_POST["newua"]);
		$NRus = str_replace(array("'", '"'), array('', '&quot;'),  $_POST["newrus"]);
		$NIndex = str_replace(array("'", '"'), array('', ''),  $_POST["newindex"]);

		$r = mysqli_query($hlnk, "INSERT INTO ".$ppt."translate SET `KeyText`='".$NIndex."', `RusText`='".$NRus."',
		`UaText`='".$NUa."';") or die ("Insert new :( ".mysqli_error($hlnk));
		
		echo "<H2>".__("TRNSL-SAVED")."</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."'\", 500);\n</SCRIPT>";
		
	break;
}

?>