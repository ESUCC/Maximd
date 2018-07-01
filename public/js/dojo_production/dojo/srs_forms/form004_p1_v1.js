/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
This is the dojo directory
/etc/httpd/srs-zf/public/js/dojo_production/dojo/srs_forms
*/


dojo.require("dijit.form.DateTextBox");

/*
 * AAA when conf date is altered, update all team_member rows with the date
 */
function updateDateConference(arg) { 
    $('.participant-names').each(function() { 
        if ($(this).val().length >= 2)
            $('#' + $(this).attr('id') + '-conference-date').html($('#date_conference').val());
    });

    /*
	var foundDateFields = dojo.query("span.page1_date_conference");
	dojo.forEach(foundDateFields, function(node){
		console.debug('refresh', node.innerHTML, node.length);
		if('' != node.innerHTML) {
			node.innerHTML = dijit.byId('date_conference').attr('displayedValue');
		}
		
		if(' ' == node.innerHTML) {
			console.debug('one space');
		}
		if('  ' == node.innerHTML) {
			console.debug('3 space');
		}
		if('\n				' == node.innerHTML) {
			console.debug('3 space');
		}

	});

    */
}

function toggleShowHideAbsenceApproval() {
//	console.debug('toggleShowHideAbsenceApproval');
	show = false;
	showId = "showHideAbsenceApproval";
	
	var showAbsencesApproved = false;
	// related disability
	dojo.query("input[type=checkbox]").forEach(
	    function(selectTag) {
	    	if(getNodeIdEndsWith(selectTag, '-absent'))
	    	{
	    		//console.debug(selectTag, selectTag.value);
//	    		console.debug('toggleShowHideAbsenceApproval', selectTag.id, selectTag.checked);
	    		subformShowHideField(selectTag.id, 'absent_reason', selectTag.checked, '1');
	    		if(selectTag.checked) {
	    			showAbsencesApproved = true;
	    		}
	    	}
	    }
	);
//	console.debug('showAbsencesApproved', showAbsencesApproved);
	if(showAbsencesApproved) {
		showHideAnimation('showHideAbsenceApproval', 'show');
	} else {
		showHideAnimation('showHideAbsenceApproval', 'hide');
	}
}

function clearParentSigSectionRadioButtons(evt)
{	
	//
	// clear radio buttons on page 1 of IEP
	// values are not currently cleared, just the display
	//
	dojo.query("input[name=necessary_action]").forEach(
		    function(selectTag) {
		    	selectTag.checked = false;
		    }
		);
	dojo.query("input[name=received_copy]").forEach(
		    function(selectTag) {
		    	selectTag.checked = false;
		    }
		);
	dojo.query("input[name=doc_signed_parent]").forEach(
		    function(selectTag) {
		    	selectTag.checked = false;
		    }
		);
	dojo.query("input[name=absences_approved]").forEach(
		    function(selectTag) {
		    	selectTag.checked = false;
		    }
		);
	//dojo.byId('clearRadioButtons').value = 1;
	// saving of page required
//		console.debug('saving of page required');
	enableSave();

	var form_num = dojo.byId("form_number").value;
    var formID = dojo.byId('id_form_'+form_num).value;
	save(evt, false, "/form"+form_num+"/jsonupdateiep");
}

dojo.addOnLoad(toggleShowHideAbsenceApproval);
