dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.Dialog");
dojo.require("dojox.grid.DataGrid");
dojo.require("dojo.data.ItemFileWriteStore");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.TextBox");
dojo.require("dijit.form.DateTextBox");
dojo.require("dijit.form.TimeTextBox");
dojo.require("dojox.json.ref");

dojo.require("dojox.grid.Grid");
dojo.require("dojo.parser");


function gridClicked(n) { // n: [object MouseEvent]
    
    var gridID = n.currentTarget.id;
    var gridTab = dijit.byId(gridID);               // gridTab: [Widget dojox.grid.DataGrid, table]
    //var row = gridTab.selection.getSelected()[0];
    var datarow = gridTab.getItem(n.rowIndex);      // datastore item
    var store = gridTab.store;

    var dialogName = gridID+'_dialog';
    var dialogObj =  dijit.byId(dialogName);

//    var interactionObj = gridTab.store;
    
    // ========================================================================================
        var failed = function(error) {
            console.log('failed');
        }
        var displayClickedRow = function (item, request){
            
            console.debug('displayClickedRow');
            dojo.forEach(store.getAttributes(item), function(fieldName){
                
                dijitName = gridID + '_' + fieldName;
                console.debug('fieldName:'+dijitName);
                
                if(dijit.byId(dijitName)) {
                    console.debug('dijit:'+dijitName+' exists');
                    dijit.byId(dijitName).setValue(item[fieldName]);
                    
                } else if(dojo.byId(dijitName)) {
                    console.debug('dojo:'+dijitName+' exists');
                    dojo.byId(dijitName).innerHTML = item[fieldName];
                    
                }
            });        
            dialogObj.show(); // display the dialog
        
        }
    // ========================================================================================
    // ========================================================================================


    // if datarow is null, a header was probably clicked
    // or if the page was loaded improperly, referencing an object before it is created...
    // datarow may be null for all clicks
    //
    if(datarow == null)
    {
        //console.debug('datarow IS NULL:'+datarow);
        //console.debug('clickoutside of rows');
        
    } else {
        //console.debug('ROW FOUND:'+datarow);

        var rowIndex = n.rowIndex;
        console.debug('\trowIndex:'+rowIndex);
        //console.debug(row);

        //
        // get identifier from item (row clicked)
        //
        itemIdentity = store.getIdentity(datarow);
        
        //Invoke the lookup.  This is an async call as it may have to call back to a server to get data.
        store.fetchItemByIdentity({identity: itemIdentity, onItem: displayClickedRow, onError: failed});

    }
}



// function teamMemberDialog()
// {
// 
//     //build the nodes to display
//     var dialogNodes = new dojo.NodeList(dojo.doc.createElement("p"));
//     
//     i = 1;
//     if (1) {
//         var msg = "There was an error while processing your request. This is likely the result of a faulty connection to the server. Please check your internet connection and resubmit your request.";
//         dialogNodes.addContent(msg, i++);
//     }
//     
//     if (1) {
//         var pnode = new dijit.form.TextBox({
//             id: "participant_name"
//         });
//         dialogNodes.addContent(pnode, i++);
//     }
// 
// 
//     secondDlg = new dijit.Dialog({
//         title: "Edit IEP Team Member",
//         execute: "connObj.submitted(dojo.toJson(arguments[0], true));",
//         content: dialogNodes,
//         id: "formDialog",
//         style: "width: 300px"
//     });
// 
//     return secondDlg;
// }
var connectionObjects = {};

