//dojo.provide("dojo_my_classes.ErrorReporter");
//dojo.require("dijit._Widget");
//dojo.require("dijit.form.Button");
//
//dojo.declare("dojo_my_classes.ErrorReporter", [dijit._Widget, dijit._Templated],{
//	templatePath: dojo.moduleUrl("dojo_my_classes", "templates/ErrorReporter.html"),
//
//	//Indicate our widget has widgets declared in the template
//	widgetsInTemplate: true,
//
//	//Reference to the datastore to pass to grid
//	store: null,
//
//	//Reference to the query to send to the store
//	query: null,
//
//	//The page size ot use for the store
//	//Default is 10.
//	pageSize: 10,
//
//	//Label for the next button.
//	nextLabel: "Next >",
//
//	//Label for the previous button.
//	prevLabel: "< Previous",
//
//	//The grid structure to use.
//	structure: null,
//
//	//The underlying grid used in the paging view.
//	grid: null,
//
//	//Variable to control how tall to make the datagrid in pixels.
//	//-1 means autoHeight.
//	gridHeight: -1,
//
//	//current page we are on
//	_currentPage: 0,
//
//	//The currently obtained max # of rows to page through.
//	_maxSize: null,
//
//	//Flag to protect against accidental multi-startups.
//	_started: false,
//
//	//FilteringSelect used to make a 'jump to' dropdown.
//	_selector: null,
//		
//    
//	startup: function() {
//		//	summary:
//		if(!this._started){
//			this._started = true;
////			this.store = new dojo_my_classes._ForcedPageStore(this.store, this);
//console.debug('startup');
////			if(this.gridHeight > 0){
////				dojo.style(this._gridNode, "height", this.gridHeight + "px");
////			}
////			var gridParms = {store: this.store, 
////				structure: this.structure, 
////				rowsPerPage: this.pageSize,
////				query: this.query
////			};
////			if (this.gridHeight<=0) {
////				gridParms.autoHeight = this.pageSize;
////
////			}
////			this.grid = new dojox.grid.DataGrid(gridParms, this._gridNode);
////
////			this.grid.startup();
////			this.connect(window, "resize", "_resize");
////
////			//Total hack to get rid of ... default load text.
////			dojo.extend(dojox.grid.cells._Base, {defaultValue: "<div>&nbsp</div>"});
//		}
//	},
//
////	_resize: function(){
////		//	summary:
////		//		Function to handle resize by setting a slught delay on it to avoid 
////		//		multiple resize events
////		if (this._resTimer){
////			clearTimeout(this._resTimer);
////			this._resTimer = null;
////		}
////		this._resTimer = setTimeout(dojo.hitch(this, this.resize), 10);
////	},
////
////	resize: function(){
////		//	summary:
////		//		Simple function to handle invoking the underlying grid resize.
////		if(this.gridHeight > 0){
////			dojo.style(this.grid.domNode, "height", this.gridHeight + "px");
////		}
////		this.grid.resize();
////		this._centerSelector();
////	},
////
////	nextPage: function(){
////		//	summary:
////		//		Function to handle shifting to the next page in the list.
////		if(this._maxSize > ((this._currentPage + 1) * this.pageSize)){
////			//Current page is indexed at 0 and gotoPage expects 1-X.  So to go 
////			//up  one, pass current page + 2!
////			this.gotoPage(this._currentPage + 2);
////		}
////	},
////
////	prevPage: function(){
////		//	summary:
////		//		Function to handle shifting to the previous page in the list.
////		if(this._currentPage > 0){
////			//Current page is indexed at 0 and gotoPage expects 1-X.  So to go 
////			//back one, pass current page!
////			this.gotoPage(this._currentPage);
////		}
////	},
////
////	gotoPage: function(page){
////		//	summary:
////		//		Function to handle shifting to an arbirtary page in the list.
////		//
////		//	page:
////		//		The page to go to, starting at 1.
////		var totalPages = Math.ceil(this._maxSize / this.pageSize);
////		page--;
////		if (page < totalPages && page >= 0 && this._currentPage !== page) {
////			this._currentPage = page;
////			this.grid.setQuery(this.query);
////		}
////	},
////
////	setStore: function(store){
////		//	summary:
////		//		Function to set the store on the paging table.
////		//
////		//	store:
////		//		The store to set on the grid.
////		this._store = new dojo_my_classes._ForcedPageStore(store, this);
////		this.grid.setStore(this._store);
////	},
////
////	setQuery: function(query){
////		//	summary:
////		//		Function to set the query to use when fetching data.
////		//
////		//	query:
////		//		The query to use.
////		this.query = query;
////		this.grid.setQuery(query);
////	},
////
////	setStructure: function(structure){
////		//	summary:
////		//		Function to set the structure to use for grid layout.
////		//
////		//	structure:
////		//		The structure to use.
////		this.structure = structure;
////		this.grid.setStructure(structure);
////	},
////
////	refresh: function(){
////		//	summary:
////		//		Function to refresh the view of the current data.
////		//
////		this.grid.setQuery(this.query);
////	},
////
////	uninitialize: function(){
////		//	summary:
////		//		Cleanup function.
////		if(this.grid){
////			this.grid.destroy();
////			this.grid = null;
////		}
////		if(this._selector){
////			this._selector.destroy();
////			this._selector = null;
////		}
////	},
////
////	_centerSelector: function(){
////		//	summary:
////		//		Function to nicely center the selector dropdown between the two buttons.
////		if(this._selector){
////			//Try to center the selector!
////			var bb = dojo.marginBox(this.buttonBar);
////			var pb = dojo.marginBox(this.prevButton.domNode);
////			var fs = dojo.marginBox(this._selector.domNode.firstChild);
////			var leftMargin = ((bb.w/2) - (pb.w/2)) - (fs.w/2);
////			dojo.style(this._selector.domNode, "marginLeft", leftMargin+"px");
////		}
////	},
////
////	_selectorChanged: function(){
////		//	summary:
////		//		Selector connection point to control swapping pages.
////		var pg = parseInt(this._selector.attr("value"));
////		this.gotoPage(pg + 1);
////	}
//});
