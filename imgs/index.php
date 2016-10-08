<?php
include("../core.php");
error_reporting(E_ALL | E_STRICT);

addNewLibs('fileresizeimagick');

require('UploadHandler.php');
$upload_handler = new UploadHandler();
?>