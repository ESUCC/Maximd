
function colorSuppTabs(validationMsgs){
	var processedKeys = new Array();
	
	var tabContainerPrefix = "form_002_suppform_"
	
	// make all tabs have black text
	var tabNumber = dijit.byId(tabContainerPrefix+'tab_container').tablist.getChildren().length - 1;
	for(i=0;i<=tabNumber;i++)
	{
		colorTabText(tabContainerPrefix+'tab_container', i, 'grey');
	}
	
	// color invalid tabs red
//	try {
	if(undefined != validationMsgs) {
		dojo.forEach(validationMsgs, // loop through returned errors
	        function(validationArr) {
		    	var match = /form_002_suppform_(.+)-/i.exec(validationArr['field'])
		    	if(match[1] > 0 && !processedKeys[match[1]]) 
		    	{
		    		colorTabText(tabContainerPrefix+'tab_container', (match[1]-1), 'red');
	//	    		console.debug('here');
	//	    		setTabText(tabContainerPrefix+'tab_container', (match[1]-1), 'tet');
		    		processedKeys[match[1]] = 1;
		    	}
	        }
	    );
	}
//	} catch (error) {
//		console.debug('catch failed colorSuppTabs - probably validationMsgs not defined');
//	}
}

function setSuppTabTitles()
{
	try {
		// set tab titles based on form element data
		rowCount = dojo.byId("form_002_suppform-count").value;
		//console.debug('rowCount', rowCount);
		for(i=1;i<=rowCount;i++)
		{
			titleField = dojo.byId("form_002_suppform_"+i+"-title");
	//		console.debug('titleField', titleField);
			if(titleField != null && titleField.value != '') dijit.byId("form_002_suppform_"+i).setTitle(titleField.value);
			//console.debug(titleField, i);
		}
	} catch(err) {
		//Handle errors here
		console.debug('form_002_suppform-count not found');
	}			

}
function pageInit()
{
    // color tabs based on validity
	if(typeof(validationMsgs) != "undefined"){
		colorSuppTabs(validationMsgs['items']);
	}
	// set tab titles
	setSuppTabTitles();
}

function pageReload(returneditems)
{
    // called when page returns from save
	colorSuppTabs(returneditems[0]['validationArr']);                   

	// set tab titles
	setSuppTabTitles();
}

dojo.addOnLoad(callPageInit);