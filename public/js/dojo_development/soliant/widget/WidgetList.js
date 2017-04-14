// dojo.provide allows pages to use all of the
// types declared in this resource.
dojo.provide("soliant.widget.WidgetList");

// dojo.require the necessary dijits for templated
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

// dojo.require the necessary dijits for widgets
// used in the template
dojo.require("dijit.form.Form");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.Select");
dojo.require("dijit.form.TextBox");

dojo.declare("soliant.widget.WidgetList", [ dijit._Widget, dijit._Templated ], {
	
	// Path to the template
	templateString : dojo.cache("soliant.widget", "WidgetList/WidgetList.html"),

	// are there widgets in the template
	widgetsInTemplate : true,
	
	id_widget_list: '',
	id_foreign_key: '',
	minRows: 0,
	
	// declare attributes
	// you need to declare any attributes that will be used
	// in the template file
	urlRelatedRecords: '/widget-list/related-records',
	url: '/widget-list/search',
	saveurl: '/widget-list/save',

	startup: function() {
		this.inherited(arguments);

		this.manageAddRowButton(false);
		this.manageRemoveRowLinks();
	},
	_setFkId: function (id) {
		this.manageAddRowButton(true);
		this.id_foreign_key = id;
	},
	_getFkId: function () {
		return this.id_foreign_key;
	},
	
	relatedRecords : function(evt) {
		this.removeAllRelatedRows();
		
		// put the key into the form
		this.WidgetListForeignKey.attr('value', this._getFkId());
		
		// build data to be submitted as json
		submitObj = new Object();
		data = this.WidgetListForm.getValues();
		submitObj.data = dojo.toJson(data);
		
		// submit the post and process result
		var xhrArgs = {
				content : submitObj,
				handleAs : "json",
				url : this.urlRelatedRecords,
				sync : true,
				load : dojo.hitch(this, "processRelatedRecords"),
				error : function(data, ioArgs) {
					console.debug('error');
					alert('There was an error while trying to perform this search.');
				}
		};
		dojo.xhrPost(xhrArgs);
	},

	processRelatedRecords : function(data) {
		var rowData = data.items;
		if(undefined !== rowData[0]['result']) {
			console.debug('error', rowData[0]['result']);
		} else {
			this.buildSearchRows(rowData);
		}
		this.manageRemoveRowLinks();
	},
	
	/*
	 * build the search row in the widget
	 */
	buildSearchRows : function(returneditems) {
		that = this;

		dojo.forEach(returneditems, function(row) {
			
			var rowId = 'rowNode'+row['id_widget_list'];
			var rowContainer = dojo.create("div", {id:rowId, class:'RelatedRow', style:'clear:both;'}, that.RelatedRows);
			var searchValueContainer = dojo.create("div", {name:'searchValueContainer', style:'display: inline;'}, rowId);
			var deleteRowContainer = dojo.create("div", {name:'deleteRowContainer', style:'display: inline;'}, rowId);

	        // id_widget_list (pk)
	        new dijit.form.TextBox({
	        	name: 'id_widget_list',
	        	value: row['id_widget_list'],
	        	type: 'hidden'
	        }).placeAt(searchValueContainer);
			
	        // name
			new dijit.form.TextBox({
	            name: 'name',
	            value:row['name']
	        }).placeAt(searchValueContainer);

			// delete row button
			new dijit.form.Button({
				class:'WidgetList-remove_row', 
				label: "Delete Row",
				title: 'Delete Search Row!',
				onClick:dojo.hitch(this, function(){
					that.deletesearchrow(row['id_widget_list']);
				})
			}).placeAt(deleteRowContainer);
			
		});
	},
	
	/*
	 * save search data to the database
	 */
	save : function(evt) {
		// build data to be submitted as json
		submitObj = new Object();
		data = this.WidgetListForm.getValues();
		submitObj.data = dojo.toJson(data);
		
		// submit the post and process result
		var xhrArgs = {
			content : submitObj,
			handleAs : "json",
			url : this.saveurl,
			sync : true,
			load : function(data, ioArgs) {
				if(undefined !== data.items[0]['result']) {
					//console.debug('result', data.items[0]['result']);
					result = data.items[0]['result'];
					if('success' == result) {
						alert('The item has been saved.');
					} else {
						alert('There was an error saving the item.');
					}
					
				} else {
					alert('There was an error saving the item.');
				}
			},
			error : function(data, ioArgs) {
				console.debug('error');
				alert('There was an error saving the item.');
			}
		};
		dojo.xhrPost(xhrArgs);
	},
	
	/*
	 * ajax call to remove the search row
	 */
	deletesearchrow : function(id, evt) {
		that = this;
		xhrArgs = {
				url: '/WidgetList/removerow/id_widget_list/'+id,
				handleAs: 'json',
				load: function(data, ioArgs) {
					if(undefined !== data.items[0]['result']) {
						//console.debug('result', data.items[0]['result']);
						result = data.items[0]['result'];
						if('success' == result) {
					    	// row was deleted
							var rowId = 'rowNode'+data.items[0]['id_widget_list'];
					    	dojo.destroy(rowId);
							that.manageRemoveRowLinks();
						} else {
							alert('There was an error deleting the item.');
						}
						
					} else {
						alert('There was an error deleting the item.');
					}
				}
		};    
		dojo.xhrPost(xhrArgs); 
	},
	/*
	 * hide remove row link when there is only one row
	 * and show when more than one row
	 */
	manageRemoveRowLinks:function() {
		var node=this.domNode;
	    searchRows = dojo.query('.WidgetList-remove_row', node);
	    if(this.minRows < searchRows.length) {
	    	$display = '';
	    } else {
	    	$display = 'none';
	    }
		dojo.forEach(
			searchRows,
			function(row, index) {
				dojo.style(row, 'display', $display);
			}
		);
		
		
		this.manageSaveAllButton(searchRows.length);
		
		
		
	},
	manageSaveAllButton:function(rowCount) {
		if(rowCount >= 1) {
			dojo.style(this.SaveAllContainer, 'display', 'inline');
		} else {
			dojo.style(this.SaveAllContainer, 'display', 'none');
		}
	},
	manageAddRowButton:function(booleanFlag) {
		if(booleanFlag) {
			dojo.style(this.AddRowContainer, 'display', 'inline');
		} else {
			dojo.style(this.AddRowContainer, 'display', 'none');
		}
	},
	

	/*
	 * hide remove row link when there is only one row
	 * and show when more than one row
	 */
	removeAllRelatedRows:function() {
		var node=this.domNode;
		relatedRows = dojo.query('.RelatedRow', node);
		dojo.forEach(
			relatedRows,
			function(row, index) {
				dojo.destroy(row);
			}
		);
	},
	/*
	 * add row to database and
	 * get as ajax data 
	 */
	addrow : function(evt) {
		xhrArgs = {
            url: '/widget-list/addrow/id_widget_list/'+this._getFkId(),
            handleAs: 'json',
			load : dojo.hitch(this, "processRelatedRecords"),
        };    
        dojo.xhrPost(xhrArgs); 
	}
	
});
