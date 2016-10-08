<?php
/*
 Ядро системы.
 Здесь производиться инициация всех основных модулей
*/

//error_reporting (55);
if (isset($_GET)) {
 while (list($name, $value)=each($_GET)) {
  $$name=$value;
 }
}
if (isset($_POST)) {
 while (list($name, $value)=each($_POST)) {
  $$name=$value;
 }
}
if(isset($_COOKIE)){
 while (list($name, $value)=each($_COOKIE)){
  $$name=$value;
 }
}

include("config.php");

//Connect to DB
$hlnk = mysqli_connect($shhost, $shuser, $shpass, $shname) or die("MySQL server not available. Please come back later");
$result = mysqli_query($hlnk, "SET NAMES utf8;");

$link = $hlnk; //обратная совместимость
include($SPUrl."/intfunctions/function_translate.php");

if (!defined("NOAUTH")) {include("userauth.php");}

//Инициация класса ядра и подключение всех дочерних функций

include($SPUrl."/intfunctions/functions_core.php");


registerPackage('errmsg');
registerPackage('textfunct');
registerPackage('coreparsers');

date_default_timezone_set('Europe/Moscow');

if(!loadLibs()) {die('Critical system error !');}

?>