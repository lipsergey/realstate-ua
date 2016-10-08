<?php
defined('SHP_VALID') or die('Direct Access is not allowed.');

$Monthes = array('*','1','2','3','4','5','6','7','8','9','10','11','12');
$Days = array('*','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
$Hours = array('*','0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
$Minuts = array('@10','@20','@30','0','10','20','30','40','50');

switch ($ModAct) {	case "":
		echo "<h3>For run crontab agent of system need add entry into crontab programm on server: <font color=red>*/10 * * * * ".$SPUrl."/".MODDIR."/cronrun.php >/dev/null 2>&1</font> (start each 10 minutes)</h3>
		<A HREF=\"".$ModURL."&modact=cronstparams\">Update settings of crontab system</A>
		<h4><A HREF=\"".$ModURL."&modact=cronstman\">Manually start crontab script</A></h4>
		<P><TABLE BORDER=1 CELLSPACING=0 CELLPADDING=4 ALIGN=CENTER>
		<TR BGCOLOR=SILVER ALIGN=CENTER>
		<TD><B>#</B></TD>
		<TD><B>Name</B></TD>
		<TD><B>File</B><SUP>1</SUP></TD>
		<TD><B>Start time</B><SUP>2</SUP></TD>
		<TD><B>Status</B></TD>
		<TD><B>Edit</B></TD>
		<TD><B>Del</B></TD>
		</TR>
		<FORM NAME=\"addtask\" METHOD=POST ACTION=\"".$ModURL."&modact=addnew\">
		<TR BGCOLOR=YELLOW ALIGN=CENTER>
		<TD>N</TD>
		<TD WIDTH=100><INPUT TYPE=TEXT NAME=\"crname\" SIZE=\"20\"></TD>
		<TD WIDTH=100><INPUT TYPE=TEXT NAME=\"crfile\" SIZE=\"20\" VALUE=\"\"></TD>
		<TD WIDTH=400>
			<TABLE BORDER=1 CELLSPACING=0 CELLPADDING=4 ALIGN=CENTER>
			<TR ALIGN=CENTER><TD>Year (fornat - 2010)</TD><TD><INPUT TYPE=TEXT NAME=\"cryear\" VALUE=\"*\" SIZE=\"5\"></TD></TR>
			<TR ALIGN=CENTER><TD>Month</TD><TD><SELECT NAME=\"crmonth\">\n";
			foreach ($Monthes as $Key) {				echo "<OPTION VALUE=\"".$Key."\">".$Key."</OPTION>\n";			}
			echo "</SELECT></TD></TR>
			<TR ALIGN=CENTER><TD>Day</TD><TD><SELECT NAME=\"crday\">\n";
			foreach ($Days as $Key) {
				echo "<OPTION VALUE=\"".$Key."\">".$Key."</OPTION>\n";
			}
			echo "</SELECT></TD></TR>
			<TR ALIGN=CENTER><TD>Hour</TD><TD><SELECT NAME=\"crhour\">\n";
			foreach ($Hours as $Key) {
				echo "<OPTION VALUE=\"".$Key."\">".$Key."</OPTION>\n";
			}
			echo "</SELECT></TD></TR>
			<TR ALIGN=CENTER><TD>Minute</TD><TD><SELECT NAME=\"crmin\">\n";
			foreach ($Minuts as $Key) {
				echo "<OPTION VALUE=\"".$Key."\">".$Key."</OPTION>\n";
			}
			echo "</SELECT></TD></TR>

			</TABLE>
		</TD>
		<TD WIDTH=100>
			<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=4 ALIGN=CENTER>
			<TR><TD><INPUT TYPE=RADIO NAME=\"cractive\" VALUE=\"1\"></TD><TD>Active</TD></TR>
			<TR><TD><INPUT TYPE=RADIO NAME=\"cractive\" VALUE=\"0\" CHECKED></TD><TD>Inactive</TD></TR>
			</TABLE>
		</TD>
		<TD COLSPAN=2><INPUT TYPE=SUBMIT VALUE=\"".__("Добавить")."\"></TD>
		</TR></FORM>\n";

		addNewLibs('crontime');

		$r = mysqli_query($hlnk, "SELECT Task_ID, Task_Year, Task_Month, Task_Day, Task_Hour, Task_Minute, Task_RunFile,
		Task_Nazv, Task_Active, Task_LastStart
		FROM ".SQLPRFX."tasks
		ORDER BY Task_ID;") or die ("Task spis :(");
		if (mysqli_num_rows($r) == 0) {echo "<TR><TD COLSPAN=\"7\" ALIGN=CENTER>No tasks</TD></TR>\n";}
		$i = 1;
		while ($CronTasks = mysqli_fetch_assoc($r)) {			$Color = "";
			$Active = "Active";
			if ($CronTasks["Task_Active"] == "0") {				$Color = " BGCOLOR=\"#E8E8E8\"";
				$Active = "Not active";
			}
			$StartFin = "";

			if (file_exists($SPUrl . $CronTasks["Task_RunFile"])) {				$Img = "<IMG SRC=\"modimgs/ok.gif\" border=0 alt=\"Path correct, file exists\" title=\"Path correct, file exists\">";
			}
			else {				$Color = " BGCOLOR=\"#FEBADA\"";
				$Img = "<IMG align=middle SRC=\"modimgs/cancel.gif\" border=0 alt=\"Path wrong, file not found\" title=\"Path wrong, file not found\">";
			}

			$Next = $instances["crontime"]->TaskParams($CronTasks);

			if ($CronTasks["Task_Year"] == "*") {$CronTasks["Task_Year"]= "each";}
			if ($CronTasks["Task_Month"] == "*") {$CronTasks["Task_Month"] = "each";}
 			if ($CronTasks["Task_Day"] == "*") {$CronTasks["Task_Day"] = "each";}
			if ($CronTasks["Task_Hour"] == "*") {$CronTasks["Task_Hour"] = "each";}
			if ($CronTasks["Task_LastStart"] == "0000-00-00 00:00:00") {$CronTasks["Task_LastStart"] = "never";}

			echo "<TR ALIGN=CENTER".$Color.">
			<TD>".$i."</TD>
			<TD>".$CronTasks["Task_Nazv"]."</TD>
			<TD>".$Img."&nbsp;".$CronTasks["Task_RunFile"]."</TD>
			<TD><TABLE BORDER=1 CELLSPACING=0 CELLPADDING=4 ALIGN=CENTER>
				<TR><TD VALIGN=TOP BGCOLOR=SILVER><B>Условие</B></TD><TD>
					<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2 ALIGN=CENTER>
					<TR><TD>Year</TD><TD>: ".$CronTasks["Task_Year"]."</TD></TR>
					<TR><TD>Month</TD><TD>: ".$CronTasks["Task_Month"]."</TD></TR>
					<TR><TD>Day</TD><TD>: ".$CronTasks["Task_Day"]."</TD></TR>
					<TR><TD>Hour</TD><TD>: ".$CronTasks["Task_Hour"]."</TD></TR>
					<TR><TD>Minute</TD><TD>: ".$CronTasks["Task_Minute"]."</TD></TR>
					</TABLE>
				</TD></TR>
				<TR><TD BGCOLOR=SILVER><B>Last start</B></TD><TD>".$CronTasks["Task_LastStart"]."</TD></TR>
				<TR><TD BGCOLOR=SILVER><B>Next start</B></TD><TD>".$Next."</TD></TR>
			</TABLE></TD>
			<TD>".$Active."</TD>
			<TD><A HREF=\"".$ModURL."&modact=edit&crid=".$CronTasks["Task_ID"]."\">Edit</A></TD>
			<TD><A HREF=\"".$ModURL."&modact=del&crid=".$CronTasks["Task_ID"]."\" ONCLICK=\"javascript:if(confirm('".__("Действительно хотите удалить")."?')) {return true;} else{return false;}\">Del</A></TD>
			</TR>\n";
			$i += 1;		}
		echo "</table>
		<ol>
		<li>Path from site root (".$SPUrl.") to PHP file, which need to start. File did not include \"include()\" instructions with relative paths. This file can't noway finished by \"die()\" instruction. The file permissions should be 0755</li>
		<LI>\"*\" - means \"each\" value (i.e. \"each year\", \"each month\" and etc.), \"@10\" - means start every 10 minutes</LI>
		<li>At the same time it can not be performed more than 3 files. Set tasks to the different time.</li>
		</ol>";
	break;


	case "edit": //Редактирование
		if (!isset($_GET["crid"]) || !is_numeric($_GET["crid"])) {die("<SCRIPT>history.back();</SCRIPT>");}
		$CronID = $_GET["crid"];

		$r = mysqli_query($hlnk, "SELECT Task_Year, Task_Month, Task_Day, Task_Hour, Task_Minute,
		Task_RunFile, Task_Nazv, Task_Active, Task_LastStart
		FROM ".SQLPRFX."tasks
		WHERE Task_ID='".$CronID."';") or die ("Task Get :(");
		$CronDt = mysqli_fetch_assoc($r);
		if ($CronDt["Task_LastStart"] == "0000-00-00 00:00:00") {$CronDt["Task_LastStart"] = "never";}

		echo "Edit
		<P><FORM NAME=\"addtask\" METHOD=POST ACTION=\"".$ModURL."&modact=svchng&crid=".$CronID."\">
		<TABLE BORDER=1 CELLSPACING=0 CELLPADDING=4 ALIGN=CENTER>
		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>Name</B></TD><TD><INPUT TYPE=TEXT NAME=\"crname\" SIZE=\"40\" VALUE=\"".$CronDt["Task_Nazv"]."\"></TD></TR>
		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>File</B></TD><TD><INPUT TYPE=TEXT NAME=\"crfile\" SIZE=\"40\" VALUE=\"".$CronDt["Task_RunFile"]."\"></TD></TR>
		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>Year</B><BR>(формат - 2010)</TD><TD><INPUT TYPE=TEXT NAME=\"cryear\" SIZE=\"10\" VALUE=\"".$CronDt["Task_Year"]."\"></TD></TR>
		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>Month</B></TD><TD><SELECT NAME=\"crmonth\">\n";
		foreach ($Monthes as $Key) {
			echo "<OPTION VALUE=\"".$Key."\"";
			if ($Key == $CronDt["Task_Month"]) {echo " SELECTED";}
			echo ">".$Key."</OPTION>\n";
		}
		echo "</SELECT></TD></TR>
		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>Day</B></TD><TD><SELECT NAME=\"crday\">\n";
		foreach ($Days as $Key) {
			echo "<OPTION VALUE=\"".$Key."\"";
			if ($Key == $CronDt["Task_Day"]) {echo " SELECTED";}
			echo ">".$Key."</OPTION>\n";
		}
		echo "</SELECT></TD></TR>
		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>Hour</B></TD><TD><SELECT NAME=\"crhour\">\n";
		foreach ($Hours as $Key) {
			echo "<OPTION VALUE=\"".$Key."\"";
			if ($Key == $CronDt["Task_Hour"]) {echo " SELECTED";}
			echo ">".$Key."</OPTION>\n";
		}
		echo "</SELECT></TD></TR>
		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>Minute</B></TD><TD><SELECT NAME=\"crmin\">\n";
		foreach ($Minuts as $Key) {
			echo "<OPTION VALUE=\"".$Key."\"";
			if ($Key == $CronDt["Task_Minute"]) {echo " SELECTED";}
			echo ">".$Key."</OPTION>\n";
		}
		echo "</SELECT></TD></TR>

		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>Status</B></TD><TD>
			<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=4 ALIGN=CENTER>
			<TR><TD><INPUT TYPE=RADIO NAME=\"cractive\" VALUE=\"1\"";
			if ($CronDt["Task_Active"] == 1) {echo " checked";}
			echo "></TD><TD>Active</TD></TR>
			<TR><TD><INPUT TYPE=RADIO NAME=\"cractive\" VALUE=\"0\"";
			if ($CronDt["Task_Active"] == 0) {echo " checked";}
			echo "></TD><TD>Inactive</TD></TR>
			</TABLE>
		</TD></TR>
		<TR ALIGN=CENTER><TD BGCOLOR=SILVER><B>Last start</B></TD><TD>".$CronDt["Task_LastStart"]."</TD></TR>
		<TR BGCOLOR=SILVER ALIGN=CENTER><TD COLSPAN=2><INPUT TYPE=SUBMIT VALUE=\"".__("Сохранить")."\"></TD></TR>
		</TABLE>
		</FORM>\n";
	break;

	case "svchng":
		if (!isset($_GET["crid"]) || !is_numeric($_GET["crid"])) {die("<SCRIPT>history.back();</SCRIPT>");}
		$CronID = $_GET["crid"];

 		$CRONName = str_replace("'", '', $_POST["crname"]);
		$CRONName = str_replace('"', '&quot;', $CRONName);

 		$CRONFile = str_replace("'", '', $_POST["crfile"]);
		$CRONFile = str_replace('"', '', $CRONFile);

		if (!isset($_POST["cryear"]) || ($_POST["cryear"] != "*" && !is_numeric($_POST["cryear"]))) {$CRONYear = "*";}
		else {$CRONYear = $_POST["cryear"];}

		if (!isset($_POST["crmonth"]) || !in_array($_POST["crmonth"], $Monthes)) {$CRONMnth = "*";}
		else {$CRONMnth = $_POST["crmonth"];}

		if (!isset($_POST["crday"]) || !in_array($_POST["crday"], $Days)) {$CRONDay = "*";}
		else {$CRONDay = $_POST["crday"];}

		if (!isset($_POST["crhour"]) || !in_array($_POST["crhour"], $Hours)) {$CRONHour = "*";}
		else {$CRONHour = $_POST["crhour"];}

		if (!isset($_POST["crmin"]) || !in_array($_POST["crmin"], $Minuts)) {$CRONMin = "@30";}
		else {$CRONMin = $_POST["crmin"];}

		if (!isset($_POST["cractive"]) || !is_numeric($_POST["cractive"]) || !file_exists($SPUrl . $CRONFile)) {$CRONActive = "0";}
		else {$CRONActive = $_POST["cractive"];}

		$r=mysqli_query($hlnk, "UPDATE ".SQLPRFX."tasks SET Task_Year='".$CRONYear."', Task_Month='".$CRONMnth."',
		Task_Day='".$CRONDay."', Task_Hour='".$CRONHour."', Task_Minute='".$CRONMin."', Task_RunFile='".$CRONFile."',
		Task_Nazv='".$CRONName."', Task_Active='".$CRONActive."'
		WHERE Task_ID='".$CronID."';") or die("Update Cron :(");
		echo "<H2>".__("Сохранено")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."'\", 200);\n</SCRIPT>";
	break;

	case "cronstman":
		include("cronrun-man.php");
	break;

	case "del":
		if (!isset($_GET["crid"]) || !is_numeric($_GET["crid"])) {die("<SCRIPT>history.back();</SCRIPT>");}
		$CronID = $_GET["crid"];

		$r=mysqli_query($hlnk, "DELETE FROM ".SQLPRFX."tasks WHERE Task_ID='".$CronID."';") or die ("Del :(");
		$r=mysqli_query($hlnk, "OPTIMIZE TABLE ".SQLPRFX."tasks;") or die ("Opt :(");
		echo "<H2>".__("Удалено")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."'\", 200);\n</SCRIPT>";
	break;

	case "addnew":
 		$CRONName = str_replace("'", '', $_POST["crname"]);
		$CRONName = str_replace('"', '&quot;', $CRONName);

 		$CRONFile = str_replace("'", '', $_POST["crfile"]);
		$CRONFile = str_replace('"', '', $CRONFile);

		if (!isset($_POST["cryear"]) || ($_POST["cryear"] != "*" && !is_numeric($_POST["cryear"]))) {$CRONYear = "*";}
		else {$CRONYear = $_POST["cryear"];}

		if (!isset($_POST["crmonth"]) || !in_array($_POST["crmonth"], $Monthes)) {$CRONMnth = "*";}
		else {$CRONMnth = $_POST["crmonth"];}

		if (!isset($_POST["crday"]) || !in_array($_POST["crday"], $Days)) {$CRONDay = "*";}
		else {$CRONDay = $_POST["crday"];}

		if (!isset($_POST["crhour"]) || !in_array($_POST["crhour"], $Hours)) {$CRONHour = "*";}
		else {$CRONHour = $_POST["crhour"];}

		if (!isset($_POST["crmin"]) || !in_array($_POST["crmin"], $Minuts)) {$CRONMin = "@30";}
		else {$CRONMin = $_POST["crmin"];}

		if (!isset($_POST["cractive"]) || !is_numeric($_POST["cractive"]) || !file_exists($SPUrl . $CRONFile)) {$CRONActive = "0";}
		else {$CRONActive = $_POST["cractive"];}

		$res = mysqli_query($hlnk, "INSERT INTO ".SQLPRFX."tasks VALUES ('', '".$CRONYear."', '".$CRONMnth."', '".$CRONDay."', '".$CRONHour."', '".$CRONMin."', '".$CRONFile."', '".$CRONName."', '".$CRONActive."', '');") or die ("Insert Cron :(");
		echo "<H2>".__("Сохранено")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."'\", 200);\n</SCRIPT>";
	break;

	case "cronstparams":
		echo __("Сохранено")."
		<H3>Update path to config.php<H3>";

		$CrFile = file_get_contents(MODDIR."/cronrun.php");

		$ITOG = '//%%
include("'.$SPUrl.'config.php");
';
if (file_exists("srvservice/dbsrvcheck.php")) {
	$ITOG .= 'include($SPUrl.$SRVAdm."/srvservice/dbsrvcheck.php");';
}
$ITOG .= '
//%%';
		$Rezult = preg_replace("|//%%.*//%%|imsU", $ITOG, $CrFile);

		$ssf = "./".MODDIR."/cronrun.php";
		$ft = fopen($ssf, "w");
		fwrite($ft, $Rezult);
		fclose($ft);
		@chmod($ssf, 0755);
		echo "<H2>".__("Сохранено")."!</H2>\n<SCRIPT>\n\nvar i = setTimeout(\"window.location.href='".$ModURL."'\", 4000);\n</SCRIPT>";
	break;
}
?>