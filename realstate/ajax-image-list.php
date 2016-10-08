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

echo '
<link rel="stylesheet" href="css/jquery.fileupload.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<noscript><link rel="stylesheet" href="css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>
<link href="css/ekko-lightbox.css" rel="stylesheet">
<div class="container">
	<form id="fileupload" action="imgs/" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="objid" id="objid" value="'.$ObjID.'">
	<div class="row fileupload-buttonbar">
		<div class="col-lg-7">
			<!-- The fileinput-button span is used to style the file input field as button -->
			<span class="btn btn-success fileinput-button">
				<i class="glyphicon glyphicon-plus"></i>
				<span>Add files...</span>
				<input type="file" name="files[]" multiple>
			</span>
			<button type="submit" class="btn btn-primary start">
				<i class="glyphicon glyphicon-upload"></i>
				<span>Start upload</span>
			</button>
			<button type="reset" class="btn btn-warning cancel">
				<i class="glyphicon glyphicon-ban-circle"></i>
				<span>Cancel upload</span>
			</button>
			<button type="button" class="btn btn-danger delete">
				<i class="glyphicon glyphicon-trash"></i>
				<span>Delete</span>
			</button>
			<input type="checkbox" class="toggle">
			<!-- The global file processing state -->
			<span class="fileupload-process"></span>
		</div>
		<!-- The global progress state -->
		<div class="col-lg-5 fileupload-progress fade">
			<!-- The global progress bar -->
			<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
				<div class="progress-bar progress-bar-success" style="width:0%;"></div>
			</div>
			<!-- The extended global progress state -->
			<div class="progress-extended">&nbsp;</div>
		</div>
	</div>
	<!-- The table listing the files available for upload/download -->
	<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
</form>
<br>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Demo Notes</h3>
	</div>
	<div class="panel-body">
		<ul>
			<li>'.__("TRNSL-UPLOAD").' <strong>5 MB</strong>.</li>
			<li>'.__("TRNSL-UPLOAD-IMGS").' (<strong>JPG, GIF, PNG</strong>).</li>
			<li>'.__("TRNSL-UPLOAD-DRUG").'.</li>
			<li>'.__("TRNSL-UPLOAD-SPIS").'.</li>
		</ul>
	</div>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>


<script src="js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="js/canvas-to-blob.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/jquery.fileupload-image.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="js/main.js"></script>
<script src="js/ekko-lightbox.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
';


$r = mysqli_query($hlnk, "SELECT `Image_ID`, `ImageName`
FROM ".$ppt."relastate_gallery WHERE `Object_ID`='".$ObjID."';") or die ("Get spis of rieltors :(");
if (mysqli_num_rows($r) > 0) {
	echo '<div class="container"><div class="row">'."\n";
	while($tFotLst = mysqli_fetch_assoc($r)) {
		echo '<div class="col-lg-3 col-md-4 col-xs-6 thumb">
			<a href="imgs/objects/big/'.$tFotLst["ImageName"].'" data-gallery="global-gallery" data-parent="" data-toggle="lightbox">
				<figure>
					<img src="'.$SRVUrl.'imgs/objects/small/'.$tFotLst["ImageName"].'" class="img-responsive" alt="">
					<figcaption><a href="" class="glyphicon glyphicon-trash" onclick="javascript:if(confirm(\''.__("TRNSL-SURE-DEL").'?\')) {delImg('.$tFotLst["Image_ID"].');return false;} else{return false;}">&nbsp;</a></figcaption>
				</figure>
			</a>
		</div>'."\n";
		//				
	}
	echo '</div></div>';
}

echo '
<style>
figure figcaption {
    font-size: 22px;
    text-decoration: none;
    bottom: 5px;
	right: 30px;
    position: absolute;
}
.thumb {
    margin-bottom: 30px;
}
.glyphicon {
	 text-decoration: none !important;
}
</style>

<script>

$(document).ready(function ($) {
   // delegate calls to data-toggle="lightbox"
	$(document).delegate(\'*[data-toggle="lightbox"]\', \'click\', function(event) {
		event.preventDefault();
		return $(this).ekkoLightbox({
			onShown: function() {
			},
			onNavigate: function(direction, itemIndex) {
			}
		});
	});

}); 

function delImg(tIm) {
	$.ajax({
		url: "realstate/ajax-del-img.php?objid='.$ObjID.'&img="+tIm,
		cache: false,
		success: function(html){
			LoadImgTab();
		},
	});
}
</script>';


?>