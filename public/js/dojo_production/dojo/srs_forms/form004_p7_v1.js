/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/



//function attachSaveAction() {
//    console.debug('running attachSaveAction');
//    
//    //
//    // form id is stored on the form in a hidden field
//    // get the value here
//    //
//    var formID = dojo.byId("id_form_004").value;
//    
//    //
//    // attach save function (defined in /public/js/srs_forms/common_form_functions.js)
//    // to the submit button
//    // notice that we're wrapping the call to save in a function so we can pass parameters
//    //
//    var button = dijit.byId("submitButton");
//    dojo.connect(button, "onClick", function(evt){ save(evt, false, "/form004/jsonupdateiep"); console.debug('save finished');});
//    
//    
//    //
//    // attach nextPage function (defined in /public/js/srs_forms/common_form_functions.js)
//    // to the nextPage button
//    //
//    var nextButton = dijit.byId("nextPage");
//    dojo.connect(nextButton, "onClick",  function(evt){ nextPage(evt, "/form004/jsonupdateiep"); });
//
//    //
//    // attach prevPage function (defined in /public/js/srs_forms/common_form_functions.js)
//    // to the prevPage button
//    //
//    var prevButton = dijit.byId("prevPage");
//    dojo.connect(prevButton, "onClick",  function(evt){ prevPage(evt, "/form004/jsonupdateiep"); });
//
//    //
//    // attach selectPage function (defined in /public/js/srs_forms/common_form_functions.js)
//    // to the navPage select menu
//    //
//    var pageSelectButton = dojo.byId("navPage");
//    dojo.connect(pageSelectButton, "onchange",  function(evt){ selectPage(evt, "/form004/jsonupdateiep"); });
//}
//
//
//function loadForm() {
//    console.debug('running loadForm');
//    var formID = dojo.byId("id_form_004").value;
//
//    // ==============================================================================
//    // xhrGet
//    // load the main form from the server
//    // ==============================================================================
//        dojo.xhrGet( {
//        
//            
//            // The following URL must match that used to test the server.
//            url: "/ajax/getformiep/id/"+formID,
//            handleAs: "json",
//            load: function(responseObject, ioArgs) {
//              // Now you can just use the object
//              //console.dir(responseObject);  // Dump it to the console
////              items=responseObject.items;    
//				//
//				// after the page loads, disable the save button
//				//
//				console.debug('disable save button');
//				
//				var submitButton = dijit.byId('submitButton');
//				
//				console.debug('submit btn', submitButton, submitButton.getAttribute('disabled'));
//				
//				submitButton.setAttribute('disabled', false);
//				
//				return responseObject;
//            }
//            // More properties for xhrGet...
//        });    
//    // ==============================================================================
//    // end xhrGet
//    // ==============================================================================
//    // ==============================================================================
//
//
//}
//
//
//
//
//var connectionObjects = []; // variable containing datagrid connection objects
//
////dojo.addOnLoad(loadForm);
////dojo.addOnLoad(load_iepteammember);
////dojo.addOnLoad(load_teamother);
////dojo.addOnLoad(load_teamdistrict);
//dojo.addOnLoad(attachSaveAction);
//dojo.addOnLoad(startTimer);
//dojo.addOnLoad(firstLoadColorValidationErrors);
function childQualifies() {
    if ($("input[name='transportation_yn']:checked").val() == 0)
        $("#transportation_why").val('Not Necessary');
}
