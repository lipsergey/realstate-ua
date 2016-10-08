<?php
if ($RefVal != 1 && $RefVal != 2) {
	$RefVal = 1;
}

$CTyp = array(
	1 => "TRNSL-SEE-SALE",
	2 => "TRNSL-SEE-RENT",
);

$Was = __($CTyp[$ObjOldInfo["ContractType"]]);
$Now = __($CTyp[$RefVal]);

?>