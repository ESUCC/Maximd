/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/



function colorSuppTabs(validationMsgs){
	var processedKeys = new Array();
	
	var tabContainerPrefix = "iep_form_004_suppform_"
	
	// make all tabs have black text
	var tabNumber = dijit.byId(tabContainerPrefix+'tab_container').tablist.getChildren().length - 1;
	for(i=0;i<=tabNumber;i++)
	{
		colorTabText(tabContainerPrefix+'tab_container', i, 'grey');
	}
	
	// color invalid tabs red
    dojo.forEach(validationMsgs, // loop through returned errors
        function(validationArr) {
	    	var match = /iep_form_004_suppform_(.+)-/i.exec(validationArr['field'])
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

function setSuppTabTitles()
{
	try
	{
		// set tab titles based on form element data
		rowCount = dojo.byId("iep_form_004_suppform-count").value;
		//console.debug('rowCount', rowCount);
		for(i=1;i<=rowCount;i++)
		{
			titleField = dojo.byId("iep_form_004_suppform_"+i+"-title");
			if(titleField.value != '') dijit.byId("iep_form_004_suppform_"+i).setTitle(titleField.value);
			//console.debug(titleField, i);
		}
	}
	catch(err)
	{
		console.debug('there was an error while setting up supplimental tabs');
	}
	
}
function pageInit()
{
	try
	  {
	    // color tabs based on validity
	    console.debug(validationMsgs);
		colorSuppTabs(validationMsgs['items']);
	  }
	catch(err)
	  {
		console.debug('there was an error while processing validation messages - possibly no errors');
	  }
	
	
	// set tab titles
	setSuppTabTitles();
	console.debug('end pageInit');
}

function pageReload(returneditems)
{
    // called when page returns from save
	colorSuppTabs(returneditems[0]['validationArr']);                   

	// set tab titles
	setSuppTabTitles();
}

dojo.addOnLoad(callPageInit);
