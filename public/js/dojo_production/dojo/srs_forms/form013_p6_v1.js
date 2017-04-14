/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


/*
 * show or hide a field
 * gets the subform identifier and constructs the proper id
 */ 
function subformShowHideJustification(id, naturalEnvironment, whoPays) { 
	
//	try {
		var rowIdentifier = getSubformRowIdentifierFromId(id);
//		console.debug( 'rowIdentifier', rowIdentifier);

		// natural services
		var natEnvName = rowIdentifier+'[service_natural]';
		var natEnvValue = getSubTabCheckedValue(natEnvName, rowIdentifier);
//		console.debug( 'natEnvValue', natEnvValue);
		
		// who pays
		var whoPaysValue = dojo.byId(rowIdentifier + '-service_who_pays').value;
//		console.debug( 'whoPaysValue', whoPaysValue);
		
		if(0 == natEnvValue && 'School district' == whoPaysValue) {
			// show the text area
//			console.debug('show justification');
			showHideAnimation(dojo.byId(rowIdentifier + '-service_justification-label_row'), 'show');
			showHideAnimation(dojo.byId(rowIdentifier + '-service_justification-element_row'), 'show');
		} else {
			showHideAnimation(dojo.byId(rowIdentifier + '-service_justification-label_row'), 'hide');
			showHideAnimation(dojo.byId(rowIdentifier + '-service_justification-element_row'), 'hide');
			// also remove text in service justification
			console.debug('editorId', rowIdentifier + '-service_justification');
			updateInlineValueTest(rowIdentifier + '-service_justification-Editor', '', true);
			
		}
		return true;
//	} catch (error) {
//		console.debug('Error while running subformShowHideJustification:form013_p6_v1.js');
//		return false;
//	}
	
}

function firstLoad(){
	var tc = dijit.byId("ifsp_services_tab_container");
	dojo.forEach(tc.getChildren(), function(tabNode){
//		console.debug('Run subformShowHideJustification on: ', tabNode.id);
		subformShowHideJustification(tabNode.id+'-service_justification', 'service_natural', 'service_who_pays'); 
	});
}

dojo.addOnLoad(firstLoad);
