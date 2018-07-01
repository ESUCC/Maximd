$().ready(function() {
	colorTabs();
});

function colorTabs() {
	var counter = 0;
	try {
		$('#ifsp_goals_tab_container .dijitTabInnerDiv').each(function() {
			if ($(this).attr('id').length < 1) {
				counter++;
				var errorCounter = 0;
				
				// does an error exist in the validation array
				$('#ifsp_goals_' + counter + ' .errored').each(function() {
					errorCounter++;
				});
				
				// is the goal met checkbox checked
				goal_met = $('#ifsp_goals_' + counter + '-goal_met').attr('checked');
	
				if (errorCounter > 0) {
					// red
					//console.debug('errorCounter', counter, errorCounter);
					$(this).attr('style', 'background-color:#fbe6e6;');
				} else if(goal_met) {
					// green
					//console.debug('goal_met', counter, goal_met);
					$(this).attr('style', 'background-color:#3EA99F;');
				} else {
					// white
					$(this).attr('style', 'background-color:#fff;');
				}
			}
		});
		
	} catch(error) {
		console.debug('There was an error while trying to color tabs.');
	}
	
}
