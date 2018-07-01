
function attachSaveAction() {
    console.debug('running attachSaveAction');
    
    // form id is stored on the form in a hidden field
    // get the value here
    var formID = dojo.byId("id_form_001").value;
    
    // attach save function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the submit button
    var button = dijit.byId("submitButton");
    dojo.connect(button, "onClick", function(evt){ 
    	
    	//
    	// connect all the Zend_Dojo_Form_Element_Editor elements on this page
    	// grab the value from the iframe and put it into the hidden variable for the editor
    	//
    	dojo.byId('explanation').value = dijit.byId('explanation-Editor').getValue(false);
    	dojo.byId('options').value = dijit.byId('options-Editor').getValue(false);
    	dojo.byId('reasons').value = dijit.byId('reasons-Editor').getValue(false);
    	dojo.byId('proposal').value = dijit.byId('proposal-Editor').getValue(false);
    	dojo.byId('other_factors').value = dijit.byId('other_factors-Editor').getValue(false);
    	dojo.byId('amount_time').value = dijit.byId('amount_time-Editor').getValue(false);

    	
    	save(evt, false, "/ajax/updateform001");
    	console.debug('save finished');
    });
    
    // attach nextPage function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the nextPage button
    var nextButton = dijit.byId("nextPage");
    dojo.connect(nextButton, "onClick", nextPage);

    // attach prevPage function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the prevPage button
    var prevButton = dijit.byId("prevPage");
    dojo.connect(prevButton, "onClick", prevPage);

    // attach selectPage function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the navPage select menu
    var pageSelectButton = dojo.byId("navPage");
    dojo.connect(pageSelectButton, "onchange", selectPage);
    
    
    // set up the onchange handler for the rich text areas
//    var form = zend.findParentForm(dojo.byId('explanation'));
//    dojo.connect(form, 'onchange', function () {
//    	console.debug('explanation change');
//        dojo.byId('explanation').value = dijit.byId('explanation-Editor').getValue(false);
//    });

}


function loadForm() {
    console.debug('running loadForm');
    var formID = dojo.byId("id_form_001").value;
    
    //
    // load the form data from the server
    //
    dojo.xhrGet( {
        	
        // The following URL must match that used to test the server.
        url: "/ajax/getform001/id/"+formID,
        handleAs: "json",
        load: function(responseObject, ioArgs) {
          // Now you can just use the object
          //console.dir(responseObject);  // Dump it to the console
          items=responseObject.items;
//            var loadFields = ['date_doc_signed_parent', 'doc_signed_parent', 'necessary_action', 'received_copy', 'no_sig_explanation'];
          var loadFields = [];
            dojo.forEach(loadFields, function(fieldName)
            {
            	
            });

            //
            // after the page loads, disable the save button

            disableSave(); // function in common_form_functions.js
            
            return responseObject;
        }
        // More properties for xhrGet...
    });    
   
    // end xhrGet
}

var connectionObjects = []; // variable containing datagrid connection objects

dojo.addOnLoad(loadForm);
//dojo.addOnLoad(load_iepteammember);
//dojo.addOnLoad(load_teamother);
//dojo.addOnLoad(load_teamdistrict);
dojo.addOnLoad(attachSaveAction);
console.debug("dojo.version", dojo.version);

dojo.addOnLoad(function (){
//	console.debug('connecting', dojo.byId('necessary_action-0'));
//	console.debug('connecting', dojo.byId('necessary_action-1'));
	
//	dojo.connect(dojo.byId('necessary_action-0'), 'onchange', 'colorMe');
//	dojo.connect(dojo.byId('necessary_action-1'), 'onchange', 'colorMe');
//	
//	dojo.connect(dojo.byId('received_copy-0'), 'onchange', 'colorMe');
//	dojo.connect(dojo.byId('received_copy-1'), 'onchange', 'colorMe');
//	
//	dojo.connect(dojo.byId('doc_signed_parent-0'), 'onchange', 'colorMe');
//	dojo.connect(dojo.byId('doc_signed_parent-1'), 'onchange', 'colorMe');
//
//	dojo.connect(dojo.byId('date_doc_signed_parent'), 'onchange', 'colorMe');
//
//	dojo.connect(dojo.byId('no_sig_explanation'), 'onchange', 'colorMe');
	
//	
	//msgArr = json->decode(validationMsgs);
//	console.debug('validationMsgs; ', validationMsgs);
    dojo.forEach(validationMsgs, function(fieldName)
    {
    	console.debug('fieldName: ', fieldName);
//    	colorMeRed(fieldName); // color divs that are invalid
    });
		
});


