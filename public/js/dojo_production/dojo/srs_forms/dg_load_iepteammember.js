/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/



var load_iepteammember = function() {
    	//    console.debug('running load_iepteammember');

    var formID = dojo.byId("id_form_004").value;
    
    var mainPrefix = "member_grid";
    var dialogName = "member_grid_dialog";
    var tableName = "iep_team_member";
    var tableKey = "id_iep_team_member";
    var divToReplace = "replaceme_grid_iep_team_member"; // place div on layout (<div id="replaceme_grid_iep_team_member"></div>)
    var serverStoreGetUrl = "/ajax/jsongetiepteammember/id/"+formID;
    var serverStoreUpdateUrl = "/ajax/jsonupdateiepteammember";
    
    var updateFields = ['participant_name', 'absent', 'meeting_date'];

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
    connectionObjects.push(member_grid_connObj);
    

    // ==========================================================================
    // Custom formatters
    // ==========================================================================
    formatCurrency = function(inDatum){
        //console.debug('formatCurrency', inDatum, isNaN(inDatum) ? '.x.' : dojo.currency.format(inDatum, this.constraint));
        return isNaN(inDatum) ? '.x.' : dojo.currency.format(inDatum, this.constraint);
    }

    
    formatDate = function(inDatum){
        
        
//        console.group('function:: FormatDate', inDatum);
        //, dojo.date.stamp.fromISOString(inDatum) );////, dojo.date.stamp.toISOString(inDatum)
        
        if(inDatum == null) 
       	{
//        	console.debug('null');
//        	console.groupEnd();
        	//return null;
        	return dojo.date.locale.format(new Date (), this.constraint);
       	}
        
        if(dojo.isString(inDatum))
        {
//        	console.debug('string');
        	//
        	// postgres data comes in as a string (2009-12-16)
        	//
//	        if(inDatum.search('-') > 0)
//	        {
//	        	inDatum = inDatum.replace(/-/g, '/'); // replace - with /
//	            console.debug('replacing dashes with slashes - inDatum: ',inDatum);
//	        }

	        var jsDate = new Date (inDatum);
//	        console.debug('jsDate: ', jsDate);

	        var dojoDate = dojo.date.locale.format(jsDate, this.constraint);
//	        console.debug('dojoDate: ', dojoDate);
	        
//	        console.groupEnd();
	        
	        return dojoDate;
	        
//	        return dojo.date.stamp.toISOString(dojoDate);


        } else {
        	//
        	// data selected comes back as a date object
        	//
//        	console.debug('not string', 'data selected comes back as a date object');
//        	console.debug(dojo.date.locale.format(inDatum, this.constraint));
        }
        
                
//        console.debug('middle');
        
        if(dojo.date.stamp.fromISOString(inDatum) )
        {
//        	console.debug('fromISOString');
        	var theDate = dojo.date.stamp.fromISOString(inDatum);
        	
        	var pdate = dojo.date.locale.parse(inDatum, {datePattern: "yyyy-MM-dd", selector: "date"});
        	
        	var retDate = dojo.date.locale.format(pdate, this.constraint);
        	
//        	console.debug('top', theDate, pdate, retDate);
//        	console.groupEnd();
        	return retDate;

        } else {
//        	console.debug('not fromISOString', '-', 'format the date');

        	var retDate = dojo.date.locale.format(inDatum, this.constraint);
//            return 		  dojo.date.locale.format(new Date(inDatum), this.constraint);
        	
//        	console.debug('bottom', retDate);
//        	console.groupEnd();
        	return retDate;
        	
        }
        	
        

        //console.debug(inDatum, dojo.date.locale.format(new Date(inDatum), this.constraint));
        //console.debug(inDatum, dojo.date.locale.format(new Date("2009-06-08"), this.constraint));
        //console.debug(inDatum, dojo.date.locale.format(new Date("06/08/2009"), this.constraint));

        var date = dojo.date.locale.parse(inDatum, {datePattern: "yyyy-MM-dd", selector: "date"});
        //console.debug('date: ', inDatum, date, dojo.date.locale.format(new Date(date), this.constraint));
        console.groupEnd();
        return dojo.date.locale.format(new Date(date), this.constraint);
    }
    formatDate2 = function(inDatum){
        console.group('function:: FormatDate', inDatum);
        
        if(inDatum == null) 
       	{
        	console.groupEnd();
        	return null;
       	}
        
        if(inDatum.search('-') > 0)
        {
        	inDatum = inDatum.replace(/-/g, '/');
            console.debug('inDatum: ',inDatum);
        }
        
        var jsDate = new Date (inDatum);
        console.groupEnd();
        return dojo.date.locale.format(jsDate, this.constraint);    
    }
//     dojo.declare("myDojox.grid.myEditor.Radio", dojox.grid.editors.AlwaysOn, {
//         // summary:
//         // grid cell editor that provides a standard radio button that is always on
//         _valueProp: "checked",
//         format: function(inDatum, inRowIndex){
//             return '<input class="dojoxGrid-input" dojoType="dijit.form.RadioButton" name="wuminqi" id="radiobutton' 
//             +inRowIndex+'" type="radio"' + (inDatum ? ' checked="checked"' : '') + ' style="width: auto" />';
//         },
//         doclick: function(e){
//             if(e.target.tagName == 'INPUT'){
//                 this.applyStaticValue(e.rowIndex);
//             }
//         }
//     });

    dojo.declare("my.grid.cells.Bool", dojox.grid.cells.Bool, {
        formatEditing: function(thevalue, inRowIndex){
            //console.debug('mybool', inRowIndex, thevalue);
            
            //if(inDatum.toLowerCase() === "t"  || inDatum.toLowerCase() === "true" || inDatum !=0)
            if( thevalue === "t" || thevalue === true  || thevalue == 1 )
            {
                var bool = true;
            } else { 
                var bool = false;
            }
            return '<input class="dojoxGridInput" type="checkbox"' + (bool ? ' checked="checked"' : '') + ' value="1" style="width: auto" /> Absent';
        }
    });

    dojo.declare("OracleDateTextBox",[dojox.grid.cells.DateTextBox], {
        serialize: function(d, options) {
    		console.debug('OracleDateTextBox');
    		return dojo.date.locale.format(d, {selector:'date', datePattern:'dd-MMM-yyyy', locale:'en'}).toLowerCase();
         },
         deserialize: function(d, options) {
     		console.debug('OracleDateTextBox');
     		return dojo.date.locale.format(d, {selector:'date', datePattern:'dd-MMM-yyyy', locale:'en'}).toLowerCase();
         }
	});

    dojo.declare("OracleDateTextBoxEditor",[dojox.grid.editors.DateTextBox], {
        serialize: function(d, options) {
			console.debug('OracleDateTextBoxEditor');
			return dojo.date.locale.format(d, {selector:'date', datePattern:'dd-MMM-yyyy', locale:'en'}).toLowerCase();
         },
         deserialize: function(d, options) {
      		console.debug('OracleDateTextBox');
      		return dojo.date.locale.format(d, {selector:'date', datePattern:'dd-MMM-yyyy', locale:'en'}).toLowerCase();
         }

	});

    //
    // build the layout for the datagrid
    //
    var layout = [{
          rows: [[
            { name: '#',
            	get: function(inRowIndex) { return inRowIndex+1;},
//                		editor: dojox.grid.editors.Dijit,
//                		editorClass: "dijit.form.NumberSpinner",
//                  	editable:true,
    		},
//            { name: '#', styles: 'text-align: left;', field: 'sortnum', width: 2, hidden: false},
            { 	name: 'Name(s)', 
    			styles: 'text-align: left;', 
    			field: 'participant_name', 
    			editable:true, width: 15
            },
            { name: 'Absent', styles: 'text-align: left;', width: 12, 
              field: 'absent',
              type: my.grid.cells.Bool,
              editable:true
            },
            { name: 'Position/Relationship To Student', styles: 'text-align: left;', field: 'positin_desc', width: 30},
//            { name: 'Date 0', width: 10, field: 'meeting_date',
//                editor: dojox.grid.editors.DateTextBox,
//                formatter: formatDate,
//                editable:true,
//                constraint: {formatLength: 'long', selector: "date"}},
            { name: 'Date', styles: 'text-align: left;', width: 12,
              field: 'meeting_date',
              formatter: formatDate,
              constraint: {formatLength: 'long', datePattern: "MM/dd/yyyy", selector: "date"},
              cellType: OracleDateTextBox, //dojox.grid.cells.DateTextBox,	// 
              type: OracleDateTextBox, //dojox.grid.cells.DateTextBox // show the drop down menu, but doesn't take value
              editor: OracleDateTextBoxEditor,//dojox.grid.editors.DateTextBox
              editable:true,

//              constraint: {formatLength: 'short', datePattern:'MM/dd/yyyy', selector:'date'},
//              formatter: formatDate,
//              cellType: dojox.grid.cells.DateTextBox,
//              editor: dojox.grid.editors.DateTextBox,
            
            },

          ]]
        }];



    
    
    //
    // build the datagrid
    //
    var dg = new dojox.grid.DataGrid({
        id: mainPrefix,
        store: store,
        structure: layout,
//        width: '100%',
//        selectionMode: 'single',
//        style: "font-size: 90%",
        query: eval('({'+tableKey+': \'*\'})'),
        queryOptions: {
            ignoreCase: true,
            deep: false
        },
//        rowSelector: '10px',
        rowsPerPage: 10,
        singleClickEdit: true
    },
    dojo.byId(divToReplace));

    //
    // load the grid with data
    //
    dg.startup();

    //
    // call modified to enable save button when elements in the store are changed
    //
    dojo.connect(store, "onSet", function (item, attribute, oldValue, newValue) {
        modified();
    });
    
    
    //dojo.connect(dojo.byId(mainPrefix),'onclick',gridClicked);

    member_grid_connObj.init_database_connect();

}
