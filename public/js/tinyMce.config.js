function loadDataForReplaceFunctions(id) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/api-student/get-info-for-replace/id/'+id,
        success: function(json) {
            if(json.success) {
                window.studentData = json.data[0];
            } else {
                window.studentData = false;
            }
        },
        error: function(json) {
            window.studentData = false;
        }
    });
}

function initTinyMce(jqueryObjects) {
	console.debug('initTinyMce', jqueryObjects);
    var studentData = loadDataForReplaceFunctions($('#id_student').val())

	if ($('.jTabContainer').length) {
		instancesToSpawn = jqueryObjects.length;
	} else {
		instancesToSpawn = 0;
	}
	jqueryObjects.each(function(i, DOMelem) {
		$(this).tinymce({
			// Location of TinyMCE script
			script_url : '/js/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,mce_history,|,formatselect,removeformat,code",//,|,justifyleft,justifycenter,justifyright,justifyfull
		    theme_advanced_buttons2 : "",
		    theme_advanced_buttons3 : "",
			theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_resizing : true,
            theme_advanced_statusbar_location: "none",

            valid_styles : {'*' : 'color,text-decoration,border,border,border-style,border-width'},
			forced_root_block : "",

			// add conditional css file from above
			content_css : '/css/tiny_mce_view.css?' + new Date().getTime(),

			/*
			 * configure plugins
			 */
			plugins : "paste,autoresize",
			verify_css_classes : true,
			width : "100%",

			/*
			 * paste plugin
			 */
			paste_auto_cleanup_on_paste : true,

            paste_preprocess : function(pl, o) {
                // Content string containing the HTML from the clipboard
                if (o.content.indexOf("XXXX") >= 0) {
                    $( "<div>Would you like to replace \"XXXX\" with the student's first name?</div>" ).dialog({
                        resizable: false,
                        height:140,
                        modal: true,
                        buttons: {
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            },
                            "Replace": function() {
                                $( this ).dialog( "close" );
                                o.content = o.content.replace(/XXXX/g, window.studentData.name_first);
                                var editor = tinymce.get(pl.editor.editorId);
                                editor.setContent(o.content);
                                $('#' + pl.editor.editorId).tinymce().save();

                                if (o.content.indexOf("YYYY") >= 0) {
                                    $( "<div>Would you like to replace \"YYYY\" with the student's last name?</div>" ).dialog({
                                        resizable: false,
                                        height:140,
                                        modal: true,
                                        buttons: {
                                            Cancel: function() {
                                                $( this ).dialog( "close" );
                                            },
                                            "Replace": function() {
                                                $( this ).dialog( "close" );
                                                var newContent = o.content.replace(/YYYY/g, window.studentData.name_last);
                                                var editor = tinymce.get(pl.editor.editorId);
                                                editor.setContent(newContent);
                                                $('#' + pl.editor.editorId).tinymce().save();
                                            }
                                        }
                                    });
                                }


                            }
                        }
                    });
                }
            },

            setup : function(ed) {
				ed.onClick.add(function(ed, evt) {
					colorMeById(ed.editorId);
					modified('', '', '', '', '', '');
				});

				ed.onInit.add(function(ed) { blurevent(ed); });

				// Add a custom button
		        ed.addButton('mce_history', {
		           title : 'History',
		           image : '/images/History.png', // it'll write the title in if it cant find this file
                    // fails in IE9
		           onclick : function() {
		        	   editorHistoryDisplay(ed.editorId);
		           }
		        });


			}
		});
	});
    console.debug('initTinyMce over');
}
function editorHistoryDisplay(fieldName) {
	var formNum = dojo.byId('form_number').value;
	linkString = 'http://'+window.location.host+'/editor-history/display/formnum/'+parseFloat(formNum);
	linkString += '/id/'+dojo.byId('id_form_'+formNum).value;
	linkString += '/field/'+fieldName;
	window.open(linkString,'_newtab');
}

function blurevent(inst) {
    tinyMCE.dom.Event.add(inst.getWin(), "blur", function (e) {

        // save to background field
        $('#' + inst.editorId).val(tinyMCE.activeEditor.getContent()); // dirty hack to fix ajax requested page to save first time
        $('#' + inst.editorId).tinymce().save();

        // save to server
        tinyMceSaveToEditorHistory(inst.editorId, tinyMCE.activeEditor.getContent());

        // make sure editor shows it's modified
        // and enable the save buttons
        colorMeById(inst.editorId);
        modified('', '', '', '', '', '');
    });
}
/*
 * init all editors
 */
var initializedInstances = 0;
var instancesToSpawn = -1;
$(function() {
	/*
	 * init tab containers not inside jTabContainers
	 */
//	if (0 == $(".jTabContainer").length) {
		initTinyMce($('div.tinyMceEditor textarea').not('.jTabContainer div.tinyMceEditor textarea'));
//	}
});


function tinyMceSaveToEditorHistory(editorID, dataToProcess) {
	
	// build object to submit to server
	submitObj = new Object();
	submitObj.data = dataToProcess;
	submitObj.id_editor = editorID;
    try {
    	submitObj.form_number = $("#form_number").val();
    	submitObj.id_form = $("#id_form_"+submitObj.form_number).val();
    } catch (error) {
    	//execute this block if error
    	console.debug('There was an error getting the form id');
    }
	
	// hide the save buttons
//	try{
//		$("#page_navigation_controlbar").slideUp(200);
//		$(".navButtons").slideUp(200);
//	} catch (error) {
//		console.debug('There was an error hiding the save and next buttons.');
//	}
	
    // send to server to be saved
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: '/editor-history/save-to-editor-history/',
		data:submitObj,
		success: function(json) {
			var errorMsg = '<b>ERROR: </b>';
			var displayErr = false;
			// build error msg if saving to editor history failed
			if(true!=json.editorHistorySuccess) {
				errorMsg = errorMsg+'Editor history could NOT be saved.<br/>';
				displayErr = true;
			}
						
			if(displayErr) {
				console.debug('error', errorMsg);
				$('#'+editorID).parent().before('<div class="editor-error-msg">'+errorMsg+'</div> ').slideDown();
//				$("#page_navigation_controlbar").slideDown(200);
//				$(".navButtons").slideDown(200);
			} else {
				$('#'+editorID).parent().parent().find('.editor-message').html('Editor successfully processed.');
				var slideUp = setInterval(function(){
			        $('#'+editorID).parent().parent().find('.editor-message').html('');
			        clearInterval(slideUp);
			    }, 2000);
			}
		}
	});
	
}