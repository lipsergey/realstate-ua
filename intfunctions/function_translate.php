<?php
$TRTextArr = array();

function __($TxtKey) {
	global $hlnk, $ppt, $LangList, $TRTextArr;

	$tLangSQL = $LangList["1"]["sqlf"];
	if (defined("USERLANG") && is_numeric(USERLANG) && isset($LangList[USERLANG])) {
		$tLangSQL = $LangList[USERLANG]["sqlf"];
	}
	
	if (count($TRTextArr) == 0) {
		$r = mysqli_query($hlnk, "SELECT KeyText, `".$tLangSQL."`, `RusText` FROM ".$ppt."translate
		WHERE 1;") or die (mysqli_error()."<HR>Get translation :(");
		while($LngTrns = mysqli_fetch_row($r)) {
			$TRTextArr[$LngTrns[0]] = (($LngTrns[1] != "") ? $LngTrns[1] : $LngTrns[2]);
		}
	}

	return (isset($TRTextArr[$TxtKey]) ? $TRTextArr[$TxtKey] : $TxtKey);
}


?>