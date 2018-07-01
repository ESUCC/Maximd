/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/




function toggleShowDevelopmentalAreas() {

	showDA = false;
	showHideId = "showDevelopmentalAreas";
    showHideDefaultId = "showEducationalNeedsText";

    try {
		// primary disability
		disability = dojo.byId('educationalneeds_ddview');
		if(disability.checked)
			showDA = true;
	
		//console.debug('showDA', showDA, disability.value);
		
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
}

function toggleShowMultiAreas() {

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
}

function firstLoad(){
//	console.debug('firstLoad');
	// first load functionality transferred to conditional css in the view script
	setTimeout(function() { 
		toggleShowDevelopmentalAreas();
		toggleShowSLDAreas();
		toggleShowMultiAreas();
	}, 5000);

    try {
    	var mode = dojo.attr('mode', 'value');
		if('view' == mode && dojo.byId('mdt_00603e2a_charlen')) {
			dojo.byId('mdt_00603e2a_charlen').innerHTML = dojo.attr('mdt_00603e2b', 'value').length; 
		}
    } catch (error) {
    	//execute this block if error
    }
	
}
dojo.addOnLoad(firstLoad);




//jquery
$().ready(function() {

	$( "#disability_primary" ).focus(function () {
        // Store the current value on focus, before it changes
        previousValue = $(this).val();
        console.debug('store', previousValue);
	})
    .change(function(){
    	if('SLD'==$(this).val()) {
			toggleShowSLDAreas();
			toggleShowMultiAreas();
			previousValue = $(this).val();
    	} else if('SLD' == previousValue) {
    		if((
    				editorEmpty('mdt_00603f2')||
    				editorEmpty('mdt_00603f2d')||
    				editorEmpty('mdt_00603f2e')||
    				editorEmpty('mdt_00603f2g')
    			)) {
    				$("<div>Selecting a disability other than SLD will cause the following textboxes to be permanently erased." +
    						"<ul><li>A. Relevant behavior noted during observation</li>" +
    						"<li>B. Relationship of relevant behavior to the child&apos;s academic functioning</li>" +
    						"<li>C. Educationally relevant medical findings, if any</li>" +
    						"<li>F. Summarize the effects of environmental, cultural or economic disadvantages</li>" +
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

    	}
		
	});
});






