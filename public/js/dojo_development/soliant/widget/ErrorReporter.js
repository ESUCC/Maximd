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
	templateString : dojo.cache("soliant.widget", "ErrorReporter/ErrorReporter.html"),

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
