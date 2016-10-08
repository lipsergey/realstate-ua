/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';
    //var hash = window.location.hash;
    //hash && $('ul.nav-tabs a[href="' + hash + '"]').tab('show');
	var cururl = window.location.href;
	
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'imgs/',
		maxChunkSize: 1024*1024*2, //2 MB
		maxRetries: 100,
		retryTimeout: 500,
		downloadTemplateId: null,
		acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		stop: function (e) {
			LoadImgTab();
			//window.location.href=cururl+'#tabs-imgoperat';
		}
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );

	 // Load existing files:
	$('#fileupload').addClass('fileupload-processing');
	$.ajax({
		// Uncomment the following to send cross-domain cookies:
		//xhrFields: {withCredentials: true},
		url: $('#fileupload').fileupload('option', 'url'),
		dataType: 'json',
		context: $('#fileupload')[0]
	}).always(function () {
		$(this).removeClass('fileupload-processing');
	}).done(function (result) {
		//LoadImgTab();
		$(this).fileupload('option', 'done')
		.call(this, $.Event('done'), {result: result});
	});


});
