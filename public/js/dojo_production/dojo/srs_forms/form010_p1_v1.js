/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/



function fillFromPreviousReport(measurement, explain, sufficient, comment, goalNum){
// 	console.debug('fillFromPreviousReport');
// 
// 	console.debug('measurement', measurement);
// 	console.debug('explain', explain);
// 	console.debug('sufficient', sufficient);
// 	console.debug('comment', comment);
// 
    

    // radio button
    if(1 == sufficient) {
        dojo.byId('goal_progress_'+goalNum+'-progress_sufficient-1').checked = "checked";
    } else if(0 == sufficient) {
        dojo.byId('goal_progress_'+goalNum+'-progress_sufficient-0').checked = "checked";
    }
    dojo.byId('goal_progress_'+goalNum+'-progress_measurement').value = measurement;
    toggleShowOnMatchById(measurement, 'D', 'show_hide_progress_measurement_explain')
    
    dijit.byId('goal_progress_'+goalNum+'-progress_measurement_explain').attr("value", explain);
    updateInlineValueTextArea('goal_progress_'+goalNum+'-progress_measurement_explain', explain);
//    dijit.byId('goal_progress_'+goalNum+'-progress_measurement_explain').onChange();
    
    dijit.byId('goal_progress_'+goalNum+'-progress_comment').attr("value", comment);
    updateInlineValueTextArea('goal_progress_'+goalNum+'-progress_comment', comment);
    //dijit.byId('goal_progress_'+goalNum+'-progress_comment').onChange();    
    
//    onchange();

    
    return;
}

function prHelperRedirect(id_goto_form010, id_goto_student){

// 	console.debug('id_goto_student', id_goto_student);
// 	console.debug('id_goto_form010', id_goto_form010);

    var form_num = dojo.byId("form_number").value;
    
    dojo.byId('goto_id_form_010').value = id_goto_form010;
    dojo.byId('goto_id_student').value = id_goto_student;
    
    if('edit' == dojo.byId('mode')) {
    	save(null, true, "/form"+form_num+"/jsonupdateiep");
    }
    window.location.href="/form"+form_num+"/edit/document/"+id_goto_form010+"/page/1";
    
    return;
}
function setupClearEditorsOnChangeFromOther() {
    try {
    	// attach to each of the selects
        for (var goalNum = 1; goalNum<30; goalNum++){
        	if($("#goal_progress_"+goalNum+"-progress_measurement").length > 0) {
        		// select exists - attach onchange
        		$("#goal_progress_"+goalNum+"-progress_measurement").focus(function () {
    		        // Store the current value on focus, before it changes
    		        previousValue = $(this).val();
    			}).change(function(){
        	    	if('D'==$(this).val()) {
        	    		var partsArray = $(this).attr('name').split('[');
        	    		var prefix = partsArray[0];
        	    		// show the hidden text area
        	    		previousValue = $(this).val();
        	    		toggleShowOnMatchByIdSubform($(this).val(), 'D', 'show_hide_progress_measurement_explain', prefix+"-progress_measurement");
        	    		
        	    	} else if('D' == previousValue) {
        	    		var partsArray = $(this).attr('name').split('[');
        	    		var prefix = partsArray[0];
        	    		if(!editorEmpty(prefix+'-progress_measurement_explain')) {
    	    				$("<div>Selecting an option other that 'Other Specify' will cause the 'If Other, explain' textboxes to be permanently erased." +
    							"<p>Do you wish to continue?</p></div>").dialog({
    							height:300,
    							width: 500,
    					        modal: true,
    					        title: 'Erase Textboxes',
    					        overlay: {
    					           backgroundColor: '#000',
    					           opacity: 0.5
    					        },
    					        buttons: {
    								'Proceed?': function(){
    									setTextareaContents(prefix+'-progress_measurement_explain', '');
    									toggleShowOnMatchByIdSubform($(this).val(), 'D', 'show_hide_progress_measurement_explain', prefix+"-progress_measurement");
    									$(this).dialog('close');
    					           },
    					           Cancel: function(){
    					        	   $("#"+prefix+"-progress_measurement").val('D');
    					        	   $(this).dialog('close');
    					           }
    					        }
    						});
        	    		} else {
        	    			previousValue = $(this).val();
        	    		}
        	    	}
    			});
        	}
        }//end for
        return true;
    } catch (error) {
    	//execute this block if error
    	console.debug('There was an error attaching the setupClearEditorsOnChangeFromOther function to the select menus on this Progress Report.');
    	return true;
    }
}

//jquery
$().ready(function() {
	setupClearEditorsOnChangeFromOther();
});