var monkeyload = function() {

    //var secondDlg = teammemberDialog();
    
    var mainPrefix = "member_grid";
    var dialogName = "member_grid_dialog";
    var tableName = "iep_team_member";
    var tableKey = "id_iep_team_member";
    var divToReplace = "replaceme_grid_iep_team_member"; // place div on layout (<div id="replaceme_grid_iep_team_member"></div>)
    var serverStoreGetUrl = "/ajax/data2/id/1255692";
    var serverStoreUpdateUrl = "/ajax/update";
    
    var updateFields = ['participant_name', 'absent'];

    //
    // link to dialog for this store
    //
    var secondDlg = dijit.byId(dialogName);


    //
    // get data from remote server
    //
    var store = new dojo.data.ItemFileWriteStore({
        identifier: tableKey,
        label: tableName,
        url: serverStoreGetUrl,
        clearOnClose: true

    });
    
    //
    // get data from remote server
    //
    member_grid_connObj = new ItemFileManagedStore(store, tableKey, updateFields, serverStoreUpdateUrl, mainPrefix);

    
    //
    // build the layout for the datagrid
    //
    var layout = [{
          rows: [[
            { name: 'sortnum', styles: 'text-align: left;', field: 'sortnum',
              editor: dojox.grid.editors.Input, width: 12},
            { name: 'Name(s)', styles: 'text-align: left;', field: 'participant_name',
              editor: dojox.grid.editors.Input, width: 12}
          ]]
        }];
    
    //
    // build the datagrid
    //
    var dg = new dojox.grid.DataGrid({
        id: mainPrefix,
        store: store,
        structure: layout,
        width: '100%',
        selectionMode: 'single',
        style: "font-size: 90%",
        query: eval('({'+tableKey+': \'*\'})'),
        queryOptions: {
            ignoreCase: true,
            deep: false
        },
        rowSelector: '10px',
        rowsPerPage: 10
    },
    dojo.byId(divToReplace));

    //
    // load the grid with data
    //
    dg.startup();
    
    dojo.connect(dojo.byId(mainPrefix),'onclick',gridClicked);

    member_grid_connObj.init_database_connect();

}
// var otherload = function() {
// 
//     //var secondDlg = teamparentDialog();
//     
//     var mainPrefix = "other_grid";
//     var dialogName = "other_grid_dialog";
//     var tableName = "iep_team_parent";
//     var tableKey = "id_iep_team_parent";
//     var divToReplace = "replaceme_grid_iep_team_parent"; // place div on layout (<div id="replaceme_grid_iep_team_parent"></div>)
//     var serverStoreGetUrl = "/ajax/getparents/id/004";
//     var serverStoreUpdateUrl = "/ajax/updateparent";
//     
//     var updateFields = ['participant_name', 'absent'];
// 
//     //
//     // link to dialog for this store
//     //
//     var secondDlg = dijit.byId(dialogName);
// 
// 
//     //
//     // get data from remote server
//     //
//     var store = new dojo.data.ItemFileWriteStore({
//         identifier: tableKey,
//         label: tableName,
//         url: serverStoreGetUrl,
//         clearOnClose: true
// 
//     });
//     
//     //
//     // get data from remote server
//     //
//     other_grid_connObj = new ItemFileManagedStore(store, tableKey, updateFields, serverStoreUpdateUrl, mainPrefix);
// 
//     
//     //
//     // build the layout for the datagrid
//     //
//     var layout = [{
//           rows: [[
//             { name: 'sortnum', styles: 'text-align: left;', field: 'sortnum',
//               editor: dojox.grid.editors.Input, width: 12},
//             { name: 'Name(s)', styles: 'text-align: left;', field: 'participant_name',
//               editor: dojox.grid.editors.Input, width: 12}
//           ]]
//         }];
//     
//     //
//     // build the datagrid
//     //
//     var dg = new dojox.grid.DataGrid({
//         id: mainPrefix,
//         store: store,
//         structure: layout,
//         //width: '100%',
//         selectionMode: 'single',
//         style: "font-size: 90%",
//         query: eval('({'+tableKey+': \'*\'})'),
//         queryOptions: {
//             ignoreCase: true,
//             deep: false
//         },
//         rowSelector: '20px',
//         rowsPerPage: 4
//     },
//     dojo.byId(divToReplace));
// 
//     //
//     // load the grid with data
//     //
//     dg.startup();
//     
//     dojo.connect(dojo.byId(mainPrefix),'onclick',gridClicked);
// 
//     other_grid_connObj.init_database_connect();
// 
// }
//dojo.addOnLoad(otherload);
dojo.addOnLoad(monkeyload);








