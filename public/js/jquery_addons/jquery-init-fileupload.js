/*
 * jQuery File Upload Plugin JS Example 5.0.3
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://creativecommons.org/licenses/MIT/
 * 
 * 
 * https://github.com/blueimp/jQuery-File-Upload/wiki/Options
 * 
 * 
 */

/*jslint nomen: true */
/*global $ */

$(function () {
    'use strict';
    // build uploader url
    
    var formNum = $("#form_number").val();
    var uploaderUrl = "/Jfile-upload/upload/form_number/"+formNum+"/document/"+$("#id_form_"+formNum).val();
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
    	url:uploaderUrl,
        add: function (e, data) {
            try {
            	//console.debug('modified');
            	modified();
            } catch (error) {
            	//console.debug('blockIU not loading');
            }
            //console.debug('added', e, data);
            data.submit();
        },
        acceptFileTypes: /(\.|\/)(pdf)$/i
    });
    
    $('#fileupload button.cancel').hide();
    $('#fileupload button.start').hide();
    $('#fileupload button.delete').hide();
    
    if('edit'!=$("#mode").val()) {
//    	$('#fileupload').fileupload('disable'); // meh, erases file names in ie
    	$('.fileupload-buttonbar label').hide(); // hide the add button
    	$('#fileupload button').hide(); // hide other buttons in toolbar	
    }

    $.getJSON(uploaderUrl, function (files) {
        var fu = $('#fileupload').data('fileupload');
        fu._adjustMaxNumberOfFiles(-files.length);
        fu._renderDownload(files)
            .appendTo($('#fileupload .files'))
            .fadeIn(function () {
                // Fix for IE7 and lower:
                $(this).show();
                if('edit'!=$("#mode").val()) {
                	$('#fileupload button').hide(); // hide delete icon on file rows	
                }

            });
    });

    // Open download dialogs via iframes,
    // to prevent aborting current uploads:
    $('#fileupload .files').delegate(
        'a:not([target^=_blank])',
        'click',
        function (e) {
            e.preventDefault();
            $('<iframe style="display:none;"></iframe>')
                .prop('src', this.href)
                .appendTo('body');
        }
    );

});