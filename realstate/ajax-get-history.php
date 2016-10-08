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

$AllUsers = array();
$r = mysqli_query($hlnk, "SELECT `uadm_id`, `uadm_fio`
FROM ".$ppt."ta_usrsadm_main WHERE 1 ORDER BY BINARY(`uadm_fio`);") or die ("Get spis of rieltors :(");
while($tRielt = mysqli_fetch_assoc($r)) {
	$AllUsers[$tRielt["uadm_id"]] = $tRielt["uadm_fio"];
}

$r = mysqli_query($hlnk, "SELECT ChangeDateTime, EditAutor, ChangeField, ChangeWas, ChangeNew
FROM ".$ppt."realstate_changes_log WHERE Object_ID='".$ObjID."' ORDER BY ChangeDateTime DESC
LIMIT 0,20;") or die ("Get object info :(");
if (mysqli_num_rows($r) > 0) {
	echo "<table id=tabcont class=\"table table-bordered\" style=\"width: auto; margin-left:10px;\">
	<tr style=\"background: silver; text-align:center;\">
		<th>Date/time</th>
		<th>Author</th>
		<th>Field</th>
		<th>Was</th>
		<th>Now</th>
	</tr>\n";
	while($RLog = mysqli_fetch_assoc($r)) {
		echo "<tr>
			<td>".date("d.m.y h:i:s", $RLog["ChangeDateTime"])."</td>
			<td>".(isset($AllUsers[$RLog["EditAutor"]]) ? $AllUsers[$RLog["EditAutor"]] : "")."</td>
			<td>".__($RLog["ChangeField"])."</td>
			<td>".$RLog["ChangeWas"]."</td>
			<td>".$RLog["ChangeNew"]."</td>
		</tr>\n";
	}
	echo "</table>";
}
?>