dojo.declare(
    "ItemFileManagedStore",
    null,
    {
        store : null,
        identifier : 'id',
        updatefields : { val : "foo" },
        updateurl : '',
        prefix : '',
        constructor : function(arg1, arg2, arg3, arg4, arg5) {
                          this.store = arg1;
                          this.identifier = arg2;
                          this.updatefields = arg3;
                          this.updateurl = arg4;
                          this.prefix = arg5;
                      },
        init_database_connect: function() {
            console.log("init_database_connect: "+this.identifier)

            var updateurl = this.updateurl; // moving the this.var into local space so it can be seen bu programatic functions
            var identifier = this.identifier; // moving the this.var into local space so it can be seen bu programatic functions
            var prefix = this.prefix; // moving the this.var into local space so it can be seen bu programatic functions
            var store = this.store; // moving the this.var into local space so it can be seen bu programatic functions
            //
            // function to save data to the database
            //
            store._saveCustom = function(saveComplete, saveFailed) {

                console.log("saving")
                // summary:
                // This is a custom save function for a data store to emit
                // the deleted, new and modified items as a block of JSON text.
                var changeSet = store._pending;
        
                changes = {};
        
                changes.deletedItems = {};
                changes.newItems = {};
                changes.modifiedItems = {};
                
                // Loop through ALL items in the store, checking to see which ones are dirty
                store.fetch({
                    query: {}, //fetch all rows for this store
                    onItem: function(item) {
        
                        if (store.isDirty(item)) {

                            //get the id from this item
                            var itemId = String(store.getIdentity(item));

                            //build a data structure of for this item        
                            if (dojo.exists(itemId, store._pending._newItems)) {
                                var itemData = {};
                                for (var itemElement in item) {
                                    if (itemElement.substr(0, 1) != '_') {
                                        itemData[itemElement] = store.getValue(item, itemElement);
                                        console.log(itemElement + ": " + store.getValue(item, itemElement));
                                    }
                                    else {
                                        console.log("Bad New Element: " + store.getValue(item, itemElement));
                                    }
                                }
        
                                console.log('Writing to New Array');
                                changes.newItems[itemId] = itemData;
        
                            } else if (dojo.exists(itemId, store._pending._modifiedItems)) {
                                var itemData = {};
                                for (var itemElement in item) {
                                    if (itemElement.substr(0, 1) != '_') {
                                        itemData[itemElement] = store.getValue(item, itemElement);
                                        console.log(itemElement + ": " + store.getValue(item, itemElement));
                                    }
                                    else {
                                        console.log("Bad Modified Element: " + store.getValue(item, itemElement));
                                    }
                                }
        
                                console.log('Writing to Modified Array');
                                changes.modifiedItems[itemId] = itemData;
                            }
                        };
                    },
        
                    onComplete: function() {
                        // Do deletedItems last, as there is no access to the values of the items
                        for (var item in store._pending._deletedItems) {
        
                            console.log("Deleted Item: " + item);
        
                            var itemData = {};
                            itemData[identifier] = item;
        
                            console.log('Writing to Deleted Array');
                            changes.deletedItems[item] = itemData;
                        }
        
                        var data = dojox.json.ref.toJson(changes);
                        var jsonContainer = [];
                        jsonContainer[0] = data;
        
                        //console.log(changes);
                        //console.log(data);
        
                        //send an XHR request to the server to request that the specified data be saved
                        //console.debug('identifier: ' +identifier);
                        dojo.xhrPost({
                            url: updateurl,
                            //console.debug('updateurl: ' +updateurl);
                            //handleAs: 'json',
                            timeout: 100000,
                            content: jsonContainer,
        
                            //Load function called on successful response
                            load: function(response) {
                                console.log('prefix: '+prefix);
                                var datagridObj = dijit.byId(prefix);

                                if (response == "success") {
                                    console.debug('success');
                                    saveComplete();
                                    store.close();
                                    datagridObj.sort(); // Calls datagrid._refresh internally
                                } else {
                                    console.debug('fail');
                                    saveFailed();
                                }
                            },
        
                            //Error Function called in an error case
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                });
            };
        
            //dojo.connect(savebutton, "onClick", store, "save");
        },

        submitted: function (args){
            var result = args;
            var prefix = this.prefix;
            var store = this.store;
            console.debug('prefix: '+prefix);

            try{
                result = dojo.fromJson(args);
                
            } catch(e) {
                // squelch exception, use input as result
            }

            var ident = prefix+'_'+this.identifier; // moving the this.var into local space so it can be seen bu programatic functions
            var updatefields = this.updatefields; // moving the this.var into local space so it can be seen bu programatic functions
            
            var updateStore = function(items, request){
                //console.debug('saving item: '+items[0][ident]);
                dojo.forEach(updatefields, function(fieldName){
                    resultFieldName = prefix+'_'+fieldName;
                    if(ident != fieldName) {
                        //console.debug('saving value fieldName: '+fieldName+':'+result[resultFieldName]);
                        store.setValue(items[0], fieldName, result[resultFieldName]);
                    }
                });
                store.save();
            }
            var theqyery = eval('({'+this.identifier+': \''+result[ident]+'\'})');
            var request = store.fetch({query: theqyery, onComplete: updateStore});
        }


    }
);
