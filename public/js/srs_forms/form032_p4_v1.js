dojo.declare("lavere.Dialog", dijit._Widget, {
	cache: {},
	saveError: false,
	addError: false,
	deleteError: false,
	
	myFormsEdit: function (sourceTableId, replaceRowNum)
	{
		// build params
		var theObj = this;
		formNum = dojo.byId("form_number").value;
		formID = dojo.byId("id_form_"+formNum).value;
		page = dojo.byId("page").value;
		rowPrefix = 'myrecords_'+sourceTableId+'_';
		savedLinkPrefix = sourceTableId+'_saved_';
		urlAddress = '/form'+formNum+'/myrecords/myrecordsType/'+sourceTableId;
		
		// get the data from the db and store it
		this.getMyRecordsData(urlAddress);
		//console.debug('raw:', this.cache['raw']);
		
		// create the dialog
		if(!dojo.byId('formDialog')) {
			// dialog does not exist yet, create it
			myDialog = new dijit.Dialog({
				title: "My Saved Records",
				id: 'formDialog'
			});
		} else {
			// dialog exists
			// destroy existing widgets
			dojo.forEach(dijit.findWidgets(dojo.byId('formDialog')), function(w) {
				w.destroyRecursive();
			});
			dijit.byId('formDialog').destroyRecursive();
			
			// create new dialog
			myDialog = new dijit.Dialog({
				title: "My Saved Records",
				id: 'formDialog'
			});
		}

		// get row content from existing html row
		// this will be used as the template for each 
		// data row returned from the db
		if(dojo.byId(sourceTableId+'_1')) {
			replacePrefix = true;
			rowContent = dojo.clone(dojo.byId(sourceTableId+'_1')).innerHTML;
		} else if(dojo.byId(sourceTableId)) {
			replacePrefix = false;
			rowContent = dojo.clone(dojo.byId(sourceTableId)).innerHTML;
		} else {
			console.debug('exit - template table not found');
			return false;
		}

		// ====================================================================================
		// build the dialog content
		content = '<table border="1">';
		i = 1;
		dojo.forEach(this.cache['raw'], function(item){
			tmpRowHtml = dojo.clone(rowContent);
		
			// because we grab the first row, we replace _1
			// this fails when the elements do not have a prefix
			if(replacePrefix) {
				tmpRowHtml = tmpRowHtml.replace(new RegExp(sourceTableId+'_1', 'g'), rowPrefix+i);
			} else {
				// no prefix on field names
				// write content into the dialog so we can search it
				myDialog.attr('content', tmpRowHtml);
				
				// get each input and give it a prefix
				dojo.query('input', dojo.byId('formDialog')).forEach(
					function(node, index, arr){
						// add prefix to id and name attributes
						node.id = rowPrefix+i+'-'+node.id;
						node.name = rowPrefix+i+'-'+node.name;
					}
				);
				// write the new html back into the temp variable
				tmpRowHtml = myDialog.attr('content');
				// clear the dialog content off the page
				myDialog.attr('content', "");
			}
			
			// @todo decouple - pass as param
			// because we grab the first row, we replace _1
			tmpRowHtml = tmpRowHtml.replace(new RegExp('Team Member 1', 'g'), 'Team Member '+i);

			content +=  '<TR id="ROW__'+rowPrefix+i+'">';
				// button column
				content +=  '<TD>';
					content +=  '<button class="myrecords_use_link" id="myrecords_use_'+sourceTableId+'_'+i+'" dojoType="dijit.form.Button" type="button"> Use </button>';
					content +=  '<BR />';
					content +=  '<button class="myrecords_delete" id="myrecords_delete_'+sourceTableId+'_'+i+'" dojoType="dijit.form.Button" type="button"> Delete </button>';
				content +=  '</TD>';
				// template column
				content +=  '<TD>';
					content +=  '<input type="hidden" id="myrecords_'+sourceTableId+'_'+i+'-id_my_template_data">';
					content +=  '<table id="'+rowPrefix+i+'">';
					content +=  tmpRowHtml;
					content +=  '</table>';
				content +=  '</TD>';
			content +=  '</TR>';
			
			i++;
		});
		content += '</table>';
		
		// track row count
		rowsCreated = i-1;
		
		// bottom row buttons
		if(rowsCreated >= 1) {
			// add buttons for editing
			content += ' <button id="saveMyRecords-' + sourceTableId+'" dojoType="dijit.form.Button" type="button"> Save </button> ';
			content += ' <button id="editMyRecords-' + sourceTableId+'" dojoType="dijit.form.Button" type="button"> Edit </button> ';
		} else {
			// add notice row
			content +=  '<table><TR><TD>No saved records found for this section.</TD></TR></table>';
		}
	
		// close button should always be visible
		content += ' <button dojoType="dijit.form.Button" type="button" onClick="dijit.byId(\'formDialog\').hide();"> Close </button> ';
		
		// replace dialog content on the page
		myDialog.attr('content', content);
        
		// ====================================================================================
		// the html for the dialog is now in the DOM
		// we can now start hitching button functionality to the buttons
		// that we added above
		
		// hitch the edit button
        var callback = dojo.hitch( this, 'enableEdit', sourceTableId);
        dojo.connect( dijit.byId('editMyRecords-' + sourceTableId), "onClick", this, callback );

        // hitch the save button
	    var callback = dojo.hitch( this, 'saveAllRows', sourceTableId);
	    dojo.connect( dijit.byId('saveMyRecords-' + sourceTableId), "onClick", this, callback );
        
		// hitch use links
		i = 1;
		dojo.query('.myrecords_use_link', dojo.byId('formDialog')).forEach(
			function(node, index, arr){
		        callback = dojo.hitch( theObj, "useFromMyRecord", sourceTableId, i, replaceRowNum);
		        dojo.connect( node, "click", theObj, callback );
	            i = i+1;
			}
		);

		// hitch delete links
		i = 1;
		dojo.query('.myrecords_delete', dojo.byId('formDialog')).forEach(
			function(node, index, arr){
		        callback = dojo.hitch( theObj, "myFormsDelete", sourceTableId, i);
		        dojo.connect( node, "click", theObj, callback );
	            i = i+1;
			}
		);
		
		// ====================================================================================
		// fill the dialog content with data
		i = 1;
		dojo.forEach(this.cache['raw'], function(item)
		{
			// loop through properties
			fieldValuesArr = dojo.fromJson(item['table_data']);
	    	for(var propertyName in fieldValuesArr) {
	    		// propertyName is what you want
	 		    // you can get the value like this: myObject[propertyName]
	    		
	    		// build field name for fields inside of the template dialog
	    		templateFieldName = 'myrecords_'+sourceTableId+'_'+i+'-'+propertyName;  
	    		if(dojo.byId(templateFieldName)) {
	    			// if field exists, set value
	    			dojo.byId(templateFieldName).value = fieldValuesArr[propertyName];
	    		}
	    	}
	    	// set pk
	    	dojo.byId('myrecords_'+sourceTableId+'_'+i+'-id_my_template_data').value = item['id_my_template_data'];
			i++; // move on to next row
		});
		
		// rows are not editable until the user hits the edit button
		if(rowsCreated >= 1) {
			// disable inputs/buttons in the dialog
			this.disableEdit('formDialog', sourceTableId);
		}
		
		if(1 == rowsCreated) {
			console.debug('use only record');
			this.useFromMyRecord(sourceTableId, 1, replaceRowNum);
		} else {
			// display the dialog
			myDialog.show();
		}
	
	},
	/*
	 * get data from the db
	 */
    getMyRecordsData: function (urlAddress)
	{
	    var settings = {
	        // The following URL must match that used to test the server.
	        url: urlAddress,
	        handleAs: "json",
	        sync: true,
	        cache: this.cache,
	    };
		// store the 
		var urlAddress = settings.url,
			cache = settings.cache,
			req = dojo.xhrGet(dojo.safeMixin({ //cache['raw'] || //this.cache[urlAddress] ||
		        	  // override the load handler
		        	  load : function(resp) {
		            	  //cache[urlAddress] = resp.items;
		            	  cache['raw'] = resp.items; //dojo.toJson(resp.items);
		            	  cache['decoded'] = dojo.toJson(resp.items);
		        	  }
		          }, settings)
		  );
		dojo.when(req, settings.load);
	  return req;
	},

	/*
	 * user has selected to replace data in an existing row
	 * with data from the dialog
	 */
	useFromMyRecord: function (sourceTableId, templateRowNum, replaceRowNum)
	{
		// get values from the global JS variable
		fieldValuesArr = dojo.fromJson(this.cache['raw'][templateRowNum-1]['table_data']);

		// loop through properties
		// if the field exists in the row to be updated
		// replace the existing value with data from the template
		for(var propertyName in fieldValuesArr) {
			// propertyName is what you want
		    // you can get the value like this: myObject[propertyName]
			// build field name for fields inside of the template dialog
			var rowPrefix = sourceTableId+'_'+replaceRowNum+'-';
			var templateFieldName = propertyName;
			
			console.debug('replace on ', rowPrefix+templateFieldName);
			if(dojo.byId(rowPrefix+templateFieldName)) {
				// if field exists, set value
				console.debug(rowPrefix+templateFieldName, 'FOUND');
				dojo.byId(rowPrefix+templateFieldName).value = fieldValuesArr[propertyName];
			} else if(dojo.byId(templateFieldName)) { 
				// no no no
				// no row prefix - try the table
				// this can destroy fields outside the template
				//dojo.byId(templateFieldName).value = fieldValuesArr[propertyName];
//				console.debug(rowPrefix+templateFieldName, 'not found');
//				console.debug('sourceTableId:', dojo.byId(sourceTableId));
//				console.debug('templateFieldName:', templateFieldName);
//				console.debug('query:', dojo.query('#'+templateFieldName));
				// instead, search for decendents inside the source table
				dojo.query('#'+templateFieldName, dojo.byId(sourceTableId)).forEach(function(node, index, arr){
					console.debug('node', node);
					node.value = fieldValuesArr[propertyName]; 
				});

			}
		}
		// hide the dialog
		if(dijit.byId('formDialog')) {
			dijit.byId('formDialog').hide();
		}
	},
	
	/*
	 * user clicks the button to allow editing of content in the dialog
	 */
	enableEdit: function (templateId) {
		// enable save button
		dijit.byId('saveMyRecords-' + templateId).setAttribute('disabled', false);
	
		// show delete buttons
		dojo.query('.myrecords_delete', dojo.byId('formDialog')).forEach(
			function(node, index, arr){
			    dojo.style(node, 'visibility', 'visible');
			}
		);
	
		// disable editbutton
		dijit.byId('editMyRecords-' + templateId).setAttribute('disabled', true);
		
		// disable all inputs
		dojo.query('input', dojo.byId('formDialog')).forEach(function(node, index, arr){
		      dojo.removeAttr(node, 'disabled');
		});
	},
	
	/*
	 * disable editable fields in the dialog
	 * usually run before the dialog is displayed
	 */
	disableEdit: function (dialogName, templateId)
	{
		//console.debug('disableEdit templateId', templateId);
		// disable save button
		dijit.byId('saveMyRecords-' + templateId).setAttribute('disabled', true);
		
		// enable edit button
		dijit.byId('editMyRecords-' + templateId).setAttribute('disabled', false);
	
		// hide delete buttons
		dojo.query('.myrecords_delete', dojo.byId(dialogName)).forEach(
			function(node, index, arr){
			    dojo.style(node, 'visibility', 'hidden');
			}
		);
	
		// disable all inputs
		dojo.query('input', dojo.byId(dialogName)).forEach(function(node, index, arr){
		    dojo.attr(node, 'disabled', 'disabled');
		    dojo.attr(node, 'onchange', ''); // remove modified() and colorme()
		});
	
		// remove unwanted items from view
		dojo.query('.nodialog', dojo.byId(dialogName)).forEach(function(node, index, arr){
		    dojo.style(node, 'visibility', 'hidden');
		});
	},
	
	/*
	 * user has edited content in the dialog and hit save
	 */
	saveAllRows: function (templateId)
	{
		this.saveError = false;
		
		// this is the id of the dialog
		sourceTableId = 'myrecords_'+templateId;
	
		// disable inputs
		this.disableEdit('formDialog', templateId);

		// loop through the rows of the dialog
		rowNum = 1;
		while(dojo.byId(sourceTableId+'_'+rowNum)) {
			
			submitObj = new Object();
			tempObj = new Object();
			
			// loop through properties
			dojo.query('input', dojo.byId('ROW__'+rowPrefix+rowNum)).forEach(function(node, index, arr){
			    if(node.id) {
			    	strInputCode = node.id;
				    propertyName = strInputCode.replace(new RegExp(sourceTableId+'_'+"..", 'g'), '');
					eval("tempObj."+propertyName+"='"+node.value+"'");
				}
			});
			submitObj.data = dojo.toJson(tempObj);
			
		    var formNum = dojo.byId("form_number").value;
			
		    // send to server to be saved
			var xhrArgs = {
				content : submitObj,
				handleAs : "json",
		        url: '/form'+formNum+'/savemyrecords/myrecordsType/'+sourceTableId,
				// sync: wait2finish, // should we wait till the call is done before
				// continuing
				sync : true,
				load : this.myFormsSaveCallback,
				error : this.myFormSaveError
			};
			var deferred = dojo.xhrPost(xhrArgs); 
			
			rowNum = rowNum+1;
		}
		
	},
	
	/*
	 * user clicked on add record
	 * this stores the data from the selected record in the db
	 */
	myFormsAdd: function (sourceTableId, addRowNum)
	{
		// loop through properties
		x = new Object();
		dojo.query('input', dojo.byId(sourceTableId+'_'+addRowNum)).forEach(function(node, index, arr){
		    if(node.id) {
		    	strInputCode = node.id;
			    propertyName = strInputCode.replace(new RegExp(sourceTableId+'_'+"..", 'g'), '');
				x[propertyName] = node.value;
			    //opener.document.forms[0].elements['plugin-media4pic'].value
				//eval("x."+escape(propertyName)+"='"+node.value+"'");
			}
		});
	
		submitObj = new Object();
		submitObj.data = dojo.toJson(x);
		
	    var formNum = dojo.byId("form_number").value;
		// send to server to be saved
		var xhrArgs = {
			content : submitObj,
			handleAs : "json",
	        url: '/form'+formNum+'/insertmyrecords/myrecordsType/'+sourceTableId,
			sync : true,
			load : this.myFormsInsertCallback,
			error : this.myFormAddError
		};
		var deferred = dojo.xhrPost(xhrArgs); 
	
	},
	
	/*
	 * user clicked delete
	 * remove the row from the database
	 */
	myFormsDelete: function (sourceTableId, addRowNum)
	{
		// loop through properties
		submitObj = new Object();
		tempObj = new Object();
		dojo.query('input', dojo.byId('ROW__myrecords_'+sourceTableId+'_'+addRowNum)).forEach(function(node, index, arr){
			if(node.id) {
		    	strInputCode = node.id;
			    propertyName = strInputCode.replace(new RegExp(sourceTableId+'_'+"..", 'g'), '');
				eval("tempObj."+propertyName+"='"+node.value+"'");
			}
		});

		submitObj.data = dojo.toJson(tempObj);
		
	    var formNum = dojo.byId("form_number").value;
		// send to server to be saved
		var xhrArgs = {
			content : submitObj,
			handleAs : "json",
	        url: '/form'+formNum+'/deletemyrecords/myrecordsType/'+sourceTableId,
			sync : true,
			load : this.myFormsDeleteCallback,
			error : this.myFormDeleteError
		};
		var deferred = dojo.xhrPost(xhrArgs); 
		
		// hide the row
		dojo.style('ROW__myrecords_'+sourceTableId+'_'+addRowNum, 'visibility', 'hidden');
	},
	
	/*
	 * CALLBACK FUNCTIONS
	 * these are called when the ajax functions return from the server
	 */
	myFormsInsertCallback: function ()
	{
		// create the dialog
		if(!dojo.byId('myFormsInsertCallbackBtn')) {
			myDialog = new dijit.Dialog({
				title : "Insert Result",
				content :'Record added. <button dojoType="dijit.form.Button" type="button" onClick="dijit.byId(\'myFormsInsertCallbackBtn\').hide();"> Close </button>',
				id : "myFormsInsertCallbackBtn"
			});
		} else {
			myDialog = dijit.byId('myFormsInsertCallbackBtn');
		}
		myDialog.show();
	},
	myFormsSaveCallback: function (){},
	myFormsDeleteCallback: function ()
	{
		// create the dialog
		if(!dijit.byId('myFormsDeleteCallbackBtn')) {
			myDialog = new dijit.Dialog({
				title : "Insert Result",
				content :'Record deleted. <button dojoType="dijit.form.Button" type="button" onClick="dijit.byId(\'myFormsDeleteCallbackBtn\').hide();"> Close </button>',
				id : "myFormsDeleteCallbackBtn"
			});
		} else {
			myDialog = dijit.byId('myFormsDeleteCallbackBtn');
		}
		myDialog.show();
	},
	myFormSaveError: function (error) {
		this.saveError = true;
	},
	myFormAddError: function (error) {
		this.addError = true;
	},
	myFormDeleteError: function (error) {
		this.deleteError = true;
	}
});


function setupPage() {
	dialogWidget = new lavere.Dialog();
	
	i = 1;
	dojo.query('.myrecords_edit_link').forEach(
		function(node, index, arr){
            var callback = dojo.hitch( dialogWidget, dialogWidget.myFormsEdit, 'iep_form_029_returninfo', i);
            this.click = dojo.connect( node, "onclick", dialogWidget, callback );
            i = i+1;
		}
	);

	//hitch add links
	i = 1;
	dojo.query('.myrecords_add_link').forEach(
		function(node, index, arr){
            var callback = dojo.hitch( dialogWidget, dialogWidget.myFormsAdd, 'iep_form_029_returninfo', i);
            this.click = dojo.connect( node, "onclick", dialogWidget, callback );
            i = i+1;
		}
	);
	
}
dojo.addOnLoad(setupPage);

