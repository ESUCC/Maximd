
function connectOtherCheckboxes() {
	//
    // attach fadeNode function 
    // to the otherEvalProc and otherPersonResp checkboxes
    //
	//var numberOfTabs = dojo.byId("iep_form_004_goal_tab_container_tablist").getElementsByClassName(" dijitTab").length;
	numberOfTabs = $('#iep_form_004_goal_tab_container_tablist .dijitTab').length;
	//alert("Number of tabs: " + numberOfTabs);
	
	//Attach it to the "Other" checkbox in every tab
	for(var j=1; j<=numberOfTabs; j++) {
		var checkboxNameEP = "iep_form_004_goal_"+j+"-eval_procedure-H";
	    var otherEvalProcCheckbox = dojo.byId(checkboxNameEP);
	    dojo.connect(otherEvalProcCheckbox, "onchange",  function(evt){ changeOtherEvalProc(evt); });
	    
	    var checkboxNamePR = "iep_form_004_goal_"+j+"-person_responsible-O";
	    var otherPersonRespCheckbox = dojo.byId(checkboxNamePR);
	    dojo.connect(otherPersonRespCheckbox, "onchange",  function(evt){ changeOtherPersonResp(evt); });
	    
	    var scheduleDropDownString = "iep_form_004_goal_"+j+"-schedule";
	    var scheduleDropDownElem = dojo.byId(scheduleDropDownString);
	    dojo.connect(scheduleDropDownElem, "onchange",  function(evt){ changeOtherSchedule(evt); });
	}
}

function getActiveTab() {
	activeTabs = $('#dijitTab dijitTabChecked dijitChecked').length;
	var activeTab = activeTabs[0];
	var widgetid = activeTab.getAttribute("widgetid");
	var i = widgetid.substring(24); //dijit_layout__TabButton_0 has 25 chars or more
	i++;
	return i;
}

function changeOtherSchedule(evt) {
	//Change textfield status
	setOtherSchedule(getRowNumFromSubformRowIdentifier(getSubformRowIdentifierFromId(evt.target.id)));
}

function changeOtherPersonResp(evt) {
	//Change textfield status
	setOtherPersonResp(getRowNumFromSubformRowIdentifier(getSubformRowIdentifierFromId(evt.target.id)));
}

function changeOtherEvalProc(evt) {
	//Change textfield status
	setOtherEvalProc(getRowNumFromSubformRowIdentifier(getSubformRowIdentifierFromId(evt.target.id)));
}

//Hide or show the "Other" text fields when the page load for every tab
function setOtherFieldsDisplay() {
	//numberOfTabs = dojo.byId("iep_form_004_goal_tab_container_tablist").getElementsByClassName(" dijitTab").length;
	numberOfTabs = $('#iep_form_004_goal_tab_container_tablist .dijitTab').length;
	//console.debug('numberOfTabs', numberOfTabs, $('#iep_form_004_goal_tab_container_tablist .dijitTab').length);
	for(var i=1; i<=numberOfTabs; i++) {
		setOtherPersonResp(i);
		setOtherEvalProc(i);
	}
}

function setOtherSchedules() {
	numberOfTabs = $('#iep_form_004_goal_tab_container_tablist .dijitTab').length;
	//var numberOfTabs = dojo.byId("iep_form_004_goal_tab_container_tablist").getElementsByClassName(" dijitTab").length;

	for(var i=1; i<=numberOfTabs; i++) {
		setOtherSchedule(i);
	}
}

//Hide or show the "Other" text field in "Evaluation Process" for tab number i
function setOtherSchedule(i) {
	
	try {
		var dropdownName = "iep_form_004_goal_"+i+"-schedule";
		var scheduleOther = "iep_form_004_goal_"+i+"-schedule_other";
		var otherEvalProcName = 'iep_form_004_goal_'+ i +'-time-length';
		
		var dropdownElem = dojo.byId(dropdownName);
		
		if(dropdownElem)
		{
			var opt = dropdownElem.options[dropdownElem.selectedIndex];
			if(opt.value == 'D') {
				showHideAnimation(otherEvalProcName, 'show');
			} else {
				showHideAnimation(otherEvalProcName, 'hide');
			}
			if(opt.value != 'D') {
				dojo.attr(scheduleOther, 'value', '');
			}
		}
		return true;
	} catch(error) {
		console.debug('Error in setOtherSchedule', error);
	}
	return false;
}

