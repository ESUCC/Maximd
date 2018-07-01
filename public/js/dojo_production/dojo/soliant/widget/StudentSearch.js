/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["soliant.widget.StudentSearch"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["soliant.widget.StudentSearch"] = true;
// dojo.provide allows pages to use all of the
// types declared in this resource.
dojo.provide("soliant.widget.StudentSearch");

// dojo.require the necessary dijits for templated
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

// dojo.require the necessary dijits for widgets
// used in the template
dojo.require("dijit.form.Form");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.Select");
dojo.require("dijit.form.TextBox");
dojo.require("dojox.grid.EnhancedGrid");

dojo.declare("soliant.widget.StudentSearch", [ dijit._Widget, dijit._Templated ], {
	
	// are there widgets in the template
	widgetsInTemplate : true,
	
	id_student_search: '',
	
	// declare attributes
	// you need to declare any attributes that will be used
	// in the template file
	url: '/student-search/search',
	saveurl: '/student-search/save',
	
	studentSearchGrid: '',
	
	// Path to the template
	templateString : dojo.cache("soliant.widget", "StudentSearch/StudentSearch.html", "<div dojoType=\"soliant.widget.StudentSearch\">\n\t<style type=\"text/css\">\n\t  @import \"/js/dojo_development/dojo/dojox/grid/resources/Grid.css\";\n\t  @import \"/js/dojo_development/dojo/dojox/grid/resources/{{ theme }}Grid.css\";\n\t  .dojoxGrid table {\n\t    margin: 0;\n\t  }\n\t</style>\n\t<div dojoType=\"dijit.form.Form\" dojoAttachPoint=\"studentSearchForm\">\n\t\t<div style=\"padding-left:10px;float:left;\">\n\t\t\t<input type=\"hidden\" id=\"id_student_search\" name=\"id_student_search\" value=\"${id_student_search}\" dojoType=\"dijit.form.TextBox\">\n\t\t    <div style=\"float:left;\">\n\t\t    \tLimit to:\n\t\t    </div>\n\t\t    <div style=\"float:left;\">\n\t\t    \t<select name=\"limitto\" id=\"limitto\" dojoType=\"dijit.form.Select\" dojoAttachPoint=\"studentSearchLimitTo\">\n\t\t    \t\t<option value=\"all\">All</option>\n\t\t    \t\t<option value=\"caseload\">Caseload</option>\n\t\t    \t</select>\n\t\t\t</div>\t\t    \n\t\t    <div style=\"float:left;\">\n\t\t    \tStatus:\n\t\t    </div>\n\t\t    <div style=\"float:left;\">\n\t\t    \t<select name=\"status\" id=\"status\" dojoType=\"dijit.form.Select\" dojoAttachPoint=\"studentSearchStatus\">\n\t\t    \t\t<option value=\"All\">All</option>\n\t\t    \t\t<option value=\"Active\">Active</option>\n\t\t    \t\t<option value=\"Inactive\">Inactive</option>\n\t\t    \t\t<option value=\"Never Qualified\">Never Qualified</option>\n\t\t    \t\t<option value=\"No Longer Qualifies\">No Longer Qualifies</option>\n\t\t    \t\t<option value=\"Transferred to Non-SRS District\">Transferred to Non-SRS District</option>\n\t\t    \t</select>\n\t\t\t</div>\t\t    \n\t\t    <div style=\"float:left;\">\n\t\t    \tOrder by:\n\t\t    </div>\n\t\t    <div style=\"float:left;\">\n\t\t    \t<select name=\"sort_order\" id=\"orderby\" dojoType=\"dijit.form.Select\" dojoAttachPoint=\"studentSearchOrder\">\n\t\t    \t\t<option value=\"name\">Name</option>\n\t\t    \t\t<option value=\"school\">School</option>\n\t\t    \t</select>\n\t\t\t</div>\t\t    \n\t\t</div>\n\t\t<div style=\"padding-left:5px;float:left;\">\n\t\t    <div style=\"float:left;\">\n\t\t    \tRecords per page:\n\t\t    </div>\n\t\t    <div style=\"float:left;\">\n\t\t    \t<select name=\"recs_per\" id=\"recsPer\" dojoType=\"dijit.form.Select\" dojoAttachPoint=\"studentSearchRecsPer\">\n\t\t    \t\t<option value=\"2\">2</option>\n\t\t    \t\t<option value=\"5\">5</option>\n\t\t    \t\t<option selected=\"selected\" value=\"20\">20</option>\n\t\t    \t</select>\n\t\t\t</div>\t\t    \n\t\t    <div style=\"float:left;\">\n\t\t        <button id=\"search\" name=\"search\" dojoType=\"dijit.form.Button\" dojoAttachEvent=\"onClick: search\">Search</button>\n\t\t    </div>\n\t\t    <div style=\"float:left;\">\n\t\t        <button dojoType=\"dijit.form.Button\" dojoAttachEvent=\"onClick: save\">Save Search</button>\n\t\t    </div>\n\t\t    <div style=\"float:left;\">\n\t\t\t    <button dojoType=\"dijit.form.Button\" dojoAttachEvent=\"onClick: getsearchrow\">Add Row</button>\n\t\t\t</div>\t\t    \n\t\t</div>\n\t\t<div style=\"padding-left:10px;float:right;\">\n\t\t\t<div dojoAttachPoint=\"studentSearchFormRows\"></div>\n\t\t</div>\n\t</div>\n\t<div class=\"claro\" style=\"height:400px;clear:both;margin-top:10px;\" dojoAttachPoint=\"studentSearchGrid\"></div>\n</div>\n"),

	/*
	 * boot the widget to get the current search data
	 */
	startup: function() {
		this.inherited(arguments);		
		this.getSearchHeader();
		
		this.manageRemoveRowLinks();
		
	},

	/*
	 * search: query server for results
	 */
	getSearchHeader : function() {
		
		// submit the post and process result
		var xhrArgs = {
			handleAs : "json",
			url : "/student-search/getsearchheader/id_student_search/"+this.id_student_search,
			sync : true,
			load : dojo.hitch(this, "buildSearchHeader"),
			error : function(data, ioArgs) {
				console.debug('error');
				alert('Error');
			}
		};
		var deferred = dojo.xhrPost(xhrArgs);
	},
	/*
	 * parse out the search row data
	 * and pass to builder function
	 */
	buildSearchHeader : function(data, ioArgs) {
		
		var searchData = data.items[0];
		var searchRows = searchData['subforms']['my_searches'];
		
//		console.debug('status', this.studentSearchStatus);
		this.studentSearchLimitTo.attr('value', searchData['limitto']);
		this.studentSearchStatus.attr('value', searchData['status']);
		this.studentSearchOrder.attr('value', searchData['sort_order']);
		this.studentSearchRecsPer.attr('value', searchData['recs_per']);
		
		this.buildSearchRows(searchRows);
		this.buildSearchList(searchData);
	},
	/*
	 * add row to database and
	 * get as ajax data 
	 */
	getsearchrow : function(evt) {
		xhrArgs = {
            url: '/student-search/addrow/id_student_search/'+this.id_student_search,
            handleAs: 'json',
			load : dojo.hitch(this, "addsearchrow"),
        };    
        dojo.xhrPost(xhrArgs); 
	},
	/*
	 * parse out the search row data
	 * and pass to builder function
	 */
	addsearchrow : function(data, ioArgs) {
		var searchData = data.items[0];
		var searchRows = new Array();
		searchRows[0] = searchData;
		this.buildSearchRows(searchRows);
	},
	/*
	 * build the search row in the widget
	 */
	buildSearchRows : function(returneditems) {
		that = this;
		
		dojo.forEach(returneditems,
			function(row) {
				var rowId = 'rowNode'+row['id_student_search_rows'];
				var rowContainer = dojo.create("div", {id:rowId, style:'clear:both;'}, that.studentSearchFormRows);
				var searchFieldContainer = dojo.create("div", {name:'searchFieldContainer', style:'display: inline-block;'}, rowId);
				var searchValueContainer = dojo.create("div", {name:'searchValueContainer', style:'display: inline-block;'}, rowId);
				var deleteRowContainer = dojo.create("div", {name:'deleteRowContainer', style:'display: inline-block;'}, rowId);
				

		        // delete row button
				deleteLink = new dijit.form.Button({
					'class':'button_remove_row', 
		        	label: "Delete Row",
		        	title: 'Delete Search Row!',
		            onClick:dojo.hitch(this, function(){
		            	that.deletesearchrow(row['id_student_search_rows']);
		            })
		        }).placeAt(deleteRowContainer);
				
//				// create the delete row link and attach to the widget function
//				deleteLink = dojo.create("a", { class:'button_remove_row', href: "javascript:void(0);", title: "Delete Search Row!", innerHTML: "Delete Row" }, deleteRowContainer);
//				// passing parameters to the function call on the link
//				dojo.connect(deleteLink, 'onClick', dojo.hitch(that, "deletesearchrow", row['id_student_search_rows']));
				
				// build the select for the search field option
		        new dijit.form.Select({
		            name: 'search_field',
		            options: [{
		                label: 'Name First',
		                value: 'name_first'
		            },
		            {
		                label: 'Name Last',
		                value: 'name_last',
		                selected: true
		            },
		            {
		                label: 'ID Student (District)',
		                value: 'id_student'
		            }],
		            value:row['search_field']
		        }).placeAt(searchFieldContainer);
		
		        // search value
		        new dijit.form.TextBox({
		            name: 'search_value',
		            value:row['search_value']
		        }).placeAt(searchValueContainer);
		        
		        // id_student_search_rows (pk)
		        new dijit.form.TextBox({
		        	name: 'id_student_search_rows',
		        	value: row['id_student_search_rows'],
		        	type: 'hidden'
		        }).placeAt(searchValueContainer);
			}
		);
		that.manageRemoveRowLinks();
	},
	/*
	 * search: query server for results
	 */
	search : function(evt) {
		that = this;
		
		// build data to be submitted as json
		submitObj = new Object();
		data = this.studentSearchForm.getValues();
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
				alert('There was an error while trying to submit your issue. Please contact an administrator.');
			}
		};
		dojo.xhrPost(xhrArgs);
	},
	/*
	 * get search results and put into the grid
	 */
	searchResults: function(data, ioArgs) {
		that = this;
		
	    var items = dojo.map(data.items, function(binding) {
	   		return {
	   					fullname : binding.name_last + ', ' + binding.name_first, 
	   					name_county : binding.name_county, 
	   					name_district : binding.name_district, 
	   					name_school : binding.name_school, 
	   					id_student: binding.id_student 
	   		};
	    });

	    var store =  new dojo.data.ItemFileReadStore({
	        data : {
	          items : items
	        }
	    });
	    this._createGrid(store, this.studentSearchGrid);
	},
	/*
	 * save search data to the database
	 */
	save : function(evt) {
		// build data to be submitted as json
		submitObj = new Object();
		data = this.studentSearchForm.getValues();
		submitObj.data = dojo.toJson(data);
		
		// submit the post and process result
		var xhrArgs = {
			content : submitObj,
			handleAs : "json",
			url : this.saveurl,
			sync : true,
			load : function(data, ioArgs) {
//				console.debug('The search has been saved');
				alert('The search has been saved.');
			},
			error : function(data, ioArgs) {
				console.debug('error');
				alert('There was an error saving the search.');
			}
		};
		dojo.xhrPost(xhrArgs);
	},
	/*
	 * ajax call to remove the search row
	 */
	deletesearchrow : function(id_student_search_row, evt) {
		that = this;
		xhrArgs = {
				url: '/student-search/removerow/id_student_search_row/'+id_student_search_row,
				handleAs: 'json',
				load: function(data, ioArgs) {
				    var returneditems = data.items;
				    if('success' == data.items[0]['result']) {
				    	// row was deleted
						var rowId = 'rowNode'+data.items[0]['id_student_search_row'];
				    	dojo.destroy(rowId);
						that.manageRemoveRowLinks();
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
	    searchRows = dojo.query('.button_remove_row', node);
	    if(1 < searchRows.length) {
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
	},

    _createGrid: function(store, gridDiv) {
		that = this;
    	
        var layout = this._getGridLayout();
        var node = dojo.create("div", {}, gridDiv, "only");
        var grid = new dojox.grid.EnhancedGrid({
          store : store,
          structure : layout,
          rowsPerPage: 10,
          clientSort: true
//	          plugins: {
//	          	nestedSorting: true
//	          }
        }, node);
            
        grid.update();
        grid.startup();
        return grid;
   },

   _getGridLayout:function() {
      return [[
               { field : "id_student", name : "Id Student", width : "10%" },
               { field : "fullname", name : "Student Name", width : "15%" },
               { field : "name_county", name : "County", width : "20%" },
               { field : "name_district", name : "District", width : "20%" },
               { field : "name_school", name : "School", width : "20%"}
          
      ]];
   },
   
   buildSearchList: function(searchData) {
	   console.debug('buildSearchList', searchData);

	   if(dojo.byId('StudentSearch_Widget_List')) {
		   dojo.byId('StudentSearch_Widget_List').innerHTML = "";
	   }

//	   xhrArgs = {
//			   url: '/student-search/addrow/id_student_search/'+this.id_student_search,
//			   handleAs: 'json',
//			   load : dojo.hitch(this, "addsearchrow"),
//	   };    
//	   dojo.xhrPost(xhrArgs); 

   }
	
	/*
	 * search: query server for results
	 */
//	getSearchRows : function() {
//		
//		// put function into local space so it 
//		// can be called in the load function
//		buildSearchRow = this.buildSearchRow;
//		studentSearchFormRows = this.studentSearchFormRows;
//		
//		// submit the post and process result
//		var xhrArgs = {
//				handleAs : "json",
//				url : "/student-search/getrows/id_student_search/"+this.id_student_search,
//				sync : true,
//				load : dojo.hitch(this, "buildSearchRows"),
//				error : function(data, ioArgs) {
//					console.debug('error');
//					alert('Error');
//				}
//		};
//		var deferred = dojo.xhrPost(xhrArgs);
//	},
	
	
});

}
