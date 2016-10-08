<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');
/* Пользователи админки */
define("ADMACCDIR", "useraccess");

unset($uid);
if (isset($_REQUEST["uid"]) && is_numeric($_REQUEST["uid"])) {
	$uid = $_REQUEST["uid"];
}

switch ($ModAct) {
	case "":
		echo "<OL>
		<LI><A HREF=\"".$ModURL."&modact=moduls\">".__("TRNSL-MODUL-SPIS")."</A></LI>
		<LI><A HREF=\"".$ModURL."&modact=addnew\">".__("TRNSL-ADD-ADMIN")."</A></LI>
		</ol>
		<h2>".__("TRNSL-USERS-SPIS")."</h2>
		<p>
		<table border=1 class=\"table table-bordered\" style=\"width: auto;\">
		<tr bgcolor=\"silver\" align=center>
			<td><b>Id</b></td>
			<td><b>".__("TRNSL-LOGIN")."</b></td>
			<td><b>".__("TRNSL-NAME")."</b></td>
			<td><b>".__("TRNSL-GROUP")."</b></td>
			<td><b>".__("TRNSL-MIN-PRICE")."</b></td>
			<td><b>".__("TRNSL-MAX-PRICE")."</b></td>
			<td><b>".__("TRNSL-SEE-SALE")."</b></td>
			<td><b>".__("TRNSL-SEE-COMM-SALE")."</b></td>
			<td><b>".__("TRNSL-SEE-RENT")."</b></td>
			<td><b>".__("TRNSL-SEE-COMM-RENT")."</b></td>
			<td><b>".__("TRNSL-ACTIVE")."</b></td>
			<td><b>Edit</b></td>
			<td><b>Del</b></td>
		</tr>\n";

		$r = mysqli_query($hlnk, "SELECT uadm_id, uadm_fio, uadm_login, uadm_group, min_svalue, max_svalue, can_see_sale,
		can_see_comm_sale, can_see_rent, can_see_comm_rent, is_active
		FROM ".$ppt."ta_usrsadm_main ORDER BY BINARY (uadm_fio);") or die (mysqli_error($hlnk)."<HR> List users :(");
		while ($UsList = mysqli_fetch_assoc($r)) {
			echo "<tr align=center>
				<td>".$UsList["uadm_id"]."</td>
				<td align=left>".$UsList["uadm_login"]."</td>
				<td align=left>".$UsList["uadm_fio"]."</td>
				<td align=left>".(isset($UserGrops[$UsList["uadm_group"]]) ? __($UserGrops[$UsList["uadm_group"]]) : "&nbsp;")."</td>
				<td>".$UsList["min_svalue"]."</td>
				<td>".$UsList["max_svalue"]."</td>
				<td>".__($UserAccSel[$UsList["can_see_sale"]])."</td>
				<td>".__($UserAccSel[$UsList["can_see_comm_sale"]])."</td>
				<td>".__($UserAccSel[$UsList["can_see_rent"]])."</td>
				<td>".__($UserAccSel[$UsList["can_see_comm_rent"]])."</td>
				<td>".__($UserAccSel[$UsList["is_active"]])."</td>
				<td><a href=\"".$ModURL."&modact=edituser&uid=".$UsList["uadm_id"]."\">Edit</a></td>
				<td>".($UsList["uadm_id"] > 1 ? "<A HREF=\"".$ModURL."&modact=deluser&uid=".$UsList["uadm_id"]."\" ONCLICK=\"javascript:if(confirm('".__("TRNSL-SURE-DEL")."?')) {return true;} else{return false;}\">Del</a>" : "&nbsp;")."</td>
			</tr>";
		}
		echo "</table>";
	break;

	case "moduls": //Модули сайта
		include("moduls.php");
	break;

	case "addnew":
		include("addnew.php");
	break;


 case "edituser": //Редактирование
	if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) {die("<SCRIPT>history.back();</SCRIPT>");}
	$uid = $_GET["uid"];
	$adms = array();

	/*
	$rfd = mysqli_query($hlnk, "SELECT MR.uadm_id
	FROM ".SQLPRFX."_moduls_rights MR
	INNER JOIN ".SQLPRFX."_moduls_spis MS ON MR.ua_modul_id=MS.ua_modul_id
	WHERE MS.ua_modul_dir='".ADMACCDIR."' AND MR.uadm_flag='1';") or die ("All Data :(");
	$uzklv = mysqli_num_rows($rfd);
	while($uzs = mysqli_fetch_row($rfd)) {
		$adms[$uzs[0]] = 1;
	}
	*/

	$rf = mysqli_query($hlnk, "SELECT uadm_fio, uadm_login, uadm_group, min_svalue, max_svalue, is_active,
	can_see_sale, can_see_comm_sale, can_see_rent,  can_see_comm_rent, lang_id, contacts
	FROM ".SQLPRFX."_main WHERE uadm_id='".$uid."';") or die ("All Data :(");
	$uzdat = mysqli_fetch_assoc($rf);
	echo __("TRNSL-EDIT-ADMIN")."<P>

	<FORM NAME=\"medd\" ACTION=\"".$ModURL."&modact=svchnguser&uid=".$uid."\" METHOD=POST>
	<P><TABLE BORDER=1 class=\"table table-bordered\" style=\"width: auto;\">\n";

	if ($uid > 1) {
		echo "<TR><TD BGCOLOR=\"#F9A79B\" ALIGN=CENTER COLSPAN=2><A HREF=\"".$ModURL."&modact=deluser&uid=".$uid."\" ONCLICK=\"javascript:if(confirm('".__("TRNSL-SURE-DEL")."?')) {return true;} else{return false;}\">".__("TRNSL-DEL-USER")."</A></TD></TR>";
	}

	echo "
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-NAME")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"uname\" SIZE=40 VALUE=\"".$uzdat["uadm_fio"]."\"></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-LOGIN")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"ulogin\" SIZE=40 VALUE=\"".$uzdat["uadm_login"]."\"></TD></TR>
	<TR><TD BGCOLOR=YELLOW ALIGN=CENTER COLSPAN=2><A HREF=\"".$ModURL."&modact=changepasswuser&uid=".$uid."\">".__("TRNSL-CHNG-PASS")."</A></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-CONTACTS")."</B></TD><TD ALIGN=CENTER><TEXTAREA STYLE=\"width: 300px; height:100px;\" NAME=\"contacts\">".$uzdat["contacts"]."</textarea></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-MIN-PRICE")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"uminprice\" SIZE=40 VALUE=\"".$uzdat["min_svalue"]."\"></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-MAX-PRICE")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"umaxprice\" SIZE=40 VALUE=\"".$uzdat["max_svalue"]."\"></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-SEE-SALE")."</B></TD><TD ALIGN=CENTER><select name=\"uflsale\">";
	foreach($UserAccSel as $tId => $tNM) {
		echo "<option value=\"".$tId."\"".($tId == $uzdat["can_see_sale"] ? " selected" : "").">".__($tNM)."</option>";
	}
	echo "</select></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-SEE-COMM-SALE")."</B></TD><TD ALIGN=CENTER><select name=\"uflcommsale\">";
	foreach($UserAccSel as $tId => $tNM) {
		echo "<option value=\"".$tId."\"".($tId == $uzdat["can_see_comm_sale"] ? " selected" : "").">".__($tNM)."</option>";
	}
	echo "</select></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-SEE-RENT")."</B></TD><TD ALIGN=CENTER><select name=\"uflrent\">";
	foreach($UserAccSel as $tId => $tNM) {
		echo "<option value=\"".$tId."\"".($tId == $uzdat["can_see_rent"] ? " selected" : "").">".__($tNM)."</option>";
	}
	echo "</select></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-SEE-COMM-RENT")."</B></TD><TD ALIGN=CENTER><select name=\"uflcommrent\">";
	foreach($UserAccSel as $tId => $tNM) {
		echo "<option value=\"".$tId."\"".($tId == $uzdat["can_see_comm_rent"] ? " selected" : "").">".__($tNM)."</option>";
	}
	echo "</select></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-ACTIVE")."</B></TD><TD ALIGN=CENTER><select name=\"uactive\">";
	foreach ($UserAccSel as $tId => $vSelNm) {
		echo "<option value=\"".$tId."\"";
		if ($tId == $uzdat["is_active"]) {echo " selected";}
		echo ">".__($vSelNm)."</option>";
	}
	echo "</select></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-LANG")."</B></TD><TD ALIGN=CENTER><select name=\"ulang\">";
	foreach ($LangList as $tId => $vSelNm) {
		echo "<option value=\"".$tId."\"";
		if ($tId == $uzdat["lang_id"]) {echo " selected";}
		echo ">".$vSelNm["name"]."</option>";
	}
	echo "</select></TD></TR>
	<TR><TD BGCOLOR=SILVER><B>".__("TRNSL-GROUP")."</B></TD><TD ALIGN=CENTER><select name=\"ugroup\">";
	foreach ($UserGrops as $tId => $vSelNm) {
		echo "<option value=\"".$tId."\"";
		if ($tId == $uzdat["uadm_group"]) {echo " selected";}
		echo ">".__($vSelNm)."</option>";
	}
	echo "</select></TD></TR>
	<TR><TD BGCOLOR=YELLOW COLSPAN=2 ALIGN=CENTER>".__("TRNSL-ACC-TO-MODS")."</TD></TR>\n";

	$res=mysqli_query($hlnk, "SELECT MS.ua_modul_id, MS.ua_modul_nazv, MR.uadm_flag, MS.ua_modul_dir
	FROM ".SQLPRFX."_moduls_spis MS
	LEFT JOIN ".SQLPRFX."_moduls_rights MR ON MS.ua_modul_id=MR.ua_modul_id AND MR.uadm_id='$uid'
	ORDER BY BINARY(MS.ua_modul_nazv);");
	$i = 1;
	while ($tpspis = mysqli_fetch_assoc($res)) {
		echo "<TR><TD BGCOLOR=SILVER><B>".$tpspis["ua_modul_nazv"]."</B></TD>\n";

		if ($tpspis["ua_modul_dir"] == ADMACCDIR && $uid == 1 && $tpspis["uadm_flag"] == "1") {
			echo "<TD ALIGN=CENTER BGCOLOR=PINK>".__("TRNSL-ADMIN-MOD-LAST")."</TD>";
		}
		else {
		echo "\n<TD ALIGN=CENTER>
		<TABLE BORDER=1 class=\"table table-bordered\" style=\"width: auto;\">
		<TR";
		if ($tpspis["uadm_flag"] == "1") {echo " BGCOLOR=\"#A5FD9D\"";}
		echo "><TD><INPUT TYPE=RADIO NAME=\"acctr".$i."\" VALUE=\"1\"";
		if ($tpspis["uadm_flag"] == "1") {echo " CHECKED";}
		echo "></TD><TD>".__("TRNSL-AVAL")."</TD></TR>
		<TR";
		if ($tpspis["uadm_flag"] == "0") {echo " BGCOLOR=\"#FEBADA\"";}
		echo "><TD><INPUT TYPE=RADIO NAME=\"acctr".$i."\" VALUE=\"0\"";
		if ($tpspis["uadm_flag"] == "0") {echo " CHECKED";}
		echo "></TD><TD>".__("TRNSL-NOT-AVAL")."</TD></TR>
		</TABLE>\n";
		if ($tpspis["uadm_flag"] != "") {echo "<INPUT TYPE=\"HIDDEN\" NAME=\"dbsv".$i."\" VALUE=\"1\">";}
		echo "<INPUT TYPE=\"HIDDEN\" NAME=\"ids".$i."\" VALUE=\"".$tpspis["ua_modul_id"]."\">
		</TD>\n";
		}
		echo "
		</TR>";
		$i += 1;
	}
	echo "
	<TR><TD COLSPAN=2 ALIGN=CENTER><INPUT TYPE=SUBMIT VALUE=\"".__("TRNSL-SAVE")."\">
	<INPUT TYPE=HIDDEN NAME=\"filkol\" VALUE=\"$i\">
	</TD></TR></TABLE></FORM>";
 break;

 
 case "svchnguser": //Сохранение изменений
	if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) {die("<SCRIPT>history.back();</SCRIPT>");}
	$uid = $_GET["uid"];

	echo "<A HREF=\"".$ModURL."&modact=edituser&uid=".$uid."\">".__("TRNSL-EDIT-ADMIN")."</A> | ".__("TRNSL-SAVE")."<P>";
	if (!isset($uid) || !is_numeric($uid) || !isset($_POST["uname"]) || $_POST["uname"] == "" || !isset($_POST["ulogin"]) || $_POST["ulogin"] == "") {die("<SCRIPT>history.back()</SCRIPT>");}
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

	$res=mysqli_query($hlnk, "UPDATE `".SQLPRFX."_main` SET `uadm_fio`='".$uname."', `uadm_login`='".$ulogin."',
	`uadm_group`='".$UGroup."', `min_svalue`='".$MinPrice."', `max_svalue`='".$MaxPrice."',
	`can_see_sale`='".$SeeSale."', `contacts`='".$Contacts."',
	`can_see_comm_sale`='".$SeeCommSale."', `can_see_rent`='".$SeeRent."', `can_see_comm_rent`='".$SeeCommRent."', 
	`is_active`='".$uActive."', `lang_id`='".$ULang."'
	WHERE `uadm_id`='".$uid."';") or die("Update :(");

	$r = mysqli_query($hlnk, "SELECT MAX(ua_modul_poz)
	FROM ".SQLPRFX."_moduls_rights
	WHERE uadm_id='$uid'
	GROUP BY uadm_id;") or die ("Users :(");
	$MxPoz = mysqli_fetch_row($r);

	if (isset($_POST["filkol"]) && is_numeric($_POST["filkol"]) && $_POST["filkol"] > 1) {
		for ($i=1; $i<$_POST["filkol"]; $i++) {

			if (isset($_POST["ids$i"])) {
				$fieldId = $_POST["ids$i"];
				$fieldValue = $_POST["acctr$i"];
				if (isset($_POST["dbsv$i"]) && $_POST["dbsv$i"] == 1) {
					$res=mysqli_query($hlnk, "UPDATE ".SQLPRFX."_moduls_rights SET uadm_flag='$fieldValue' WHERE ua_modul_id='$fieldId' AND uadm_id='$uid';") or die("Values update :(");
				}
				else {
					$res = mysqli_query($hlnk, "INSERT INTO ".SQLPRFX."_moduls_rights SET ua_modul_id='".$fieldId."',
					uadm_id='".$uid."', uadm_flag='".$fieldValue."', ua_modul_poz='".($MxPoz+1)."';") or die ("Insert :(");
				}
			}
		}
	}

	echo "<H2>".__("TRNSL-SAVED")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."&modact=edituser&uid=".$uid."';\", 200);\n</SCRIPT>";
 break;

 case "changepasswuser": //интерфейс изменения пароля
	if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) {die("<SCRIPT>history.back();</SCRIPT>");}
	$uid = $_GET["uid"];

	echo "<A HREF=\"".$ModURL."&modact=edituser&uid=".$uid."\">".__("TRNSL-EDIT-ADMIN")."</A> | ".__("TRNSL-CHNG-PASS")."<P>
	<FORM NAME=\"medd\" ACTION=\"".$ModURL."&modact=savchuzpassw&uid=".$uid."\" METHOD=POST>
	<TABLE BORDER=1 class=\"table table-bordered\" style=\"width: auto;\">
	<TR><TD BGCOLOR=SILVER ALIGN=CENTER><B>".__("TRNSL-NEW-PASS")."</B></TD><TD ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"npassw\" SIZE=30 VALUE=\"".$instances["textfunct"]->generate_sometype_passw(ADMUSERPASSWLEIGHT, 1, 1, 1)."\"></TD></TR>
	<TR><TD COLSPAN=2 ALIGN=CENTER BGCOLOR=SILVER><A HREF=\"#\" ONCLICK=\"window.location.reload();\">".__("TRNSL-GEN-NEW-PASS")."</A></TD></TR>
	<TR><TD COLSPAN=2 ALIGN=CENTER><INPUT TYPE=SUBMIT VALUE=\"".__("TRNSL-SAVE")."\"></TD></TR>
	</TABLE></FORM>";
 break;

 case "savchuzpassw": //сохранение нового пароля
	if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) {die("<SCRIPT>history.back();</SCRIPT>");}
	$uid = $_GET["uid"];

	$passwmessg = "";
	if (!isset($_POST["npassw"]) || $_POST["npassw"] == "") {
		$upassw = $instances["textfunct"]->generate_sometype_passw(ADMUSERPASSWLEIGHT, 1, 1, 1);
		$passwmessg = $upassw;
	}
	else {$upassw = $_POST["npassw"];}
	$upassw = md5($upassw);

	$res=mysqli_query($hlnk, "UPDATE ".SQLPRFX."_main SET uadm_passw='".$upassw."' WHERE uadm_id='".$uid."';") or die("обновление :(");

	if ($passwmessg != "") {
		echo "<A HREF=\"".$ModURL."&modact=edituser&uid=".$uid."\">".__("TRNSL-EDIT-ADMIN")."</A> | ".__("TRNSL-CHNG-PASS")."<H2>".__("TRNSL-WGEN-NEW-PASS").": <font color=red>".$passwmessg."</font></H2><A HREF=\"".$ModURL."&modact=edituser&uid=".$uid."\">".__("TRNSL-BACK")."</A>";
	}
	else {
		echo "<H2>".__("TRNSL-SAVED")."</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."&modact=edituser&uid=".$uid."';\", 200);\n</SCRIPT>";
	}
 break;

 case "deluser": //удаление
	if (!isset($_GET["uid"]) || !is_numeric($_GET["uid"])) {die("<SCRIPT>history.back();</SCRIPT>");}
	$uid = $_GET["uid"];

	$r=mysqli_query($hlnk, "DELETE FROM ".SQLPRFX."_main WHERE uadm_id='".$uid."';") or die ("DEL1 :(");
	$r=mysqli_query($hlnk, "OPTIMIZE TABLE ".SQLPRFX."_main;") or die ("OPTIM2 :(");

	$r=mysqli_query($hlnk, "DELETE FROM ".SQLPRFX."_moduls_rights WHERE uadm_id='".$uid."';") or die ("DEL2 :(");
	$r=mysqli_query($hlnk, "OPTIMIZE TABLE ".SQLPRFX."_moduls_rights;") or die ("OPTIM2 :(");
	echo "<H2>".__("Удалено")."</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."'\", 500);\n</SCRIPT>";
 break;
}
?>