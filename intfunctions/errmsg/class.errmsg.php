<?php
class errmsg {
 var $_package='Printing Errors';
 var $_version=0.2;
 var $errforuser = "";

 function PrintErrorMsg($Message) {
  global $bodht;
  if (defined('SHP_VALID')) {
   echo $Message;
   die();
  }
  else {
   //Временное решение..... надо будет перейти к постоянному

   $this->errforuser = $Message;
   $bodht = $Message;
   include("htmlmak.php");
   die();
  }
 }

}
?>
