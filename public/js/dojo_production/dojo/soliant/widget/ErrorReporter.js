/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["soliant.widget.ErrorReporter"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["soliant.widget.ErrorReporter"] = true;
// dojo.provide allows pages to use all of the
// types declared in this resource.
dojo.provide("soliant.widget.ErrorReporter");

// dojo.require the necessary dijits for templated
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

// dojo.require the necessary dijits for widgets
// used in the template
dojo.require("dijit.Dialog");
dojo.require("dijit.form.Form");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.Textarea");

dojo.declare("soliant.widget.ErrorReporter", [ dijit._Widget, dijit._Templated ], {
	
	// are there widgets in the template
	widgetsInTemplate : true,
	
	// declare attributes
	// you need to declare any attributes that will be used
	// in the template file
	keyName: '',
	formId: '',
	studentId: '',
	user: '',
	pageNumber: '',
	url: '/error-reporting/report',
	
	// Path to the template
	templateString : dojo.cache("soliant.widget", "ErrorReporter/ErrorReporter.html", "<div dojoType=\"soliant.widget.ErrorReporter\">\n    \n\t<button dojoType=\"dijit.form.Button\" dojoattachevent=\"onClick: showErrorReportDialog\" type=\"button\">Report Error</button>\n    \n    <div dojoType=\"dijit.Dialog\" title=\"Error Report\" dojoAttachPoint=\"dialogOne\">\n\t    <div dojoType=\"dijit.form.Form\" dojoAttachPoint=\"formOne\">\n\t\t    <div>User: ${user}</div>\n\t\t    <div>Form: ${keyName}</div>\n\t\t    <div >Form ID: ${formId}</div>\n\t\t    <div>Please provide a complete description of the problems you are having.</div> \n\t\t\t\n\t\t\t<textarea id=\"errorDescription\" name=\"errorDescription\" dojoType=\"dijit.form.Textarea\" style=\"min-height:100px;\"></textarea>\n\t\t\t<button dojoType=\"dijit.form.Button\" dojoattachevent=\"onClick: closeDialog\">Cancel</button>\n\t\t\t<button dojoType=\"dijit.form.Button\" dojoattachevent=\"onClick: saveErrorReport\">Submit</button>\n\t\t\t\n\t\t\t<input type=\"hidden\" id=\"formNumber\" name=\"formNumber\" value=\"${keyName}\" dojoType=\"dijit.form.TextBox\">\n\t\t\t<input type=\"hidden\" id=\"formId\" name=\"formId\" value=\"${formId}\" dojoType=\"dijit.form.TextBox\">\n\t\t\t<input type=\"hidden\" id=\"pageNumber\" name=\"pageNumber\" value=\"${pageNumber}\" dojoType=\"dijit.form.TextBox\">\n\t\t\t<input type=\"hidden\" id=\"studentId\" name=\"studentId\" value=\"${studentId}\" dojoType=\"dijit.form.TextBox\">\n\t    </div>\n\t</div>\n</div>\n"),

	showErrorReportDialog : function(evt) {
		// show the error reporting dialog
		this.dialogOne.show();
	},
	
	closeDialog : function(evt) {
		// close the error reporting dialog
		this.dialogOne.hide();
	},
	
	saveErrorReport : function(evt) {
		// hide the error reporting dialog
		this.dialogOne.hide();
		
		// build data to be submitted as json
		submitObj = new Object();
		data = this.formOne.getValues();
		data['link'] = document.URL; // add the current url to the passed data
		submitObj.data = dojo.toJson(data);
		
		// submit the post and process result
		var xhrArgs = {
			content : submitObj,
			handleAs : "json",
			url : this.url,
			sync : true,
			load : function(data, ioArgs) {
				var returneditems = data.items;
				var successCode = returneditems[0]['id_editor_data'];
				alert('Your error request has been submitted.');
			},
			error : function(data, ioArgs) {
				console.debug('error');
				alert('There was an error while trying to submit your issue. Please contact an administrator.');
			}
		};
		var deferred = dojo.xhrPost(xhrArgs);

	}

});

}