//Hide or show the "Other" text field in "Person Responsible" for tab number i
function setOtherPersonResp(i) {
	var checkboxName = "iep_form_004_goal_"+i+"-person_responsible-O";
	var otherPersonRespName = 'otherPersonResp' + i;
	var otherFieldName = "iep_form_004_goal_"+i+"-person_responsible_other";
	var otherEvalProcName = 'otherEvalProc' + i;
	
	var otherPersonRespCheckbox = dojo.byId(checkboxName);
//	console.debug('otherEvalProcName', i);
//	console.debug('otherEvalProcName', i, otherEvalProcName);
	if(otherPersonRespCheckbox)
	{
		if(otherPersonRespCheckbox.checked) {
			showHideAnimation(otherPersonRespName, 'show');
//			fadeNode(otherPersonRespName, 'in'); 
		} else {
			if(dojo.byId(otherFieldName)) {
				dojo.byId(otherFieldName).value = '';
			}
//			if(otherEvalProcName) {
//				showHideAnimation(otherEvalProcName, 'hide');
//			}
			if(otherPersonRespName) {
				showHideAnimation(otherPersonRespName, 'hide');
			}
			
//			fadeNode(otherPersonRespName, 'out'); 
		}
	}
}

//Hide or show the "Other" text field in "Evaluation Process" for tab number i
function setOtherEvalProc(i) {
	var checkboxName = "iep_form_004_goal_"+i+"-eval_procedure-H";
	var otherEvalProcName = 'otherEvalProc' + i;
	var otherFieldName = "iep_form_004_goal_"+i+"-eval_procedure_other";
	
	
	var otherEvalProcCheckbox = dojo.byId(checkboxName);
	if(otherEvalProcCheckbox)
	{
		if(otherEvalProcCheckbox.checked) {		
			showHideAnimation(otherEvalProcName, 'show');
//			fadeNode(otherEvalProcName, 'in'); 
		} else {
			if(dojo.byId(otherFieldName)) {
				dojo.byId(otherFieldName).value = '';
			}
			showHideAnimation(otherEvalProcName, 'hide');

			showHideAnimation(otherEvalProcName, 'hide');
//			fadeNode(otherEvalProcName, 'out'); 
		}
	}
}

function loadForm() {
    console.debug('running loadForm');
    var formID = dojo.byId("id_form_004").value;

    // ==============================================================================
    // xhrGet
    // load the main form from the server
    // ==============================================================================
        dojo.xhrGet( {
        
            
            // The following URL must match that used to test the server.
            url: "/ajax/getformiep/id/"+formID,
            handleAs: "json",
            load: function(responseObject, ioArgs) {
              // Now you can just use the object
              //console.dir(responseObject);  // Dump it to the console
//              items=responseObject.items;    
				//
				// after the page loads, disable the save button
				//
				console.debug('disable save button');
				
				var submitButton = dijit.byId('submitButton');
				
				console.debug('submit btn', submitButton, submitButton.getAttribute('disabled'));
				
				submitButton.setAttribute('disabled', false);
				
				return responseObject;
            }
            // More properties for xhrGet...
        });    
    // ==============================================================================
    // end xhrGet
    // ==============================================================================
    // ==============================================================================


}

function pageInit(){
	if(typeof(validationMsgs) !== 'undefined') { 
    	colorGoalTabs(validationMsgs['items']);
	}
}

function pageReload(returneditems)
{
    // called when page returns from save
	colorGoalTabs(returneditems[0]['validationArr']);                   
}

function colorGoalTabs(validationMsgs){
	var processedKeys = new Array();
	
	// make all tabs have black text
	var tabNumber = dijit.byId('iep_form_004_goal_tab_container').tablist.getChildren().length - 1;
	for(i=0;i<=tabNumber;i++)
	{
		colorTabText('iep_form_004_goal_tab_container', i, 'grey');
	}
	
	// color invalid tabs red
    dojo.forEach(validationMsgs, // loop through returned errors
        function(validationArr) {
	    	var match = /iep_form_004_goal_(.+)-/i.exec(validationArr['field'])
	    	if(match[1] > 0 && !processedKeys[match[1]]) 
	    	{
	    		colorTabText('iep_form_004_goal_tab_container', (match[1]-1), 'red');
	    		processedKeys[match[1]] = 1;
	    	}
        }
    );
}

