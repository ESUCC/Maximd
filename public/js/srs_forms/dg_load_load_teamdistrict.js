var load_teamdistrict = function() {
    //console.debug('running load_teamdistrict');

    //var secondDlg = teammemberDialog();
    var formID = dojo.byId("id_form_004").value;
    
    var mainPrefix = "teamdistrict_grid";
    var dialogName = "teamdistrict_grid_dialog";
    var divToReplace = "replaceme_grid_iep_teamdistrict"; // place div on layout (<div id="replaceme_grid_iep_teamdistrict"></div>)

    var tableName = "iep_team_district";
    var tableKey = "id_iep_team_district";

    var serverStoreGetUrl = "/ajax/jsongetteamdistrict/id/"+formID;
    var serverStoreUpdateUrl = "/ajax/jsonupdateteamdistrict";
    
    var updateFields = ['participant_name', 'relationship_desc', 'relationship_other'];

    var addButtonId = "addrow_district";
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
    
    // ==========================================================================
    // Custom formatters
    // ==========================================================================
    formatCurrency = function(inDatum){
        //console.debug('formatCurrency', inDatum, isNaN(inDatum) ? '.x.' : dojo.currency.format(inDatum, this.constraint));
        return isNaN(inDatum) ? '.x.' : dojo.currency.format(inDatum, this.constraint);
    }
    formatDate = function(inDatum){
        //console.debug('formatDate', inDatum, dojo.date.locale.format(new Date(inDatum), this.constraint));
        //console.debug(inDatum, dojo.date.locale.format(new Date(inDatum), this.constraint));
        //console.debug(inDatum, dojo.date.locale.format(new Date("2009-06-08"), this.constraint));
        //console.debug(inDatum, dojo.date.locale.format(new Date("06/08/2009"), this.constraint));

        var date = dojo.date.locale.parse(inDatum, {datePattern: "yyyy-MM-dd", selector: "date"});
        //console.debug('date --- ', date);
        return dojo.date.locale.format(new Date(inDatum), this.constraint);
        return dojo.date.locale.format(new Date(date), this.constraint);
    }


    //
    // get data from remote server
    //
    teamdistrict_grid_connObj = new ItemFileManagedStore(store, tableKey, updateFields, serverStoreUpdateUrl, mainPrefix);
    connectionObjects.push(teamdistrict_grid_connObj);

    
    
    var relationshipOptions = [
                       		"Adaptive Physical Education", 
                       		"Assistive Technology", 
                       		"Audiologist", 
                       		"Counselor", 
                       		"Interpreter", 
                       		"Notetaker", 
                       		"Occupational Therapist", 
                       		"Parent Trainer", 
                       		"Physical  Therapist", 
                       		"Physician", 
                       		"Reader", 
                       		"Recreational Therapist", 
                       		"School Nurse", 
                       		"Speech Language Pathologist", 
                       		"Transportation Services", 
                       		"Vocational Education", 
                       		"Other (Please Specify)", 
   ];
                       

    var makeDeleteButton = function (pk){
    	var delButton = "<div dojoType=\"dijit.form.Button\">";
    	delButton = delButton + "<img src=\"/images/button_delete.gif\"";
    	delButton = delButton + " height=\"18\""; //width=\"18\" 
    	delButton = delButton + " onClick=\"teamdistrict_grid_connObj.deleterow('"+pk+"')\"></div>";
    	return delButton;
    }
    
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
//            { name: '#', 
//            	styles: 'text-align: left;', 
//            	field: 'sortnum', 
//            	width: 2,
//            	hidden: false
//        	},
            { name: 'Name(s)', styles: 'text-align: left;', field: 'participant_name', width: 15, editable:true,},
            { name: 'Position/Relationship To Student', 
            	styles: 'text-align: left;', 
            	field: 'relationship_desc', 
            	width: 18, 
            	editable:true,
            	type: dojox.grid.cells.ComboBox,
            	options: relationshipOptions
            },
            { name: 'If Other, Specify', 
            	styles: 'text-align: left;', 
            	field: 'relationship_other', 
            	width: 15, 
            	editable:true
        	},
        	{name: 'Delete', 
            	field: tableKey, 
            	width: '60px', 
            	formatter: makeDeleteButton
        	},
//            { name: 'Date', styles: 'text-align: left;', field: 'date_signed', width: 12, editable:true,
//              constraint: {formatLength: 'long', datePattern:'MM/dd/yyyy', selector:'date'},
//              formatter: formatDate,
//              type: dojox.grid.cells.DateTextBox,
//            }
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

    teamdistrict_grid_connObj.init_database_connect();

}