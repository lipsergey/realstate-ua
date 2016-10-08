<?php
$userBlock = "";
$userLBL = "";

//Unlogin Process
if (isset($_GET["unl"]) && $_GET["unl"] == 1) {
	setcookie(COOKIEPREFIX."password", "", time() - 3600, "/");
	setcookie(COOKIEPREFIX."userid", "", time() - 3600, "/");
	die('<SCRIPT>window.location="/";</SCRIPT>');
}
//End of UnloginProcess

ini_set('session.use_trans_sid', 'off');
ini_set('session.use_cookies', 'on');

header ("Expires: ".gmdate("D, d M Y H:i:s") . " GMT");
header ("Last-Modified: ".gmdate("D, d M Y H:i:s") . " GMT");
header ("Keep-Alive: timeout=0, max=15");

$FormParts = array("", "", "");
$User = array();

//Ранее был авторизован, проверяем
if (isset($_COOKIE[COOKIEPREFIX."password"]) && isset($_COOKIE[COOKIEPREFIX."userid"])
&& is_numeric($_COOKIE[COOKIEPREFIX."userid"])) {

	$r = mysqli_query($hlnk, "SELECT uadm_fio, uadm_passw, lang_id, uadm_group, min_svalue, max_svalue,
	can_see_sale, can_see_comm_sale, can_see_rent, can_see_comm_rent
	FROM ".$ppt."ta_usrsadm_main
	WHERE uadm_id='".$_COOKIE[COOKIEPREFIX."userid"]."'".(($_COOKIE[COOKIEPREFIX."userid"] > 1) ? " AND is_active='1'" : "")." LIMIT 0,1;") or die ("Check login wrong :(");
	if (mysqli_num_rows($r) == 0) {
		setcookie(COOKIEPREFIX."password", "", time() - 3600, "/");
		setcookie(COOKIEPREFIX."userid", "", time() - 3600, "/");
	}
	else {
		$User = mysqli_fetch_assoc($r);

		if (md5($User["uadm_passw"] . COOKIE_SALT_CHECK) == $_COOKIE[COOKIEPREFIX."password"]) {
			$uzzName = $User["uadm_fio"];

			define("ADMUZERID", $_COOKIE[COOKIEPREFIX."userid"]);
			if (isset($User["lang_id"]) && is_numeric($User["lang_id"])) {define("USERLANG", $User["lang_id"]);}

			define("ADMGROUP", $User["uadm_group"]);
			define("ADMFILTERMIN", $User["min_svalue"]);
			define("ADMFILTERMAX", $User["max_svalue"]);
			define("ADMSEESALE", $User["can_see_sale"]);
			define("ADMSEECOMMSALE", $User["can_see_comm_sale"]);
			define("ADMSEERENT", $User["can_see_rent"]);
			define("ADMSEECOMMRENT", $User["can_see_comm_rent"]);

			setcookie(COOKIEPREFIX."password", $_COOKIE[COOKIEPREFIX."password"], time()+604800, "/");
			setcookie(COOKIEPREFIX."userid", $_COOKIE[COOKIEPREFIX."userid"], time()+604800, "/");

			$userLBL = 'User: '.$uzzName ." (".__($UserGrops[$User["uadm_group"]]).")";
			$userLBL .= ' <a href="index.php?unl=1">Exit</a>';
		}
		else {
			$FormParts[0] = __("TRNSL-AUTH-WLOGIN-WPASS");

			setcookie(COOKIEPREFIX."password", "", time() - 3600, "/");
			setcookie(COOKIEPREFIX."userid", "", time() - 3600, "/");
		}
	}
}

elseif (isset($_POST["login_username"]) && isset($_POST["login_password"])) {
	$ChkUL = str_replace("'", "", $_POST["login_username"]);
	$ChkUP = $_POST["login_password"];

	$r = mysqli_query($hlnk, "SELECT uadm_id, uadm_fio, uadm_passw, lang_id, uadm_group, min_svalue,
	max_svalue, can_see_sale, can_see_comm_sale, can_see_rent, can_see_comm_rent, is_active
	FROM ".$ppt."ta_usrsadm_main
	WHERE uadm_login='".$ChkUL."' LIMIT 0,1;") or die ("Check post auth wrong :(");
	if (mysqli_num_rows($r) == 0) {
		$FormParts[0] = __("TRNSL-AUTH-WLOGIN-WPASS");
	}
	else {
		$User = mysqli_fetch_assoc($r);

		if (md5($ChkUP) == $User["uadm_passw"]) {
			if ($User["uadm_id"] > 1 && $User["is_active"] == 0) {
				$FormParts[0] = __("TRNSL-AUTH-USER-LOCKED");
			}
			else {
				$uzzName = $User["uadm_fio"];
				define("ADMUZERID", $User["uadm_id"]);
				if (isset($User["lang_id"]) && is_numeric($User["lang_id"])) {define("USERLANG", $User["lang_id"]);}

				define("ADMGROUP", $User["uadm_group"]);
				define("ADMFILTERMIN", $User["min_svalue"]);
				define("ADMFILTERMAX", $User["max_svalue"]);
				define("ADMSEESALE", $User["can_see_sale"]);
				define("ADMSEECOMMSALE", $User["can_see_comm_sale"]);
				define("ADMSEERENT", $User["can_see_rent"]);
				define("ADMSEECOMMRENT", $User["can_see_comm_rent"]);

				setcookie(COOKIEPREFIX."password", md5($User["uadm_passw"] . COOKIE_SALT_CHECK), time()+604800, "/");
				setcookie(COOKIEPREFIX."userid", $User["uadm_id"], time()+604800, "/");
				$userLBL = 'User: '.$uzzName ." (".__($UserGrops[$User["uadm_group"]]).")";
				$userLBL .= ' <a href="index.php?unl=1">Exit</a>';
			}
		}
		else {
			$FormParts[0] = __("TRNSL-AUTH-WLOGIN-WPASS");
		}
	}

	$FormParts[1] = $ChkUL;
	$FormParts[2] = $ChkUP;
}

if (!defined("ADMUZERID") && !defined("NOTVIEWAUTH")) {
	$userBlock = '<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Authorization</title>
	<script src="js/jquery/jquery-3.1.0.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery/ui/jquery.ui.core.min.js"></script>
	<script type="text/javascript" src="js/jquery/ui/jquery.ui.widget.min.js"></script>
	<script src="js/jquery/jquery.tools.min.js" type="text/javascript"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
	<style>
	.input-group {margin: 20px;}
	</style>
	<body>
	<div class="container" style="width: 400px;">
	@TOPMESSAGE@
	<h2 class="form-signin-heading">'.__("TRNSL-AUTH-MAIN").'</h2>
	<form action="" method="post" class="form-signin">
	<div class="input-group">
		<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
		<input type="text" class="form-control" placeholder="Login" name="login_username" required autofocus value="@LOGINVALUE@" style="width: 300px;">
	</div>
	<div class="input-group">
		<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
		<input type="password" class="form-control" placeholder="Password" name="login_password" required value="@PASSWVALUE@" style="width: 300px;">
	</div>
	<input type=hidden name="getauth" value="4831">
	<button class="btn btn-lg btn-primary btn-block" type="submit">'.__("TRNSL-AUTH").'</button>
	</form>';
	if ($FormParts[0] != "") {$FormParts[0] = "<font color=red><h2>".$FormParts[0]."</h2></font>";}
	echo str_replace(array("@TOPMESSAGE@", "@LOGINVALUE@", "@PASSWVALUE@"), $FormParts, $userBlock);
}
?>