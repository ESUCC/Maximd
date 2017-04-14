//dojo.require("dojox.grid.DataGrid");
dojo.require("dojo.data.ItemFileReadStore");

dojo.require("dojox.grid.EnhancedGrid");
//dojo.require("dijit.form.Select");
//dojo.require("dojox.grid.enhanced.plugins.NestedSorting");
//dojo.require("dojox.grid.enhanced.plugins.DnD");
function startStudentSearch()
{
	console.debug('startStudentSearch');
//    var formNum = dojo.byId("form_number").value;
//    var formID = dojo.byId("id_form_"+formNum).value;
//    var page = dojo.byId("page").value;
    // ==============================================================================
    // xhrPost
    // load the main form from the server 
    // ==============================================================================
	console.debug(dojo.byId('studentSearchParams'), 'studentSearchParams');
	xhrArgs = {
            form: dojo.byId('studentSearchParams'),
            url: '/student/search/',
            handleAs: 'json',
            load: searchResults
            // More properties for xhrGet...
        };    
        //Call the asynchronous xhrPost
        var deferred = dojo.xhrPost(xhrArgs); 
    // ==============================================================================
    // end xhrGet
    // ==============================================================================
    // ==============================================================================


}
//function getSearchRow(id_student_search)
//{
//	console.debug('getSearchRow', id_student_search);
//    // ==============================================================================
//    // xhrPost
//    // load the main form from the server 
//    // ==============================================================================
//	xhrArgs = {
//            url: '/student/addrow/id_student_search/'+id_student_search,
//            handleAs: 'json',
//            load: getSearchRowResults
//            // More properties for xhrGet...
//        };    
//        //Call the asynchronous xhrPost
//        var deferred = dojo.xhrPost(xhrArgs); 
//    // ==============================================================================
//    // end xhrGet
//    // ==============================================================================
//    // ==============================================================================
//}
//function getSearchRowResults(data, ioArgs) {
//    console.debug(data, 'data');
//    // get items from the json object
//    var returneditems = data.items;
//    
//    console.debug(data.items[0]['id_student_search_rows']);
//    
////    var id_student_search_rows = data.items[0]['id_student_search_rows'];
//    var tableHtml = data.items[0]['html'];
//    
//    dojo.place(tableHtml, 'student_search_search_row_container', 'last');
//    manageRemoveRowLinks();
//}
//
//function removeSearchRow(id_student_search_row)
//{
//	console.debug('removeSearchRow', id_student_search_row);
//	// ==============================================================================
//	// xhrPost
//	// load the main form from the server 
//	// ==============================================================================
//	xhrArgs = {
//			url: '/student/removerow/id_student_search_row/'+id_student_search_row,
//			handleAs: 'json',
//			load: removeSearchRowResults
//			// More properties for xhrGet...
//	};    
//	//Call the asynchronous xhrPost
//	var deferred = dojo.xhrPost(xhrArgs); 
//	// ==============================================================================
//	// end xhrGet
//	// ==============================================================================
//	// ==============================================================================
//}
//function removeSearchRowResults(data, ioArgs) {
//    // console.debug(data, 'remove data');
//    // get items from the json object
//    var returneditems = data.items;
//    if('success' == data.items[0]['result']) {
//    	// row was deleted
//    	dojo.destroy('student_search_row-'+data.items[0]['id_student_search_row']);
//    }
//    manageRemoveRowLinks();
//}
//
//function searchResults(data, ioArgs) {
//    var items = dojo.map(data.items, function(binding) {
//   		return {
//   					fullname : binding.name_last + ', ' + binding.name_first, 
//   					name_county : binding.name_county, 
//   					name_district : binding.name_district, 
//   					name_school : binding.name_school, 
//   					id_student: binding.id_student 
//   		};
//    });
//
//    var store =  new dojo.data.ItemFileReadStore({
//        data : {
//          items : items
//        }
//    });
//    function _createGrid(store) {
//        var layout = _getGridLayout();
//        var node = dojo.create("div", {}, dojo.byId("grid"), "only");
//        var grid = new dojox.grid.EnhancedGrid({
//          store : store,
//          structure : layout,
//          rowsPerPage: 10,
//          clientSort: true
////          plugins: {
////          	nestedSorting: true
////          }
//        }, node);
//            
//        grid.update();
//        grid.startup();
//        return grid;
//   }
//
//   function _getGridLayout() {
//      return [[
//               { field : "id_student", name : "Id Student", width : "10%" },
//               { field : "fullname", name : "Student Name", width : "15%" },
//               { field : "name_county", name : "County", width : "20%" },
//               { field : "name_district", name : "District", width : "20%" },
//               { field : "name_school", name : "School", width : "20%"}
//          
//      ]];
//   }
//    _createGrid(store);
//}
///*
// * hide remove row link when there is only one row
// * and show when more than one row
// */
//function manageRemoveRowLinks() {
//    searchRows = dojo.query('.student_search_row-remove_row');
//    if(1 < searchRows.length) {
//    	$display = '';
//    } else {
//    	$display = 'none';
//    }
//	dojo.forEach(
//		searchRows,
//		function(row, index) {
//			dojo.style(row, 'display', $display);
//		}
//	);
//	
//}

//dojo.addOnLoad(manageRemoveRowLinks);