function enableDisableTransitionPlan(tranPlanValue) {
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
}

function attachChangeSubform() {
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
	
}

