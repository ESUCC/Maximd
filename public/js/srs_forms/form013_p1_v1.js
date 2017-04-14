
function setIfspType(evt) {

	select = dojo.byId('ifsptype');
	console.debug('select', select.value);
//	select.disabled = true;
	
	hiddenDiv = dojo.byId('ifsptypechosen');
	hiddenDiv.style.display = "inline";
}

function hideIfspType(evt) {

	hiddenDiv = dojo.byId('ifsptypeselect');
	hiddenDiv.style.display = "none";
}

function attachIfsptype() {
    console.debug('running attachIfsptype');
    var pageSelectButton = dojo.byId("ifsptype");
    if(pageSelectButton != null) {
    	dojo.connect(pageSelectButton, "onchange",  function(evt){ console.debug('here'); setIfspType(evt);});
    }

    // attach save function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the submit button
    var button = dijit.byId("submitButton");
    if(button != null) dojo.connect(button, "onClick", function(evt){ hideIfspType(evt);});

}

dojo.addOnLoad(attachIfsptype);
