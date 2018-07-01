/*
 * save progression
 * 
 * fn: attachSaveAction
 * 	fn: savePageDeferred
 *   fn: conditionalSave
 *    fn: save
 *     ---success: saveCallback
 *     ---failure: saveError
 * 
 * 
 * new jSave
 * 
 * attachJSave
 * 
 */
try {
	var jSaveFocused = 'mouseout';
	$(document).ready(function() {
		attachJSave();
	});
} catch (e) {
	console.debug (e.message);    //this executes if jQuery isn't loaded
}

function attachJSave() {
	
	// hide the other save buttons
	$("#submitButton").hide();
	$("#submitButton2").hide();
	$("#submitButton3").hide();
	
	
	/*
	 * get the form number - this is the type of form (001, 004, etc)
	 */
	var form_num = $("#form_number").val();
	if(undefined==form_num) {
		//throw "There was an error attaching the save function in jsave.js. Form number not found.";
		console.debug("There was an error attaching the save function in jsave.js. Form number not found.");
		return false;
	}
	
	/*
	 * attach save functionality to all desired buttons
	 */
	var buttonList = $('.jsavebutton');
	if(0==buttonList.size()) {
//		throw "There was an error attaching the save function in jsave.js. No save buttons found to attach to.";
		console.debug("There was an error attaching the save function in jsave.js. No save buttons found to attach to.");
		return false;
	}
	buttonList.click(function(evt){
		try {
			savePageDeferred(evt, "/form"+form_num+"/jsonupdateiep");
		} catch (e) {
			console.debug (e.message);
		}
		
	}).mouseover(function(evt){
		jSaveFocused = 'mouseover';
	}).mouseout(function(evt){
		jSaveFocused = 'mouseout';
	});
	
	return true;
}

