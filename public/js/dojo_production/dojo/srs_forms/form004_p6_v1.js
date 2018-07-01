/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo.require("dijit.form.DateTextBox");
function enableDisableAccomodationsChecklist(value) {
	if(value == 1) {
		showHideAnimation('accomodations_checklist_container', 'show');
	} 
	else {
		showHideAnimation('accomodations_checklist_container', 'hide');
	}
}

function otherHelper(menuValue, value) {
	try {
		currentVal = dijit.byId('accomodations_checklist_1-other').getValue();
		if('q' == menuValue) {
			if('' == currentVal || '<br />' == currentVal || '<br _moz_editor_bogus_node="TRUE" />' == currentVal) {
				dijit.byId('accomodations_checklist_1-other').setValue(value);
			} else {
				dijit.byId('accomodations_checklist_1-other').setValue(currentVal + "<BR />" + value);
			}
		}
	} catch (error) {
		console.debug('javscript error in otherHelper');
	}
	
}

function toggleShowHideMips() {
	try {
		showMips = false;
		mipsId = "showHideMips";
		
		// primary disability
		disability = dojo.byId('primary_disability_drop');
		if(disability.value == 'Occupational Therapy Services' || 
				disability.value == 'Physical Therapy' || 
				disability.value == 'Speech-language therapy')
			showMips = true;
	
		// related disability
		dojo.query("#related_service_drop-colorme select").forEach(
		    function(selectTag) {
		    	if(getNodeIdEndsWith(selectTag, 'related_service_drop'))
		    	{
		    		if(selectTag.value == 'Occupational Therapy Services' || 
		    				selectTag.value == 'Physical Therapy' || 
		    				selectTag.value == 'Speech-language therapy')
	                {
						showMips = true;
	                }
		    	}
		    }
		);
		if(showMips) {
			showHideAnimation(mipsId, 'show');
		} else {
			showHideAnimation(mipsId, 'hide');
		}
	} catch (error) {
		console.debug('javscript error in toggleShowHideMips');
	}

}

//
// toggle mips section based on the value of primary_disability_drop
function firstLoadShowHideMips(){
	toggleShowHideMips();
	console.debug('after');
}

function override(id, checkedValue)
{
	if(checkedValue) 
	{
		var answer = confirm("Checking Not Required will permanently clear out all data in that section. Do you wish to proceed?")
		if (answer){
			//console.debug('override', id, checkedValue);
			dash = id.indexOf('-');
			subformName = id.substring(0, dash);
			dojo.query('input[id^="'+subformName+'"][type=checkbox][id$="remove_row"]').forEach(
			    function(selectTag) {
			    	console.debug('found', selectTag);
			    	selectTag.checked = true;
			    }
			);
			dojo.byId(subformName+'_parent').style.display = 'none';
		}
	}
	
}

dojo.addOnLoad(firstLoadShowHideMips);
// dojo.addOnLoad(firstLoadHideAccomodationsChecklist);