function restoreScheduleDates(rownumber)
{
	
	try {
		schedule = dojo.byId('iep_form_004_goal_'+rownumber+'-schedule').value;
		//console.debug('restoreScheduleDates', rownumber, schedule);
		
		dateConf = dojo.date.locale.parse(dojo.byId('date_conference').value, {datePattern: "MM/dd/yyyy", selector: "date"});
		
		//console.debug('dateConf', dateConf);
		if('' == schedule) {
			alert('You must select a schedule before you can restore dates.');
			return true;
		}
		if('A' == schedule) {
			//console.debug('A');
			maxFieldsToFill = 6;
		} else if('B' == schedule) {
			//console.debug('B');
			maxFieldsToFill = 4;
		} else if('C' == schedule) {
			//console.debug('C');
			maxFieldsToFill = 2;
		} else if('D' == schedule) {
	        //console.debug('D');     
	        maxFieldsToFill = 1;
	    }
		
		nextFieldToUpdate = 1;
		for(var j=1; j<=6; j++) {
//			try {
//				schDate = dojo.date.stamp.fromISOString(dojo.byId('schRptDate_'+j).value);

				//console.debug('schRptDate_', $('#schRptDate_'+j).val());
				schDate = $.datepicker.parseDate('yy-mm-dd', $('#schRptDate_'+j).val());
				//console.debug('schDate', schDate);
				
				//console.debug('iep_form_004_goal_'+rownumber+'-progress_date'+nextFieldToUpdate, schDate, j, nextFieldToUpdate, dojo.byId('schRptDate_'+j).value);
				if(dateConf <= schDate) {
					//console.debug('use: ', schDate);
					
					// jquery datePicker
					$('#iep_form_004_goal_'+rownumber+'-progress_date'+nextFieldToUpdate).val($.datepicker.formatDate('mm/dd/yy', schDate));
					
					//dijit.byId('iep_form_004_goal_'+rownumber+'-progress_date'+nextFieldToUpdate).attr("value", schDate);
					nextFieldToUpdate++;
				}
//			} catch(error) {
//				console.debug('Error in restoreScheduleDates', error);
//			}
			if(nextFieldToUpdate > maxFieldsToFill) break;
		}
		//console.debug('clear fields bigger than: ', nextFieldToUpdate);
		if(nextFieldToUpdate <= 6) {
			for(var j=nextFieldToUpdate; j<=6; j++) {
//				fieldToClear = dijit.byId('iep_form_004_goal_'+rownumber+'-progress_date'+j);
				fieldToClear = $('#iep_form_004_goal_'+rownumber+'-progress_date'+j);
				if(fieldToClear) {
					//console.debug('clear field iep_form_004_goal_'+rownumber+'-progress_date'+j);
					fieldToClear.attr("value", null);
				}
			}
			
		}
		modified(); // enable save button
	} catch (error) {
		console.debug('Error in restoreScheduleDates');
	}
	

}

var connectionObjects = []; // variable containing datagrid connection objects

dojo.addOnLoad(setOtherFieldsDisplay);
dojo.addOnLoad(setOtherSchedules);
dojo.addOnLoad(connectOtherCheckboxes);
//dojo.addOnLoad(connectTabs);
dojo.addOnLoad(callPageInit);

//function connectTabs()
//{
//    test = dijit.byId("iep_form_004_goal_tab_container");
//    dojo.connect(test.tablist, 'onButtonClick', makesomething);
//}
//function makesomething(tab){
//	console.debug('tab', tab.id);
//	//iep_form_004_goal_tab_container_tablist_iep_form_004_goal_2
////	dijit.byId('iep_form_004_goal_tab_container_tablist_'+tab.id).innerDiv.style.backgroundColor='green';
//}

function insertGoalHelperText(editorID, msg)
{
//	console.debug('msg', editorID, msg);
//	console.debug(dojo.byId('iep_form_004_goal_1-measurable_ann_goal'));
}
