function googlePasteCapture(id, args) {
	upperKey = 'V';
	lowerKey = 'v';
	
	var OS = $.client.os; // uses '/js/jquery_addons/jquery.client.js' added in layout.phtml
	var pasteKeyMatch = (args[0].charCode == upperKey.charCodeAt(0) || args[0].charCode == lowerKey.charCodeAt(0));
	var osAndModifierKeyMatch = (OS != 'Mac' && args[0].ctrlKey) || (OS == 'Mac' && args[0].metaKey);
	
	if(pasteKeyMatch && osAndModifierKeyMatch) {
		$('#'+id).addClass('processThruGoogle');
		return false;
	}
}

function processThroughGoogleAndSave(editorID, dataToProcess) {
	console.debug('editorID', editorID);
	
	// remove previous error messages
	$('#'+editorID).parent().parent().find('.editor-error-msg').remove();
	
	var hiddenEditorID = editorID.substring(0, editorID.length-7);
	
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
	
	if($('#'+editorID).hasClass('processThruGoogle')) {
		submitObj.sendToGoogle = true;
		
		// visual feedback for user
		// load processing icon and hide the editor
		$('#'+editorID).parent().before('<div class="ajax-loader">Processing your paste. <img src="/images/ajax-loader.gif" alt="loading" /></div> ').slideDown();
		$('#'+editorID).parent().slideUp(800);
		
		// hide the save buttons
		try{
			$("#page_navigation_controlbar").slideUp(200);
			$(".navButtons").slideUp(200);
		} catch (error) {
			console.debug('There was an error hiding the save and next buttons.');
		}
	}
	
    // send to server to be saved
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: '/gdata/process-editor/',
		data:submitObj,
		success: function(json) {
			var errorMsg = '<b>ERROR: </b>';
			var displayErr = false;
			
			//console.debug('editorHistorySuccess', json.editorHistorySuccess);
			//console.debug('googleProcessSuccess', json.googleProcessSuccess);
			
			// build error msg if saving to editor history failed
			if(true!=json.editorHistorySuccess) {
				errorMsg = errorMsg+'Editor history could NOT be saved.<br/>';
				displayErr = true;
			}
			
			if(true==json.googleProcessSuccess) {
				// make sure we don't process this field again  
				$('#'+editorID).removeClass('processThruGoogle');

				// update editor and corresponding hidden field
				dijit.byId(editorID).setValue(json.response);
	            $("#"+hiddenEditorID).val(json.response);
	            
	            
			} else {
				// build error msg if processing through google failed
				errorMsg = errorMsg+'There was an error filtering your paste.<br/>';
				displayErr = true;
			}
			
            // if processed through google, 
            // show the field and remove the processing icon
			if(submitObj.sendToGoogle) {
				// clear the editor message
				$('#'+editorID).parent().slideDown(800);
				$('#'+editorID).parent().parent().find('.ajax-loader').remove();
				
				try{
					$("#page_navigation_controlbar").slideDown(200);
					$(".navButtons").slideDown(200);
				} catch (error) {
					console.debug('There was an error showing the save and next buttons.');
				}
			}
			if(displayErr) {
				$('#'+editorID).parent().before('<div class="editor-error-msg">'+errorMsg+'</div> ').slideDown();
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

