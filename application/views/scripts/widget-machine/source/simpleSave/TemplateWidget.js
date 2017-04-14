// dojo.provide allows pages to use all of the
// types declared in this resource.
dojo.provide("soliant.widget.{WidgetName}");

// dojo.require the necessary dijits for templated
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

// dojo.require the necessary dijits for widgets
// used in the template
dojo.require("dijit.form.Form");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.Select");
dojo.require("dijit.form.TextBox");

dojo.declare("soliant.widget.{WidgetName}", [ dijit._Widget, dijit._Templated ], {
	
	// Path to the template
	templateString : dojo.cache("soliant.widget", "{WidgetName}/{WidgetName}.html"),

	// are there widgets in the template
	widgetsInTemplate : true,
	
	id_{UnderscoreName}: '',
	
	// declare attributes
	// you need to declare any attributes that will be used
	// in the template file
	url: '/{DashesName}/search',
	saveurl: '/{DashesName}/save',

	startup: function() {
		this.inherited(arguments);
		this.search();
	},
	_setId: function (id) {
		this.id_{UnderscoreName} = id;
	},
	_getId: function () {
		return this.id_{UnderscoreName};
	},
	
	/*
	 * search: query server for results
	 */
	search : function(evt) {
		// put the key into the form
		this.{WidgetName}PrimaryKey.attr('value', this.id_{UnderscoreName});

		// build data to be submitted as json
		submitObj = new Object();
		data = this.{WidgetName}Form.getValues();
		submitObj.data = dojo.toJson(data);
		
		// submit the post and process result
		var xhrArgs = {
			content : submitObj,
			handleAs : "json",
			url : this.url,
			sync : true,
			load : dojo.hitch(this, "searchResults"),
			error : function(data, ioArgs) {
				console.debug('error');
				alert('There was an error while trying to perform this search.');
			}
		};
		dojo.xhrPost(xhrArgs);
	},
	
	/*
	 * build the search row in the widget
	 */
	searchResults : function(data, ioArgs) {
		var foundRow = data.items[0];
		this.{WidgetName}Name.attr('value', foundRow['name']);
	},
	
	/*
	 * save search data to the database
	 */
	save : function(evt) {
		// build data to be submitted as json
		submitObj = new Object();
		data = this.{WidgetName}Form.getValues();
		submitObj.data = dojo.toJson(data);
		
		// submit the post and process result
		var xhrArgs = {
			content : submitObj,
			handleAs : "json",
			url : this.saveurl,
			sync : true,
			load : function(data, ioArgs) {
				alert('The item has been saved.');
			},
			error : function(data, ioArgs) {
				console.debug('error');
				alert('There was an error saving the item.');
			}
		};
		dojo.xhrPost(xhrArgs);
	}	
	
});
