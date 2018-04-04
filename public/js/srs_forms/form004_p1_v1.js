dojo.require("dijit.form.DateTextBox");

/*
 * when conf date is altered, update all team_member rows with the date
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
	console.debug('toggleShowHideAbsenceApproval');
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

//dojo.addOnLoad(toggleShowHideAbsenceApproval);
$().ready(function() {

	/**
	 * autofill effect_to_date
	 * dreiss
	 */


$("#effect_from_date").change(function(){
	var fromDate = $(this).val();
	var toDate = new Date(Date.parse(fromDate));
	//toDate.setDate(toDate.getDate() + 364);
	toDate.setFullYear(toDate.getFullYear()+1);
	toDate.setDate(toDate.getDate()-1);
    var curr_day = toDate.getDate();
    var curr_month = toDate.getMonth() + 1; //Months are zero based
    var curr_year = toDate.getFullYear();
    var date = (curr_month + "/" + curr_day + "/" + curr_year);
	$("#effect_to_date").val(date);
});


	try {
		$("input:checkbox[id$='-absent']").change(function() {
		     if(this.checked) {
		         // do something when checked
		    	 $(this).attr('id'), $(this).closest('td').next().children('div').slideToggle('slow');
		     } else {
		    	 $(this).attr('id'), $(this).closest('td').next().children('div').slideToggle('slow');
		     }
		 });
		$.each($("input[type=checkbox][checked][id$='-absent']").filter(":visible"), function() {
			if(this.checked && $(this).attr('id') !='') {
		    	$(this).attr('id'), $(this).closest('td').next().children('div').slideToggle('slow');
		    }
		 });
	} catch (error) {
		console.debug('Error initiating toggleShowHideAbsenceApproval', error);
	}

    /**
     * set up date auto enters
     */
//    setDate2WhenDate1Changes('#effect_from_date', '#effect_to_date', 364);

});




function anyAbsence() {
	var absenceChecked = false;
	$('.absentCheckbox:checked').each(function(key, value) {
		//console.debug(key, $(this).is(':checked'));
		absenceChecked = true;
	});
	if(absenceChecked) {
		//console.debug('some absence checked');
		$('#showHideAbsenceApproval').show();
	} else {
		//console.debug('2');
		$('#showHideAbsenceApproval').hide();
	}
}
$().ready(function() {
	try {
		anyAbsence();
	} catch (error) {
		console.debug('Error initiating toggleShowHideAbsenceApproval', error);
	}
});
