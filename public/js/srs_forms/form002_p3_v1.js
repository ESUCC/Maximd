function toggleShowDevelopmentalAreas() {

	showDA = false;
	showHideId = "showDevelopmentalAreas";
    showHideDefaultId = "showEducationalNeedsText";

    try {
		// primary disability
        if($('#educationalneeds_ddview').is(":checked")) {
            showDA = true;
        }
		if(showDA) {
	        showHideAnimation(showHideDefaultId, 'hide');
			showHideAnimation(showHideId, 'show');
		} else {
	        showHideAnimation(showHideDefaultId, 'show');
			showHideAnimation(showHideId, 'hide');
		}
    } catch (error) {
    	//execute this block if error
    	console.debug('Error in toggleShowDevelopmentalAreas.');
    }

}

function toggleShowSLDAreas() {
    try {
		showDA = false;
		showHideId = "showSLDAreas";
		
		// primary disability
		disability = dojo.byId('disability_primary');
		if(disability.value == "SLD")
			showDA = true;
	
		//console.debug('showDA', showDA, disability.value);
		
		if(showDA) {
			showHideAnimation(showHideId, 'show');
		} else {
			showHideAnimation(showHideId, 'hide');
		}
    } catch (error) {
    	//execute this block if error
    	console.debug('Error in toggleShowSLDAreas.');
    }
	
}

function toggleShowMultiAreas() {
	console.debug('running toggleShowMultiAreas');
    try {

		showDA = false;
		showHideId = "showMultiAreas";
		
		// primary disability
		disability = dojo.byId('disability_primary');
		if(disability.value == "MULTI")
			showDA = true;
	
		//console.debug('showDA', showDA, disability.value);
		
		if(showDA) {
			showHideAnimation(showHideId, 'show');
		} else {
			showHideAnimation(showHideId, 'hide');
		}
    } catch (error) {
    	//execute this block if error
    	console.debug('Error in toggleShowMultiAreas.');
    }
	
}

//jquery
$().ready(function() {
	toggleShowDevelopmentalAreas();
	toggleShowSLDAreas();
	toggleShowMultiAreas();
	var previousValue = $( "#disability_primary" ).val();
	
	$( "#disability_primary" ).focus(function () {
        // Store the current value on focus, before it changes
		console.debug('store previous value:', previousValue);
		previousValue = $(this).val();
	})
    .change(function(){
    	if('SLD'!=$(this).val() && 'SLD' == previousValue) {
    		if((
    				!editorEmpty('mdt_00603f2')||
    				!editorEmpty('mdt_00603f2d')||
    				!editorEmpty('mdt_00603f2e')||
    				!editorEmpty('mdt_00603f2g')
    			)) {
    				$("<div>Selecting a disability other than SLD will cause the following textboxes to be permanently erased." +
    						"<ul>" +
    						"<li>A. Relevant behavior noted during observation</li>" +
    						"<li>B. Relationship of relevant behavior to the child&apos;s academic functioning</li>" +
    						"<li>C. Educationally relevant medical findings, if any</li>" +
    						"<li>F. Summarize the effects of environmental, cultural or economic disadvantages</li>" +
    						"</ul>" +
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
    							setEditorContents('mdt_00603f2', '');
    							setEditorContents('mdt_00603f2d', '');
    							setEditorContents('mdt_00603f2e', '');
    							setEditorContents('mdt_00603f2g', '');
    							toggleShowSLDAreas();
    							toggleShowMultiAreas();
    							previousValue = $(this).val();
    							$(this).dialog('close');
    							// do something
    			           },
    			           Cancel: function(){
    			        	   $( "#disability_primary" ).val('SLD');
    			        	   $(this).dialog('close');
    			           }
    			        }
    				});
    			}
    	} else {
	    	// after possible dialog, toggle based on current value
			toggleShowSLDAreas();
			toggleShowMultiAreas();
			previousValue = $(this).val();
    	}
		
	});
});






