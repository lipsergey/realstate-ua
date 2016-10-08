<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');
/* Добавление пользователя  */

$ltab = "$ModURL&modact=addnew";

if (!isset($ltact)) {
 $ltact = "";
 echo __("Добавить нового администратора");
}
else {
 echo "<A HREF=\"$ltab\">".__("Добавить нового администратора")."</A> | ";
}

switch ($ltact) {
	case "":
		echo "
		<script src=\"/js/jquery/jquery-1.11.3.min.js\" type=\"text/javascript\"></script>
		<FORM NAME=\"madd\" ACTION=\"$ltab&ltact=svnew\" METHOD=POST>
		<P>&nbsp;<TABLE BORDER=1 class=\"table table-bordered\" style=\"width: auto;\">
		<TR><TD BGCOLOR=SILVER><B>".__("Имя")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"uname\" SIZE=40></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("Логин")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"ulogin\" SIZE=40></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("Пароль")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"upassw\" SIZE=40 VALUE=\"".$instances["textfunct"]->generate_sometype_passw(ADMUSERPASSWLEIGHT, 1, 1, 1)."\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-CONTACTS")."</B></TD><TD ALIGN=CENTER><TEXTAREA STYLE=\"width: 300px; height:100px;\" NAME=\"contacts\"></textarea></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-MIN-PRICE")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"uminprice\" SIZE=40 VALUE=\"\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-MAX-PRICE")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"umaxprice\" SIZE=40 VALUE=\"\"></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-SEE-SALE")."</B></TD><TD ALIGN=CENTER><select name=\"uflsale\">";
		foreach($UserAccSel as $tId => $tNM) {
			echo "<option value=\"".$tId."\">".__($tNM)."</option>";
		}
		echo "</select></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-SEE-COMM-SALE")."</B></TD><TD ALIGN=CENTER><select name=\"uflcommsale\">";
		foreach($UserAccSel as $tId => $tNM) {
			echo "<option value=\"".$tId."\">".__($tNM)."</option>";
		}
		echo "</select></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-SEE-RENT")."</B></TD><TD ALIGN=CENTER><select name=\"uflrent\">";
		foreach($UserAccSel as $tId => $tNM) {
			echo "<option value=\"".$tId."\">".__($tNM)."</option>";
		}
		echo "</select></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-SEE-COMM-RENT")."</B></TD><TD ALIGN=CENTER><select name=\"uflcommrent\">";
		foreach($UserAccSel as $tId => $tNM) {
			echo "<option value=\"".$tId."\">".__($tNM)."</option>";
		}
		echo "</select></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-ACTIVE")."</B></TD><TD ALIGN=CENTER><select name=\"uactive\">";
		foreach ($UserAccSel as $tId => $vSelNm) {
			echo "<option value=\"".$tId."\">".__($vSelNm)."</option>";
		}
		echo "</select></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-LANG")."</B></TD><TD ALIGN=CENTER><select name=\"ulang\">";
		foreach ($LangList as $tId => $vSelNm) {
			echo "<option value=\"".$tId."\">".$vSelNm["name"]."</option>";
		}
		echo "</select></TD></TR>
		<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-GROUP")."</B></TD><TD ALIGN=CENTER><select name=\"ugroup\">";
		foreach ($UserGrops as $tId => $vSelNm) {
			echo "<option value=\"".$tId."\">".__($vSelNm)."</option>";
		}
		echo "</select></TD></TR>
		<TR><TD BGCOLOR=YELLOW COLSPAN=2 ALIGN=CENTER>".__("TRNSL-ACC-TO-MODS")."</TD></TR>\n";

		$res=mysqli_query($hlnk, "SELECT ua_modul_id, ua_modul_nazv
		FROM ".SQLPRFX."_moduls_spis ORDER BY BINARY(ua_modul_nazv);");
		$i = 1;
		while ($tpspis = mysqli_fetch_row($res)) {
			echo "<TR><TD BGCOLOR=SILVER><B>$tpspis[1]</B></TD>
			<TD ALIGN=CENTER>
			<TABLE BORDER=1 class=\"table table-bordered\" style=\"width: auto;\">
			<TR><TD><INPUT TYPE=RADIO NAME=\"acctr$i\" VALUE=\"1\"></TD><TD>".__("TRNSL-AVAL")."</TD></TR>
			<TR><TD><INPUT TYPE=RADIO NAME=\"acctr$i\" VALUE=\"0\" CHECKED></TD><TD>".__("TRNSL-NOT-AVAL")."</TD></TR>
			</TABLE>
			<INPUT TYPE=\"HIDDEN\" NAME=\"ids$i\" VALUE=\"$tpspis[0]\">
			</TD></TR>";
			$i += 1;
		}

		echo "
		<TR><TD COLSPAN=2 ALIGN=CENTER><INPUT TYPE=SUBMIT VALUE=\"".__("Сохранить")."\">
		<INPUT TYPE=HIDDEN NAME=\"filkol\" VALUE=\"$i\">
		</TD></TR></TABLE></FORM>
		<script>
		function AddEmail() {
			$('#emails').append('<div style=\"width:100%\"><INPUT TYPE=TEXT NAME=\"uemail[]\" SIZE=40>&nbsp;<a href=\"#\" onclick=\"DelEmail(this);return false;\"><img src=\"/modimgs/cancel.gif\" border=0 valign=\"middle\"></a></div>'); 
		}
		function DelEmail(Elm) {
			$(Elm).parent().remove();
		}
		</script>
		";
	break;

	case "svnew":
		$passwmessg = "";
		if (!isset($_POST["uname"]) || $_POST["uname"] == "" || !isset($_POST["ulogin"]) || $_POST["ulogin"] == "") {die("<SCRIPT>history.back()</SCRIPT>");}
		$uname = str_replace("'", '', $_POST["uname"]);
		$ulogin = str_replace("'", '', $_POST["ulogin"]);
		$Contacts = str_replace("'", '', $_POST["contacts"]);

		$MinPrice = ((isset($_POST["uminprice"]) && is_numeric($_POST["uminprice"])) ? $_POST["uminprice"] : 0);
		$MaxPrice = ((isset($_POST["umaxprice"]) && is_numeric($_POST["umaxprice"])) ? $_POST["umaxprice"] : 0);
		$SeeSale = ((isset($_POST["uflsale"]) && is_numeric($_POST["uflsale"])) ? $_POST["uflsale"] : 0);
		$SeeCommSale = ((isset($_POST["uflcommsale"]) && is_numeric($_POST["uflcommsale"])) ? $_POST["uflcommsale"] : 0);
		$SeeRent = ((isset($_POST["uflrent"]) && is_numeric($_POST["uflrent"])) ? $_POST["uflrent"] : 0);
		$SeeCommRent = ((isset($_POST["uflcommrent"]) && is_numeric($_POST["uflcommrent"])) ? $_POST["uflcommrent"] : 0);
		$uActive = ((isset($_POST["uactive"]) && is_numeric($_POST["uactive"])) ? $_POST["uactive"] : 0);
		$ULang = ((isset($_POST["ulang"]) && is_numeric($_POST["ulang"])) ? $_POST["ulang"] : 1);
		$UGroup = ((isset($_POST["ugroup"]) && is_numeric($_POST["ugroup"])) ? $_POST["ugroup"] : 4);

		if (!isset($_POST["upassw"]) || $_POST["upassw"] == "") {
			$_UPass = $instances["textfunct"]->generate_sometype_passw(ADMUSERPASSWLEIGHT, 1, 1, 1);
			$passwmessg = $_UPass;
		}
		else {
			$_UPass = $_POST["upassw"];
		}
		$_UPass = md5($_UPass);

		$r = mysqli_query($hlnk, "INSERT INTO `".SQLPRFX."_main` SET `uadm_id`='', `uadm_fio`='".$uname."',
		`uadm_login`='".$ulogin."', `uadm_passw`='".$_UPass."', `uadm_group`='".$UGroup."',
		`min_svalue`='".$MinPrice."', `max_svalue`='".$MaxPrice."', `can_see_sale`='".$SeeSale."',
		`contacts`='".$Contacts."', `can_see_comm_sale`='".$SeeCommSale."',
		`can_see_rent`='".$SeeRent."', `can_see_comm_rent`='".$SeeCommRent."', `is_active`='".$uActive."',
		`lang_id`='".$ULang."';") or die ("Add :(");
		$AddId = mysqli_insert_id($hlnk);


		$paramsSql = "";

		if (isset($_POST["filkol"]) && is_numeric($_POST["filkol"]) && $_POST["filkol"] > 1) {
			$t = 1;
			for ($i=1; $i<$_POST["filkol"]; $i++) {
				if (isset($_POST["ids$i"]) && is_numeric($_POST["ids$i"])
					&& isset($_POST["acctr$i"]) && is_numeric($_POST["acctr$i"])) {
					$fieldId = $_POST["ids$i"];
					$fieldValue = $_POST["acctr$i"];
					if ($paramsSql != "") {$paramsSql .= ", ";}
					$paramsSql .= "('$fieldId', '$AddId', '$fieldValue', '$t')";
					$t += 1;
				}
			}
			if ($paramsSql != "") {
				$res = mysqli_query($hlnk, "INSERT INTO ".SQLPRFX."_moduls_rights VALUES ".$paramsSql.";") or die ("Write :(");
			}
		}
		if($passwmessg != "") {
			echo "<H2>".__("TRNSL-WGEN-NEW-PASS").": <font color=red>$passwmessg</font></H2>";
			die();
		}

		echo "<H2>".__("TRNSL-SAVED")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href=\'$ModURL\'\", 200);\n</SCRIPT>";
	break;
}
?>