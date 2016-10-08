<?php
echo "Manually start Crontab";

$MinAval = array(
	"0" => " AND (Task_Minute = '@10' OR Task_Minute = '@20' OR Task_Minute = '@30' OR Task_Minute = '0')",
	"10" => " AND (Task_Minute = '@10' OR Task_Minute = '10')",
	"20" => " AND (Task_Minute = '@10' OR Task_Minute = '@20' OR Task_Minute = '20')",
	"30" => " AND (Task_Minute = '@10' OR Task_Minute = '@30' OR Task_Minute = '30')",
	"40" => " AND (Task_Minute = '@10' OR Task_Minute = '@20' OR Task_Minute = '40')",
	"50" => " AND (Task_Minute = '@10' OR Task_Minute = '50')"
);

set_time_limit(1000);
define("STARTMAN", 1);

$hlnk = mysqli_connect($shhost, $shuser, $shpass, $shname) or die("MySQL server not available. Please come back later");
$result = mysqli_query($hlnk, "SET NAMES utf8;");

$MinutSQL = "";
$t = round(date("i")/10, 0)*10;

if (isset($MinAval[$t])) {$MinutSQL = $MinAval[$t];}

if ($MinutSQL != "") {
	$crontabspis = mysqli_query($hlnk, "SELECT Task_ID, Task_RunFile
	FROM ".$ppt."crontb_tasks
	WHERE Task_Active='1'
	AND UNIX_TIMESTAMP(DATE_FORMAT(Task_LastStart, '%Y-%m-%d %H:%i:00')) < UNIX_TIMESTAMP(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:00'))
	AND (Task_Year='*' OR Task_Year='".date("Y")."')
	AND (Task_Month='*' OR Task_Month='".date("n")."')
	AND (Task_Day='*' OR Task_Day='".date("j")."')
	AND (Task_Hour='*' OR Task_Hour='".date("G")."')
	".$MinutSQL."
	LIMIT 0, 20;") or die ("Task spis :(");
	if (mysqli_num_rows($crontabspis) == 0) {
		echo "<h2>No jobs to run</h2>";
	}

	while($TaskSpis = mysqli_fetch_assoc($crontabspis)) {
		if (file_exists($SPUrl . $TaskSpis["Task_RunFile"])) {
			include($SPUrl . $TaskSpis["Task_RunFile"]);
			$r=mysqli_query($hlnk, "UPDATE ".$ppt."crontb_tasks SET Task_LastStart=NOW() WHERE Task_ID='".$TaskSpis["Task_ID"]."';") or die("Update Cron :(");
		}
		else {
			$r=mysqli_query($hlnk, "UPDATE ".$ppt."crontb_tasks SET Task_Active='0' WHERE Task_ID='".$TaskSpis["Task_ID"]."';") or die("Deactiv :(");
		}
	}
}
die();
?>