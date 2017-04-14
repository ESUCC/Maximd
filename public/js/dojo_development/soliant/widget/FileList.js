// dojo.provide allows pages to use all of the
// types declared in this resource.
dojo.provide("soliant.widget.FileList");

// dojo.require the necessary dijits for templated
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

// dojo.require the necessary dijits for widgets
// used in the template
dojo.require("dijit.form.Form");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.Select");
dojo.require("dijit.form.TextBox");

dojo.declare("soliant.widget.FileList", [ dijit._Widget, dijit._Templated ], {
	
	// Path to the template
	templateString : dojo.cache("soliant.widget", "FileList/FileList.html"),

	// are there widgets in the template
	widgetsInTemplate : true,

	// applied to the root element
	baseClass: "fileList",
	
	// formnum and document are required
	document_id: null,
	form_number: null,
	use_file_uploader: true,
	fu: null,
	
	url: '/file-list/search',
	deleteurl: '/file-list/delete',
	saveurl: '/file-list/save',

	startup: function() {
		this.inherited(arguments);
//		console.debug('_getDocumentId', this.document_id);
//		console.debug('_getFormNumber', this.form_number);
		if(null == this.document_id || null == this.form_number) {
			console.debug('Document id or form number are null.');
			return;		
		}
		
		// add file uploader 
		if(this.use_file_uploader) {
			this.fu = new soliant.widget.FileUploader({
				form_number: this.form_number,
				document_id: this.document_id,
				parent_id: this.id
				,forceFileInput: true
			}, this.fileListUploader);
		
			this.fu.startup();
		}

		

		this.search();
	},
	_setDocumentId: function (id) {
		this.document_id = id;
	},
	_getDocumentId: function () {
		return this.document_id;
	},
	_setFormNumber: function (form_number) {
		this.form_number = form_number;
	},
	_getFormNumber: function () {
		return this.form_number;
	},
	
	"delete" : function(evt) {
		
		var checkedDeleteRows = dojo.query('input:checked', this.domNode);
		if(checkedDeleteRows.length >= 1) {
			
			deleteFiles = new Array();
			dojo.forEach(
				checkedDeleteRows,
				function(row, index) {
					console.debug(row.value, index);
					deleteFiles[index] = row.value;
				}
			);
			console.debug(deleteFiles);
			submitObj = new Object();
			submitObj.form_number = this._getFormNumber();
			submitObj.document_id = this._getDocumentId();
			submitObj.deleteFiles = dojo.toJson(deleteFiles); 
				
			// submit the post and process result
			var xhrArgs = {
				content : submitObj,
				handleAs : "json",
				url : this.deleteurl,
				sync : true,
				load : dojo.hitch(this, "deleteResults"),
				error : function(data, ioArgs) {
					console.debug('error');
					alert('There was an error while trying to perform this search.');
				}
			};
			deferred = dojo.xhrPost(xhrArgs);
            deferred.then(this.search());
		}

		
	},
	
	showHideDeleteButton : function(evt) {
	    var domNode = this.domNode;
		
		if(dojo.query('input:checked', domNode).length >= 1) {
			// show the delete button
			dojo.style(this.fileListDeleteButton, "visibility", "visible");
			dojo.fadeIn({ node: this.fileListDeleteButton, duration:275 }).play();
		} else {
			// hide the button
			dojo.fadeOut({ node: this.fileListDeleteButton, duration:275 }).play();
			dojo.style(this.fileListDeleteButton, "visibility", "hidden");
		}
	},
	
	/*
	 * search: query server for results
	 */
	search : function(evt) {
		
		this.removeFileListRows();
		// build data to be submitted as json
		submitObj = new Object();
//		data = this.fileListForm.getValues();
		submitObj.form_number = this._getFormNumber();
		submitObj.document_id = this._getDocumentId();
		
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
		var deferred = dojo.xhrPost(xhrArgs);
		deferred.then(this.showHideDeleteButton());
	},
	
	/*
	 * build the search row in the widget
	 */
	searchResults : function(data, ioArgs) {
		that = this;
		dojo.forEach(
			data.items,
			function(row, index) {
				if('error' != row.result) {
					that.addFileListRow(that.fileListContainer, index, row.filename);
				}
			}
		);
	},
	deleteResults : function(data, ioArgs) {
		console.debug('delete result');
	},
	
	addFileListRow: function (table, index, filename) {
		 
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount-1);
        dojo.addClass(row, 'fileListInsertedRow');
        
        if(this.use_file_uploader) {
	        var cell0 = dojo.create("td", {"class":'fileListListDelete'}, row);
	        dojo.create("input", {id:"fileListListDelete_"+index, "class":"fileListDeleteCheckbox", type:"checkbox", value:filename}, cell0);
	        dojo.connect(dojo.byId("fileListListDelete_"+index), "onchange", this, "showHideDeleteButton");
		} else {
			dojo.fadeOut({ node: this.fileListHeaderDelete, duration:275 }).play();
			dojo.style(this.fileListHeaderDelete, "visibility", "hidden");
			dojo.style(this.fileListHeaderDelete, "display", "none");
		}
        var cell1 = dojo.create("td", {"class":'fileListListFilename'}, row);
        dojo.create("div", {innerHTML:filename}, cell1);
        
        var cell2 = dojo.create("td", {"class":'fileListListPreview'}, row);
        dojo.create("a", {href:"/file-list/printpdf/document/"+this._getDocumentId()+"/form_number/"+this._getFormNumber()+"/filename/"+encodeURIComponent(filename), innerHTML:'view'}, cell2);
        
    },
	removeFileListRows: function (table, index, filename) {
		 
		var insertedRows = dojo.query('.fileListInsertedRow', this.domNode);
		console.debug('insertedRows below');
		console.debug(insertedRows);
		if(insertedRows.length >= 1) {
			dojo.forEach(
				insertedRows,
				function(node, index) {
					console.debug(node, index);
					dojo.destroy(node);
				}
			);
		}
		
    },
	/*
	 * save search data to the database
	 */
	save : function(evt) {
		// build data to be submitted as json
		submitObj = new Object();
		data = this.fileListForm.getValues();
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
