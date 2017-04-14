/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo.require("dojox.form.FileUploader");
dojo.require("dijit.ProgressBar");

//dojo.addOnLoad(function() {
//	if(dojo.byId('fileUploadButton')) {
//		
//	}
//}
//);


function setupFormUpload(currentPdfUpload){
	var formNum = dojo.byId('form_number').value;
	var documentId = dojo.byId('id_form_'+formNum).value;
	
	var uploadUrl = parent.location.protocol +'//'+location.host+"/file-upload/loadfile/formnum/"+formNum+"/document/"+documentId+"/location/"+currentPdfUpload;
	//console.debug('base u fdrl', uploadUrl);
	var props = {
		hoverClass:"uploadHover",
		activeClass:"uploadPress",
		disabledClass:"uploadDisabled",
		uploadUrl:uploadUrl,
		fileMask:[
			["Pdf File", 	"*.pdf"],
		]
	}
//	if(dojo.byId('fileUploadButton')) {
		var h = new dojox.form.FileUploader(dojo.mixin({
			force:"html",
			fileListId:"fFiles",
			showProgress:true,
			selectMultipleFiles:false,
			devMode:true
		}, props), "fileUploadButton");
		
	
		// connect the submit button on the pdf upload form
		dojo.connect(dijit.byId("editSubmitBtn"), "onClick", function(){
			var formNum = dojo.byId('form_number').value;
			var documentId = dojo.byId('id_form_'+formNum).value;
			var uploadUrl = parent.location.protocol +'//'+location.host+"/file-upload/loadfile/formnum/"+formNum+"/document/"+documentId+"/location/"+currentPdfUpload;
	 		h.submit(dojo.byId('gorilla'));
		});
		
		dojo.connect(h, "onComplete", function(dataArray){
			console.debug(dataArray, 'dataArray');
	        dojo.forEach(dataArray, function(d){
	        	// set filepath into the form
//	        	console.debug(d, 'FILE');
//	        	console.debug(d.items[0]['file'], 'FILE');
	        	
	        	dojo.byId(currentPdfUpload).value = d.items[0]['uploadedFileName'];
	        	dojo.byId(currentPdfUpload+'-text').innerHTML = 'PDF will be attached to end of printed document: ('+d.items[0]['uploadedFileName']+')';
	        	
	        	console.debug('remove-checkbox', currentPdfUpload+'-remove', dojo.byId(currentPdfUpload+'-remove'));
	        	dojo.style(currentPdfUpload+'-remove', 'display', 'inline');
	        	
	        	// hide the dialog
	        	var w = dijit.byId("myDialog");
	     		if(w){
	     			w.hide();
	    		}
			});
		});
//	}
}
var currentPdfUpload = '';
function showPdfDialog(hiddenId) {
	var w = dijit.byId("myDialog");
	currentPdfUpload = hiddenId;
	setupFormUpload(hiddenId);
	if(w){
		w.show();
	}
}
function clearPdfFileValue(id) {
	dojo.byId(id).value = '';
	dojo.byId(id+'-text').innerHTML = 'Click to insert pdf';
	dojo.style(id+'-remove', 'display', 'none');
}
