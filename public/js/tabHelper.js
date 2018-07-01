
try {
	$(document).ready(function() {
		colorTabs();
	});
} catch (e) {
	console.log (e.message);    //this executes if jQuery isn't loaded
}
	
	

function colorTabs() {
	//	console.debug('colorTabs');
	var counter = 0;
	$('#iep_form_004_goal_tab_container .dijitTabInnerDiv').each(
		function(divElement) {
			console.debug('divElement', divElement, $(this));
			if ($(this).attr('id') && $(this).attr('id').length < 1) {
				counter++;
				var errorCounter = 0;
				$('#iep_form_004_goal_' + counter + ' .errored').each(
						function() {
							errorCounter++;
						});

				if (errorCounter > 0)
					$(this).attr('style', 'background-color:#fbe6e6;');
				else
					$(this).attr('style', 'background-color:#fff;');

			}
		});
}
