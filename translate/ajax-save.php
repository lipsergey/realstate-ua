<?php
include("../core.php");

if (!isset($_GET["refid"]) || $_GET["refid"] == "") {die("<h3>Error: no ID of edited entry</h3>");}
if (!isset($_GET["type"]) || !is_numeric($_GET["type"])) {die("<h3>Error: no type of edited entry</h3>");}
if (!isset($_GET["refvalue"])) {die("<h3>Error: no value of edited entry</h3>");}

$RefID = str_replace(array("'", '"'), array('', ''), $_GET["refid"]);
if (!isset($LangList[$_GET["type"]])) {die("<h3>Error: can't found langpack by ID: ".$_GET["type"]."</h3>");}

$RefVal = str_replace(array("'", '"'), array('', '&quot;'),  $_GET["refvalue"]);

$r = mysqli_query($hlnk, "UPDATE ".$ppt."translate SET `".$LangList[$_GET["type"]]["sqlf"]."`='".$RefVal."' WHERE `KeyText`='".$RefID."';") or die ("Update value in cat :( ".mysqli_error($hlnk));

?>