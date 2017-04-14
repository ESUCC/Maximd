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

//function jQuerySubformShowHideJustification(id, naturalEnvironment, whoPays) {
//    console.debug( 'jQuerySubformShowHideJustification id', id);
//
//    var rowIdentifier = getSubformRowIdentifierFromId(id);
//
//    // natural services
//    var natEnvName = rowIdentifier+'[service_natural]';
//    var natEnvValue = getSubTabCheckedValue(natEnvName, rowIdentifier);
//
//    // who pays
//    var whoPaysValue = $(rowIdentifier + '-service_who_pays').val();
//
//    if(0 == natEnvValue && 'School district' == whoPaysValue) {
//        // show the text area
//        console.debug('show justification', rowIdentifier + '-service_justification-label_row');
//        $(rowIdentifier + '-service_justification-label_row').show();
//        $(rowIdentifier + '-service_justification-element_row').show();
//    } else {
//        $(rowIdentifier + '-service_justification-label_row').hide();
//        $(rowIdentifier + '-service_justification-element_row').hide();
//        // also remove text in service justification
//        console.debug('editorId', rowIdentifier + '-service_justification');
//        updateInlineValueTest(rowIdentifier + '-service_justification-Editor', '', true);
//
//    }
//    return true;
//}

function firstLoad(){
//    if(0) {
//        var tc = dijit.byId("ifsp_services_tab_container");
//        dojo.forEach(tc.getChildren(), function(tabNode){
//            //		console.debug('Run subformShowHideJustification on: ', tabNode.id);
//            subformShowHideJustification(tabNode.id+'-service_justification', 'service_natural', 'service_who_pays');
//        });
//    } else {
        $('#ifsp_services_tab_container .ui-tabs-nav a').each(function(tab) {
            subformShowHideJustification('ifsp_services_'+(1+tab)+'-service_justification',
                'service_natural', 'service_who_pays');
        });
//    }

    /**
     * jquery dialog is failing on all pages
     * not sure what that conflict is, but we're still
     * trying to convert everything to jquery
     *
     * use the dojo dialog that works but all else is jquery
     * this is a static popup defined in the viewscript
     */
    dijit.byId('dde').hide();

    $('.popupHelp').live('click', function(element) {
        dijit.byId('dde').show();
    });

}

dojo.addOnLoad(firstLoad);
