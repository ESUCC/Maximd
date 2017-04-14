/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


function enableDisableTransitionPlan(tranPlanValue) {
	try {
		console.debug('transition_plan', tranPlanValue);
		if(tranPlanValue == 1) {
	//		dojo.byId("transition_plan").value = 0;
	//		transitionPlanValue = 0;
			dojo.byId("checkboxText").innerHTML = "Uncheck the box to the left to dissolve the Transition Plan.";
			dojo.byId("subformToChange").style.display = "block";
		} else {
	//		dojo.byId("transition_plan").value = 1;
	//		transitionPlanValue = 1;
			dojo.byId("checkboxText").innerHTML = "Set up a Transition Plan.";
			dojo.byId("subformToChange").style.display = "none";
		}
	} catch (error) {
		console.debug('javscript error in enableDisableTransitionPlan');
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

