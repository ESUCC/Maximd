//
// ItemFileManagedStore
// ==================================================================================================================================
dojo.declare(
    "ItemFileManagedStore",
    null,
    {
        store : null,
        identifier : 'id',
        updatefields : { val : "foo" },
        updateurl : 'unset',
        prefix : 'unset',
        constructor : function(arg1, arg2, arg3, arg4, arg5) {
                          this.store = arg1;
                          this.identifier = arg2;
                          this.updatefields = arg3;
                          this.updateurl = arg4;
                          this.prefix = arg5;
                          
                          this.dummykey= 10001;
                      },
        init_database_connect: function() {
            //console.log("init_database_connect: "+this.identifier)

            var updateurl = this.updateurl; // moving the this.var into local space so it can be seen bu programatic functions
            var identifier = this.identifier; // moving the this.var into local space so it can be seen bu programatic functions
            var prefix = this.prefix; // moving the this.var into local space so it can be seen bu programatic functions
            var store = this.store; // moving the this.var into local space so it can be seen bu programatic functions

             // moving the this.var into local space so it can be seen bu programatic functions
            
//            var updatefields = this.updatefields; // moving the this.var into local space so it can be seen bu programatic functions
            //
            // function to save data to the database
            //
            store._saveCustom = function(saveComplete, saveFailed) {

                console.group("init_database_connect - SAVING")
                
                // summary:
                // This is a custom save function for a data store to emit
                // the deleted, new and modified items as a block of JSON text.
                var changeSet = store._pending;
                //console.debug("store:"+store)
                changes = {};
        
                changes.deletedItems = {};
                changes.newItems = {};
                changes.modifiedItems = {};
                
                // Loop through ALL items in the store, checking to see which ones are dirty
                store.fetch({
                    query: {}, //fetch all rows for this store
                    onItem: function(item) {
                    	console.group('ItemFileManagedStore::onItem()');
                        if (store.isDirty(item)) {

                            //get the id from this item
                            var itemId = String(store.getIdentity(item));

                            //build a data structure of for this item        
                            if (dojo.exists(itemId, store._pending._newItems)) {
                                var itemData = {};
                                for (var itemElement in item) {
                                    if (itemElement.substr(0, 1) != '_') {
                                        itemData[itemElement] = store.getValue(item, itemElement);
                                        //console.log(itemElement + ": " + store.getValue(item, itemElement));
                                    }
                                    else {
                                        //console.log("Bad New Element: " + store.getValue(item, itemElement));
                                    }
                                }
        
                                //console.log('Writing to New Array');
                                changes.newItems[itemId] = itemData;
        
                            } else if (dojo.exists(itemId, store._pending._modifiedItems)) {
                                var itemData = {};
                                for (var itemElement in item) {
                                    if (itemElement.substr(0, 1) != '_') {
                                        itemData[itemElement] = store.getValue(item, itemElement);
                                        //console.log(itemElement + ": " + store.getValue(item, itemElement));
                                    }
                                    else {
                                        //console.log("Bad Modified Element: " + store.getValue(item, itemElement));
                                    }
                                }
        
                                //console.log('Writing to Modified Array');
                                changes.modifiedItems[itemId] = itemData;
                                //console.log('Wrote to Modified Array');
                            }
                        };
                        console.groupEnd();
                    },
        
                    onComplete: function() {
                    	console.group('ItemFileManagedStore::onComplete()');
                        // Do deletedItems last, as there is no access to the values of the items
                        for (var item in store._pending._deletedItems) {
        
                            //console.log("Deleted Item: " + item);
        
                            var itemData = {};
                            itemData[identifier] = item;
        
                            //console.log('Writing to Deleted Array');
                            changes.deletedItems[item] = itemData;
                        }
        
                        var data = dojox.json.ref.toJson(changes);
                        var jsonContainer = [];
                        jsonContainer[0] = data;
        
                        //console.debug(jsonContainer);
                        //console.log(data);
        
                        //send an XHR request to the server to request that the specified data be saved
                        //console.debug('identifier: ' +identifier);
                        
                        dojo.xhrPost({
                            url: updateurl,
                            handleAs: 'json',
                            timeout: 100000,
                            content: jsonContainer, // pass the data
        
                            //Load function called on successful response
                            load: function(response) {
                        		console.group('ItemFileManagedStore function: load', response);
                        		console.debug('url: ', updateurl);
                        		
                        		var returneditems = response.items;
                        		console.debug('count of returned items:', returneditems.length)
                        		
                        		//
                        		// if we have items returned (inserts)
                        		// update the store with the new id
                        		//
                        		if(returneditems.length > 0)
                        		{	
	                                dojo.forEach(returneditems, function(row){
	                                	//
	                                	// loop through returned rows 
	                                	//
	                                	console.debug('oldKey: ', row['oldKey']);
	                                	
	                                	store.fetchItemByIdentity({
	                                	    identity: row['oldKey'],	// find the row in the store with the old key
	                                	    onItem : function(item, request) {
	                                			console.debug('foundRow', row['id_iep_team_other'], item['id_iep_team_other']);
	                                			item.id_iep_team_other = row['id_iep_team_other'];
	                                			console.debug('foundRow', row['id_iep_team_other'], item['id_iep_team_other']);
	                                	    },
	                                	    onError : function(item, request) {
	                                	        /* Handle the error... */
	                                	    }
	                                	});
	                                	
	                                	
	                                });
                        		}
                                //console.log('prefix: '+prefix);
                                var datagridObj = dijit.byId(prefix);
                                //console.log(datagridObj);

                                console.debug('success');
                                saveComplete();
                                store.close();
                                console.debug('success2');
                                // don't forget to sort
                                datagridObj.setSortIndex(0, true); 
                                console.debug('success3');
                                datagridObj.sort(); // Calls datagrid._refresh internally
                                console.debug('success4');

                                console.groupEnd();
                                console.debug('success5');
                            },
        
                            //Error Function called in an error case
                            error: function(error) {
                                console.debug('oncomplete error');
                                console.debug(error);
                            }
                        });
                        console.groupEnd();
                    }
                });
                console.groupEnd();
            };
        
            //dojo.connect(savebutton, "onClick", store, "save");
        },
        fishman: function (args){
            //console.debug('fishman');
            //console.debug('args: '+args);
        	
        	// ajax call to insert the row and get a new id
        	
        },

        addrow: function (args){
            args[this.identifier] = 'tempkey_'+this.dummykey++;

            var nItem = this.store.newItem(args);
//            console.debug('nItem', nItem);

            var datagridObj = dijit.byId(this.prefix);
            datagridObj.sort(); // Calls datagrid._refresh internally
            
            enableSave(); // function in common_form_functions.js
        },

        deleterow: function (pk){
        	console.debug('delete pk: ', pk);
        	
        	var mystore = this.store;
        	var witem = null;
        	mystore.fetchItemByIdentity({
        		identity: pk,
        		onItem: function(item, request){ witem=item; }
        	});
        	if(!witem){ return alert('error deleting '+pk+' from Grid'); }
        	if(!confirm("Really delete?")){ return; }
        	mystore.deleteItem(witem);
        	
        	enableSave(); // function in common_form_functions.js
        	
//            var theqyery = eval('({'+this.identifier+': \''+result[ident]+'\'})');
//            var request = store.fetch({query: theqyery, onComplete: updateStore});
//        	
//        	this.storedeleteItem(items[i]);
        },

        forcesave: function (args){
            console.group('ItemFileManagedStore::forcesave(args)', args);
            //console.debug('args: '+args);
            
            var store = this.store;
            store.save();
            
            var datagridObj = dijit.byId(this.prefix);
            datagridObj.sort(); // Calls datagrid._refresh internally
            console.groupEnd();
        },

        submitted: function (args){
            // formiep_grid_connObj.submitted(dojo.toJson({formiep_grid_id_form_004:999,formiep_grid_doc_signed_parent:arguments[0]}, true))
            var result = args;
            //console.debug('submitted-result: '+result);
            var prefix = this.prefix;
            var store = this.store;
            //console.debug('prefix: '+prefix);

            try{
                result = dojo.fromJson(args);
                
            } catch(e) {
                // squelch exception, use input as result
            }
            //console.debug('json-result: '+result);
            //if(!store.save) console.debug('store function not found');

            
            
            var ident = prefix+'_'+this.identifier; // moving the this.var into local space so it can be seen bu programatic functions
            var updatefields = this.updatefields; // moving the this.var into local space so it can be seen bu programatic functions
            
            var updateStore = function(items, request){
                //
                // loop through 
                //
                dojo.forEach(updatefields, function(fieldName){
                    resultFieldName = prefix+'_'+fieldName;
                    if(ident != fieldName) {
                        store.setValue(items[0], fieldName, result[resultFieldName]);
                    }
                    //console.groupEnd();
                });
                //store.save(); // save functionality added to the save button
            }
            
            var theqyery = eval('({'+this.identifier+': \''+result[ident]+'\'})');
            var request = store.fetch({query: theqyery, onComplete: updateStore});
        }

    }
);
// END
// ItemFileManagedStore
// ==================================================================================================================================
