function enableDisableTransitionPlan(tranPlanValue) {
	console.debug('enableDisableTransitionPlan', tranPlanValue);
	
	if('tinyMce'==$('input:radio[name=form_editor_type]:checked').val()) {
		console.debug('tiny');
		try {
			if(tranPlanValue == 1) {
				$('#checkboxText').html("Uncheck the box to the left to dissolve the Transition Plan.");
				$('#subformToChange').css('display', 'block');
			} else {
				$('#checkboxText').html("Set up a Transition Plan.");
				$('#subformToChange').css('display', 'none');
			}
		} catch (error) {
			console.debug('javscript error in enableDisableTransitionPlan');
		}
	} else {
		console.debug('dojo');
		try {
			if(tranPlanValue == 1) {
				$('#checkboxText').html("Uncheck the box to the left to dissolve the Transition Plan.");
				$('#subformToChange').css('display', 'block');
				tranSave(null, null, "/form004/jsonupdateiep")
			} else {
				$('#checkboxText').html("Set up a Transition Plan.");
				$('#subformToChange').css('display', 'none');
			}
		} catch (error) {
			console.debug('javscript error in enableDisableTransitionPlan');
		}
	}
	
}

function attachChangeSubform() {
	try {
		dojo.connect(transition_plan, "onclick", function() {
			console.debug('transition_plan', transition_plan);
			if(transitionPlanValue == 1) {
				dojo.byId("transition_plan").value = 0;
				transitionPlanValue = 0;
				dojo.byId("checkboxText").innerHTML = "Uncheck the box to the left to dissolve the Transition Plan.";
				dojo.byId("subformToChange").style.visibility = "visible";
			} else {
				dojo.byId("transition_plan").value = 1;
				transitionPlanValue = 1;
				dojo.byId("checkboxText").innerHTML = "Set up a Transition Plan.";
				dojo.byId("subformToChange").style.visibility = "hidden";
			}
		});
	} catch (error) {
		console.debug('javscript error in attachChangeSubform');
	}

}




function tranSave(event, wait2finish, url) {
	/*
	 * remove when tinyMce is in place
	 */
	$("#jsubmit-header").focus(); // be sure to exit all fields.focus on the submit button
	$("#jsubmit-header").blur();
    try {
		// display the saving dialog
		// this does not display if wait2finish is true -- not sure this is true
		$.blockUI({ 
			message: 'Your page is saving...'
		});

    } catch (error) {
    	console.debug('blockIU not loading');
    }
	
	//Stop the submit event since we want to control form submission. 
	if(event != null) event.preventDefault();
	if(event != null) event.stopPropagation();

    // stop checkout timer
    ClearCountDown('clock1', 'Saving Form');
    
	// fade the navigation toolbar so user cannot 
	// navigate while page is saving
    fadeNode('page_navigation_controlbar', 'out');
    
    var xhrArgs = {
        form: dojo.byId("myform"),
        handleAs: "json",
        url: url,
        sync: false,
        load: tranSaveCallback,
        error: tranSaveError
    };
    dojo.publish("/form_timer", ["saving"]);
    
    dojox.html.set(dojo.byId("serverInteractionProgressMsg"), 'Form being saved....');
    fadeNode('serverInteractionProgressMsg', 'in');
    
    dojox.html.set(dojo.byId("response"), 'Form being sent...');
    
    dojo.byId('returnResult').value = 'connecting'; // element defined in edit.phtml

    //Call the asynchronous xhrPost
    dojo.xhrPost(xhrArgs);

}
function tranSaveCallback(data) {
	location.reload(true);
}
function tranSaveError(data) {
	console.debug('there was an error saving page 5');
}
