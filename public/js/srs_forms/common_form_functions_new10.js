function removeAllTabs(selector){
   for (var tabIndex=$('#'+selector).tabs('length')-1; tabIndex>=0; tabIndex--)
   {
	   deleteTab(selector, tabIndex); 
   }
}
function deleteTab(selector, tabIndex){
	$('#'+selector+' ul li').removeClass('ui-tabs-selected');
	$('#'+selector).tabs('select',0);
	$('#'+selector).tabs('remove',tabIndex);
}

dojo.require("dijit.Editor");
dojo.require("dijit._editor.plugins.AlwaysShowToolbar");
dojo.require("dojox.html.entities");
//dojo.require("dojox.editor.plugins.PasteFromWord");
//dojo.require("dijit._editor.plugins.FullScreen");
//dojo.require("dojox.editor.plugins.NormalizeStyle");
dojo.require("dijit._editor.plugins.ViewSource");
dojo.require("dojox.editor.plugins.PrettyPrint");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.Dialog");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.TextBox");
dojo.require("dijit.form.DateTextBox");
dojo.require("dijit.form.TimeTextBox");
dojo.require("dijit.form.ComboBox");

dojo.require("dojox.json.ref");
dojo.require("dojo.parser");
dojo.require("dojox.timing._base");

dojo.require("dojox.html._base");


//dojo.registerModulePath('soliant', '/js/dojo_development/soliant');
//dojo.require("soliant.editor.plugins.ProcessContent");
//dojo.require("soliant.editor.plugins.SrsNormalizeStyle");


/*
 * save progression
 * 
 * fn: attachSaveAction
 * 	fn: savePageDeferred
 *   fn: conditionalSave
 *    fn: save
 *     ---success: saveCallback
 *     ---failure: saveError
 * 
 */

//Prevent the backspace key from navigating back.
//$(document).keydown(function(e) {
//    console.debug('keydown');
//    var doPrevent;
//    if (e.keyCode == 8) {
//        var d = e.srcElement || e.target;
//        if (d.tagName.toUpperCase() == 'INPUT' || d.tagName.toUpperCase() == 'TEXTAREA') {
//            var focusedElement = $(d);
//            if(focusedElement.hasAttribute('type')) {
//                var type = focusedElement.attr('type').toUpperCase();
//                if('CHECKBOX'==type
//                    || 'RADIO'==type) {
//                    doPrevent = true;
//                } else {
//                    doPrevent = false;
//                }
//            } else {
//                doPrevent = false;
//            }
//        } else {
//        	doPrevent = true;
//        }
//    } else {
//        doPrevent = false;
//	}
//    if (doPrevent)
//        e.preventDefault();
//});
$(document).unbind('keydown').bind('keydown', function (event) {
    var doPrevent = false;
    if (event.keyCode === 8) {
        var d = event.srcElement || event.target;
        if ((d.tagName.toUpperCase() === 'INPUT' && (d.type.toUpperCase() === 'TEXT' || d.type.toUpperCase() === 'PASSWORD'))
            || d.tagName.toUpperCase() === 'TEXTAREA') {
            doPrevent = d.readOnly || d.disabled;
        }
        else {
            doPrevent = true;
        }
    }
    if (doPrevent) {
        event.preventDefault();
    }
});


// 20111021 jlavere
// ie8 helper var
var ie8KillBackButtonWarning = false; 


dojo.declare("my.TabContainer", dijit.layout.TabContainer, {
	doLayout: false,
    style: "width:700px;",
	_setupChild: function(child){
		// summary: same as normal addChild, but this one adds a setTitle
		// method to the ContentPane if it doesn't have one
		this.inherited(arguments);
		if(!child["setTitle"] && child.title){
			dojo.mixin(child,{ 
				setTitle: function(title){
					// summary: set the title (25 char max)
					this.title = title.substring(0, 25);
					this.controlButton.containerNode.innerHTML = title.substring(0, 25) || ""; 
				}
			});
		}
	}
});


var browserName = navigator.appName; 
var browserVer = parseInt(navigator.appVersion); 

if (document.getElementById && document.createElement 
		&& !(browserName == "Microsoft Internet Explorer" && browserVer <= 5)) {
    var useDOMCode = true;
} else {
    var useDOMCode = false;
}
//=========================================================================================================
// Global variables  -- OLD as of May 2006
var isNav, isIE, intervalID;
var coll = "";
var styleObj = "";
if (parseInt(navigator.appVersion) >= 4) {
    if (navigator.appName == "Netscape") {
        isNav = true;
    } else {
        isIE = true;
        coll = "all.";
        styleObj = ".style";
    }
}

//=========================================================================================================
//
// define PgDateTextBox inherited from dijit.form.DateTextBox
// cetralize code for processing db data flow in and out of 
// dynamically created date objects
//
dojo.declare("PgDateTextBox", dijit.form.DateTextBox, {
    postgresformat: {
        selector: 'date',
        datePattern: 'yyyy-MM-dd',
        locale: 'en-us'
    },
    value: "",
    // prevent parser from trying to convert to Date object
    postMixInProperties: function() { // change value string to Date object
        this.inherited(arguments);
        // convert value to Date object
        this.value = dojo.date.locale.parse(this.value, this.postgresformat);
    },
    // To write back to the server in Postgres format, override the serialize method:
    serialize: function(dateObject, options) {
        return dojo.date.locale.format(dateObject, this.postgresformat).toUpperCase();
    }
});

dojo.declare("SrsEditorTextBox", dijit.Editor, {
    value: "",
//    alwaysshowtoolbar:true,
//    extraPlugins: "['dijit._editor.plugins.AlwaysShowToolbar']",
//    height: "",
    onChange: function(v) {
		updateInlineValue(this.id, arguments[0]);
		modified();
	},
    // copy styles from the original widget
    style: "minHeight:10em",
    // add modified
    onClick: function(v) {
        //modified();
    },
    // prevent parser from trying to convert to Date object
    postMixInProperties: function() { // change value string to Date object
//        console.debug('SrsEditorTextBox:postMixInProperties');
    },
    // To write back to the server in Postgres format, override the serialize method:
    serialize: function(dateObject, options) {
//        console.debug('SrsEditorTextBox:serialize');
    }
});


function getTabTitleFromSubformName(subformName) {
	switch(subformName)
	{
		case 'ifsp_goals':
			var tabTitle = 'Goal ';
		  break;
		case 'iep_form_004_goal':
			var tabTitle = 'Goal ';
			break;
		case 'ifsp_services':
			var tabTitle = 'Service ';
			break;
		case 'form_002_suppform':
			var tabTitle = 'Supplemental Form ';
			break;
		case 'iep_form_004_suppform':
			var tabTitle = 'Supplemental Form ';
			break;
		default:
			var tabTitle = 'Supplemental Form ';
	}

	return tabTitle;
}

function conditionalSave(event, wait2finish, url) {

//	console.debug('conditionalSave');
	var deferred = false;
    var modFlag = false;
    $('.changed').each(function() {
    	//console.debug('found modified element');
        modFlag = 1;
    });

    // has the button been enabled?
    if(dijit.byId('submitButton') && dojo.hasClass('submitButton', 'enabledButton'))
	{
    	//console.debug('save button enabled');
    	modFlag = 1;
	}
    if(dijit.byId('submitButton3') && dojo.hasClass('submitButton3', 'enabledButton'))
	{
    	//console.debug('save button enabled');
    	modFlag = 1;
	}
    
    // if either indicator suggests the page is modified
    // save the page
    //
    //modFlag = 1; // always save
    if (modFlag) 
    {
    	// display the saving dialog
    	// this does not display if wait2finish is true
    	deferred = save(event, false, url);
    };
    // return the deferred object so we can add
    // additional callback functionality
    return deferred;
}


function save(event, wait2finish, url) {
	
	$("#jsubmit-header").focus(); // be sure to exit all fields.focus on the submit button
	$("#jsubmit-header").blur();

    try {
		// display the saving dialog
		// this does not display if wait2finish is true -- not sure this is true
		$.blockUI({ 
			message: 'Your page is saving...'
		});

    } catch (error) {
    	console.debug('blockIU not loading');
    }
	
	//Stop the submit event since we want to control form submission.
	if(event != null) event.preventDefault();
	if(event != null) event.stopPropagation();

    // stop checkout timer
    ClearCountDown('clock1', 'Saving Form');
    
	// fade the navigation toolbar so user cannot 
	// navigate while page is saving
    fadeNode('page_navigation_controlbar', 'out');
    
    var wait2finish = (wait2finish == true) ? true : false;
    //The parameters to pass to xhrPost, the form, how to handle it, and the callbacks.
    //Note that there isn't a url passed.  xhrPost will extract the url to call from the form's
    //'action' attribute.  You could also leave off the action attribute and set the url of the 
    //xhrPost object either should work.
    var xhrArgs = {
        form: dojo.byId("myform"),
        handleAs: "json",
        url: url,
        //sync: wait2finish, // should we wait till the call is done before continuing
        sync: false,
        load: saveCallback,
        error: saveError
    };
    dojo.publish("/form_timer", ["saving"]);
    
    dojox.html.set(dojo.byId("serverInteractionProgressMsg"), 'Form being saved....');
    fadeNode('serverInteractionProgressMsg', 'in');
    
    dojox.html.set(dojo.byId("response"), 'Form being sent...');
    
    dojo.byId('returnResult').value = 'connecting'; // element defined in edit.phtml

    //Call the asynchronous xhrPost
    var deferred = dojo.xhrPost(xhrArgs);
    
    return deferred;

}

var dijitsToDestroy = [];
function downWardDijits(tagId) {
	
//	console.debug('downWardDijits', tagId);
	
	if(foundDijit = dijit.byId(tagId)) 
	{	
		foundDom = dojo.byId(tagId)
//		console.debug('FOUND DIJIT', foundDijit.id);
		
		// add dijit to list of elements to be destroyed
		dijitsToDestroy.push(foundDijit.id);
		
		for (i=0;i<foundDom.childNodes.length;i++)
		{
			if(foundDom.childNodes[i].id) downWardDijits(foundDom.childNodes[i].id);
		}
		dojo.forEach(
			function(selectTag) {
				downWardDijits(selectTag.id);
			}
		);
		
	} else if (foundElement = dojo.byId(tagId)) {
//		console.debug('FOUND DOM ELEMENT', foundElement);
//		console.debug(foundElement, 'FOUND DOM ELEMENT', foundElement.id, foundElement.getChildren());
		dojo.query('[widgetid]', dojo.byId(tagId)).forEach(
			function(childNode) {
//				console.debug('childNode destroy', childNode.id, childNode.widgetid);
				if(childNode.id) {
//					console.debug('childNode destroy', childNode.id, childNode.widgetid);
//					dojo.destroy(childNode.id);
				}
				if(childNode.widgetid) {
//					console.debug('childNode destroy', childNode.id, childNode.widgetid);
//					dojo.destroy(childNode.id);
				}
				
//				downWardDijits(selectTag.id);
			}
		);
		
	} else {
//		console.debug('ELEMENT NOT FOUND');
	}
}

function destroyWidgets() {
	downWardDijits("iep_form_004_goal_tab_container");
	
	for(i=0;i<dijitsToDestroy.length;i++) {
//		console.debug('destroy:', dijitsToDestroy[i]);
		if('iep_form_004_goal_tab_container' != dijitsToDestroy[i]) {
			if(foundDijit = dijit.byId(dijitsToDestroy[i])) foundDijit.destroy();
		}
	}
}

function domDestroyRecursive(node, subformName) {
	if(undefined != node) {
	    dojo.forEach(node.childNodes, function(childNode){
	    	if(	undefined != childNode &&
	    		childNode.id && 
	    		subformName == childNode.id.substring(0, subformName.length) && 
	    		undefined != dijit.byId(childNode.id)
	    	) {
	    		dijit.byId(childNode.id).destroyRecursive();
//	    		console.debug('destroy recursive', childNode.id);
	    	}
	    	domDestroyRecursive(childNode, subformName);
	    });      
	}
}
function saveCallback(data) {

    /*
     * Catch for users trying to save finalzed forms
     */
    if (data.finalizedError) {
        var error = {
           name: 'Finalize Error',
           description: 'Form has already been finalized.',
           line: 'N/A',
           message: 'Trying to save a form that has already been finalized.'
        };
	alert("An exception occurred in the script. Error name: "
                         + error.name + ". Error description: "
                         + error.description + ". Error line: " + error.line
                         + ". Error message: " + error.message);

        window.location.href='/'+data.form+'/view/document/'+data.document+'/page/'+data.page;
    }
 
//	console.debug('saveCallback');
    dojox.html.set(dojo.byId("serverInteractionProgressMsg"), 'Form successfully saved');
	
	fadeNode('serverInteractionProgressMsg', 'out', 5000);
	dojox.html.set(dojo.byId("response"), 'Form posted.');    
	
    // get items from the json object
    var returneditems = data.items;

    if(null == returneditems[0]['validationArr'])
    {
        var errorCount = 0;
    } else {
        var errorCount = returneditems[0]['validationArr'].length;
    }
    
    // update the checkout time
    dojo.byId('zend_checkout_time').value = returneditems[0]['zend_checkout_time'];
    reset_cd(returneditems[0]['zend_checkout_duration']);
//    dojox.html.set(dojo.byId("form_timer_server"), '<SCRIPT language="JavaScript" SRC="/countdown/countdown/countto/2012-01-01 00:00:00"></SCRIPT>');
    
    // timer format changed.. moved to js file included in header
//    startTimer();
    
    /**
     * update the Draft[123...] display
     */
    $('#pagesValid').parent().html($(returneditems[0]['pageValidationList']));
    $('#pagesValidTop').parent().html($(returneditems[0]['pageValidationList']));
    $('#pagesValidBottom').parent().html($(returneditems[0]['pageValidationList']));

    if(null != returneditems[0]['subform_options'])
    {	
    	if('tinyMce'==$('input:radio[name=form_editor_type]:checked').val()) {
    		//console.debug('tinyMce');
    		if($('.jTabContainer').length) {
    			console.debug('jTabs');
    		}
	    	// loop through subforms that have had rows deleted
	    	for(var subformName in returneditems[0]['subform_options'])
	    	{    	
	    		if(0==$('.jTabContainer').length) {
	    			/*
	    			 * no tab container found
	    			 * subforms are contained in table rows 
	    			 */
	                var parentContainer = $('#'+subformName+"_parent");
	                //console.debug('parentContainer', parentContainer);
	                
	                /*
	                 * destroy the existing widgets with this subformName prefix
	                 * then remove all the dom elements below the parent container
	                 * 
	                 * tinyMce must be remove and reinitted before this process
	                 */

                    $.each(parentContainer.find('textarea:tinymce'), function(index, ele) {
                        var id = $(this).attr('id');
                        tinymce.execCommand('mceRemoveControl',false, id);
                    });
	                parentContainer.empty();
	                //console.debug('parentContainer removed', parentContainer);

	                /*
	                 * replace each tab and parse the html
	                 */
					if(null == returneditems[0]['subform_options'][subformName]['new_html'])
					{
						// update tab count with 0
						$('#'+subformName+'-count').val(0);
					} else {
						/*
						 * for each subform returned
						 * build a contentPane and load into the existing tabContainer
						 */
						for(var rowNum in returneditems[0]['subform_options'][subformName]['new_html'])
						{
							var newRowId = subformName+'_'+rowNum;
//							console.debug('parentContainer', parentContainer);
//							console.debug('newRowId', newRowId);
							parentContainer.append('<tr><td  id="'+newRowId+'">'+returneditems[0]['subform_options'][subformName]['new_html'][rowNum]+'</td></tr>')
						}
						// update tab count with count of built tabs
						$('#'+subformName+'-count').val(rowNum);

                        console.debug('new', parentContainer, parentContainer.find('textarea'));
                        initTinyMce(parentContainer.find('textarea'));
//                        // init jquery datepickers
//                        $( ".datepicker" ).datepicker({
//                            changeMonth: true,
//                            changeYear: true
//                        });

                    }
	    		} else {

                    // hack redirect until fix to delete goal is resolved
                    window.location.href=window.location.href;
                    return;

//	    			/*
//	    			 * tabContainer found
//	    			 * remove all tabs
//	    			 */
//		    		var tabContainer = $('#'+subformName+"_tab_container");
//		    		removeAllTabs(subformName+"_tab_container", $('#'+subformName+'-count').val());
//		    		/*
//	                 * buid new jquery tabs
//	                 */
//					if(null == returneditems[0]['subform_options'][subformName]['new_html'])
//					{
//						// update tab count with 0
//						$('#'+subformName+'-count').val(0);
//					} else {
//						/*
//						 * for each subform returned
//						 * build a contentPane and load into the existing tabContainer
//						 */
//						for(var tabNum in returneditems[0]['subform_options'][subformName]['new_html'])
//						{
//				    		var tabTitle = getTabTitleFromSubformName(subformName)+tabNum;
//							var newTabId = subformName+'_'+tabNum;
//							tabContainer.append('<div id="'+newTabId+'">'+returneditems[0]['subform_options'][subformName]['new_html'][tabNum]+'</div>')
//				            tabContainer.tabs("add",'#'+newTabId, tabTitle);
//						}
//						// update tab count with count of built tabs
//						$('#'+subformName+'-count').val(tabNum);
//					}
	    		}
	    	}
    		
    	} else {
    	
	    	// loop through subforms that have had rows deleted
	    	for(var subformName in returneditems[0]['subform_options'])
	    	{    	
	    		if(dijit.byId(subformName+"_tab_container"))
	    		{
	    			tabContainerFound = true;
	    		} else {
	    			tabContainerFound = false;
	    		}
	    		if(!tabContainerFound) {
	    			/*
	    			 * no tab container found
	    			 * subforms are contained in table rows 
	    			 */
	                var node = dojo.byId(subformName+"_parent");
	                
	                /*
	                 * destroy the existing widgets with this subformName prefix
	                 * then remove all the dom elements below the parent container
	                 */
	                domDestroyRecursive(node, subformName);
	                dojo.empty(subformName+"_parent");
					
	                /*
	                 * replace each tab and parse the html
	                 */
	        		for(var tabNum in returneditems[0]['subform_options'][subformName]['new_html'])
					{	
						var uniqueRowId = 'refresh_me_'+subformName+'_'+tabNum;
						insertRow = '<tr><td id="'+uniqueRowId+'">' + returneditems[0]['subform_options'][subformName]['new_html'][tabNum] + '</td></tr>';
		        		dojo.place(insertRow, node);
		        		dojo.parser.parse(dojo.byId(uniqueRowId));
					}
	        		
	        		/*
	        		 * update the hidden element with the new count of subform rows
	        		 */
	        		var countVarName = subformName+'-count';
	        		if(null == returneditems[0]['subform_options'][subformName]['new_html'])
					{
						dojo.byId(countVarName).value = 0;
					} else {
						for(var tabNum in returneditems[0]['subform_options'][subformName]['new_html'])
						{
	//						console.debug('tabnum', tabNum);
						}
						dojo.byId(countVarName).value = tabNum;
					}
	        		
	        		
	    		} else {
	    			/*
	    			 * tabContainer found
	    			 * remove each of the contentPanes from the tabContainer
	    			 * and destroy the widgets that were in them
	    			 */
		    		var tc = dijit.byId(subformName+"_tab_container");
		    		dojo.forEach(tc.getChildren(), function(tabNode){
		    			tc.removeChild(tabNode);
		    			tabNode.destroyRecursive();
		    		});
		    		
	    			//We want to refresh the tabs    
					// build and insert a content pane
		    		/*
		    		 * hard coded tab titles
		    		 * should move to the return data 
		    		 */
	//	    		console.debug('subformName', subformName);
	
	                if (subformName == 'ifsp_goals')
	                    var tabTitle = 'Goal ';
	                else if (subformName == 'iep_form_004_goal')
	                    var tabTitle = 'Goal ';
	                else if (subformName == 'ifsp_services')
	                    var tabTitle = 'Service ';
	                else if (subformName == 'form_002_suppform')
	                    var tabTitle = 'Supplemental Form ';
	                else if (subformName == 'iep_form_004_suppform')
	                	var tabTitle = 'Supplemental Form ';
	                else
					    var tabTitle = 'Tab ';
	
	                /*
	                 * update the form with the new html
	                 */
					if(null == returneditems[0]['subform_options'][subformName]['new_html'])
					{
						var countVarName = subformName+'-count';
						dojo.byId(countVarName).value = 0;
					} else {
						/*
						 * for each subform returned
						 * build a contentPane and load into the existing tabContainer
						 */
						for(var tabNum in returneditems[0]['subform_options'][subformName]['new_html'])
						{
							var tabname = subformName+'_'+tabNum;
							if(foundDijit = dijit.byId(tabname)) {
	//							console.debug('destroy');
								foundDijit.destroy();
							}
	//						console.debug('post destroy');
	//						console.debug('tabname', tabname);
	//						console.debug('subformName', subformName);
							tab = new dijit.layout.ContentPane(
							    //properties
								{ 		
									title:tabTitle + tabNum,
									id:subformName + '_' + tabNum,
	//		    					parseOnLoad: true
	//					            title: "Food",
						            content: ""
						        },
								tabname
							);
	//						console.debug('post create');
	
							tab.setContent(returneditems[0]['subform_options'][subformName]['new_html'][tabNum]);
							tc.addChild(tab, tabNum);
						}
						/*
						 * update the hidden element with the new count of subform rows
						 */
						var countVarName = subformName+'-count';
						dojo.byId(countVarName).value = tabNum;
						// append stylesheets
						addEditorStyleSheets(subformName, tabNum);
						tab.startup(); 
					}	
	    		}
	    	}
	    	//refresh_dijits(subformName, 'all');
    	}
    }

//	// grey out save button until changes have been made
//    // should this be done when save is hit? or after we confirm save?
//    var submitButton = dijit.byId('submitButton');
//	dojo.removeClass('submitButton', 'enabledButton');
//    if(false == submitButton.disabled)
//	{
//    	// submitButton.setAttribute('disabled', true);
//    }

//    var submitButton2 = dijit.byId('submitButton2');
    
    // restore the navigation toolbar
	dojo.fadeIn({
		node:'page_navigation_controlbar'
	}).play();
//
//	var submitButton3 = dijit.byId('submitButton3');
//	dojo.removeClass('submitButton3', 'enabledButton');
	
    
	validationPostSave(errorCount, returneditems);

	// unset the modified variable
	// this allows modFlag aware functions
	// to be reset
	resetModFlag();
	
    try {
    	callPageReload(returneditems);
    } catch (error) {
    	//execute this block if error
    }

	dojo.byId('returnResult').value = 'success'; // element defined in edit.phtml
    
    updateFadingOtherTextboxes();
    
    /*
     * forced refresh
     * if needed, refresh the page
     */
    if(dojo.byId('refresh_page')) {
	    if('true' == dojo.byId('refresh_page').value) {
	    	location.reload(true);
	    }
    }
    if(clearRadioButtons = dojo.byId('clearRadioButtons')) {
    	// iep page 1
    	if(dojo.byId('clearRadioButtons').checked) {
//    		console.debug('clearRadioButtons', clearRadioButtons.value);
    		location.reload(true);
    	}
    }
    
//	// hide the saving dialog
//	dijit.byId('savingDialog').hide();

}

function validationPostSave(errorCount, returneditems) {
//	console.debug('validationPostSave');
	// RESTORE ALL MODIFIED DIVS TO THEIR ORIGINAL COLOR
    // remove the changed class from these elements
//	dojo.query(".colorme").removeClass("changed");
//	dojo.query(".colorme").removeClass("errored");

	$('.colorme').removeClass("changed").removeClass("errored").removeClass("changedOrange").removeClass("changedRed");

    // VALIDATION LIST
    // this is the list of validation errors
    // update the validation list display table
    // clear the validation error list table
    $("#validationList").html(makeValidationListTable(errorCount, returneditems[0]['validationArr']));
    
    colorInvalidFields(errorCount, returneditems[0]['validationArr']);
    //
    // clear the validation status display table
    // this is the link to display the validation errors
    //
    $("#validationStatusSpan").html(makeValidationStatusTable(errorCount))
    
    if(typeof colorTabs == 'function') {
    	colorTabs();
    } 
}

function saveError(error) {
    //We'll 404 in the demo, but that's okay.  We don't have a 'postIt' service on the
    //docs server.
//    dojo.byId("response").innerHTML = "Form error.<br/>"+error;
	dojox.html.set(dojo.byId("response"), "Form error.<br/>"+error);    

    alert("An exception occurred in the script. Error name: " 
  		  	 + error.name + ". Error description: " 
  		  	 + error.description + ". Error line: " + error.line
  			 + ". Error message: " + error.message);
    
//    dojo.byId('returnResult').value = 'failure'; // element defined in edit.phtml

    // fade the navigation toolbar so user cannot 
	// navigate while page is saving
    fadeNode('page_navigation_controlbar', 'in');

	// hide the saving dialog
//	dijit.byId('savingDialog').hide();
    try {
    	$.unblockUI();// unblock the UI
    } catch (error) {
    	console.debug('blockIU not loading');
    }
	

}

function removeHTMLTags(text) {
	var strInputCode = text;
	/*
	This line is optional, it replaces escaped brackets with real ones,
	i.e. < is replaced with < and > is replaced with >
	*/
	strInputCode = strInputCode.replace(/&(lt|gt);/g, function (strMatch, p1) {
		return (p1 == "lt")? "<" : ">";
	});
 
	var strTagStrippedText = strInputCode.replace(/<\/?[^>]+(>|$)/g, "");
	//alert("Output text:\n" + strTagStrippedText);
	// Use the alert below if you want to show the input and the output text
	// alert("Input code:\n" + strInputCode + "\n\nOutput text:\n" + strTagStrippedText);
	return strTagStrippedText;
}


function modified(docRoot, area, sub, keyName, id, page) {
	//console.group('function: modified()');
    modFlag = 1;

    obj = eval("document." + coll + "revert");
    if (obj) {
        obj.innerHTML = "<a accesskey=\"r\" href=\"javascript:recordAction(document.forms[0], 'revert', '" + docRoot + "', '" + area + "', '" + sub + "', '" + keyName + "', '" + id + "', '" + page + "');\" onMouseOver=\"javascript:window.status='Click here to revert to the most previously saved version of this record.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_revert.gif\" alt=\"Revert\" title=\"Revert (shortcut key = R)\"></a>";
    } else {
        var SpanElemRef = document.getElementById("revert");

        if(SpanElemRef) {
            var img_el = document.createElement('img');
            img_el.src = "/images/button_revert.gif";
            img_el.alt = "Revert";
            img_el.title = "Revert (shortcut key = R)";
    
            var link_el = document.createElement("a"); // link element
            link_el.accesskey = "r";
            link_el.href = "javascript:recordAction(document.forms[0], 'revert', '" + docRoot + "', '" + area + "', '" + sub + "', '" + keyName + "', '" + id + "', '" + page + "');";
            link_el.onMouseOver = "javascript:window.status='Click here to revert to the most previously saved version of this record.'; return true;";
            link_el.onMouseOut = "javascript:window.status=''; return true;";
            link_el.appendChild(img_el);
            
            SpanElemRef.replaceChild(link_el, SpanElemRef.childNodes[0]);
        }
    }
    obj = eval("document." + coll + "revert_bottom");
    if (obj) {
        obj.innerHTML = "<a accesskey=\"r\" href=\"javascript:recordAction(document.forms[0], 'revert', '" + docRoot + "', '" + area + "', '" + sub + "', '" + keyName + "', '" + id + "', '" + page + "');\" onMouseOver=\"javascript:window.status='Click here to revert to the most previously saved version of this record.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_revert.gif\" alt=\"Revert\" title=\"Revert (shortcut key = R)\"></a>";
    } else {
        var SpanElemRef = document.getElementById("revert_bottom");
        if(SpanElemRef) {
            var img_el = document.createElement('img');
            img_el.src = "/images/button_revert.gif";
            img_el.alt = "Revert";
            img_el.title = "Revert (shortcut key = R)";
    
            var link_el = document.createElement("a"); // link element
            link_el.accesskey = "r";
            link_el.href = "javascript:recordAction(document.forms[0], 'revert', '" + docRoot + "', '" + area + "', '" + sub + "', '" + keyName + "', '" + id + "', '" + page + "');";
            link_el.onMouseOver = "javascript:window.status='Click here to revert to the most previously saved version of this record.'; return true;";
            link_el.onMouseOut = "javascript:window.status=''; return true;";
            link_el.appendChild(img_el);
            
            SpanElemRef.replaceChild(link_el, SpanElemRef.childNodes[0]);
        }
    }
    obj = eval("document." + coll + "save");
    if (obj) {
        if( 'nssrs' == sub) {
            obj.innerHTML = "<a accesskey=\"s\" href=\"javascript:document.forms[0].submissionAction.value='save';recordAction(document.forms[0], 'save');\" onMouseOver=\"javascript:window.status='Test Click here to save.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_save.gif\" alt=\"Save\" title=\"Save (shortcut key = S)\"></a>";
        } else {
            obj.innerHTML = "<a accesskey=\"s\" href=\"javascript:recordAction(document.forms[0], 'save');\" onMouseOver=\"javascript:window.status='Test Click here to save.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_save.gif\" alt=\"Save\" title=\"Save (shortcut key = S)\"></a>";
        }
    } else {
        //
        // 20090601 jlavere - dojo upgrade
        // enable the save button
        //
        var submitButton = dijit.byId('submitButton');
//        var submitButton2 = dijit.byId('submitButton2');
        var submitButton3 = dijit.byId('submitButton3');
        //console.debug('submitButton.disabled', submitButton.disabled);
        if(submitButton && submitButton.disabled)
    	{
        	//console.debug('save button disabled status: ', submitButton.disabled, 'enable save button');
        	submitButton.setAttribute('disabled', false);
        	//console.debug(submitButton.disabled);
        }
        if(submitButton)
    	{
        	//console.debug('enable');
        	submitButton.setAttribute('disabled', false);
        	dojo.addClass('submitButton', 'enabledButton');
        }
        if(submitButton3)
    	{
        	//console.debug('enable');
        	submitButton3.setAttribute('disabled', false);
        	dojo.addClass('submitButton3', 'enabledButton');
        }
    }


    obj = eval("document." + coll + "saveandsaveparent"); // accomodations checklist
    if (obj) {
        obj.innerHTML = "<a accesskey=\"s\" href=\"javascript:parentSave();recordAction(document.forms[0], 'save');\" onMouseOver=\"javascript:window.status='Test Click here to save.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_save.gif\" alt=\"Save\" title=\"Save (shortcut key = S)\"></a>";
    } else {
        var SpanElemRef = document.getElementById("saveandsaveparent");


        if(SpanElemRef) {
            var img_el = document.createElement('img');
            img_el.src = "/images/button_save.gif";
            img_el.alt = "Save";
            img_el.title = "Save (shortcut key = S)";
    
            var link_el = document.createElement("a"); // link element
            link_el.accesskey = "s";
            link_el.href = "javascript:parentSave();recordAction(document.forms[0], 'save', '" + docRoot + "', '" + area + "', '" + sub + "', '" + keyName + "', '" + id + "', '" + page + "');";
            link_el.onMouseOver = "javascript:window.status='Click here to save.'; return true;";
            link_el.onMouseOut = "javascript:window.status=''; return true;";
            link_el.appendChild(img_el);
            
            SpanElemRef.replaceChild(link_el, SpanElemRef.childNodes[0]);
        }
    }


    obj = eval("document." + coll + "save_bottom");
    if (obj) {
        obj.innerHTML = "<a accesskey=\"s\" href=\"javascript:recordAction(document.forms[0], 'save');\" onMouseOver=\"javascript:window.status='Test 2 Click here to save.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_save.gif\" alt=\"Save\" title=\"Save (shortcut key = S)\"></a>";
    } else {
        var SpanElemRef = document.getElementById("save_bottom");


        if(SpanElemRef) {
            var img_el = document.createElement('img');
            img_el.src = "/images/button_save.gif";
            img_el.alt = "Save";
            img_el.title = "Save (shortcut key = S)";
    
            var link_el = document.createElement("a"); // link element
            link_el.accesskey = "s";
            link_el.href = "javascript:recordAction(document.forms[0], 'save', '" + docRoot + "', '" + area + "', '" + sub + "', '" + keyName + "', '" + id + "', '" + page + "');";
            link_el.onMouseOver = "javascript:window.status='Click here to save.'; return true;";
            link_el.onMouseOut = "javascript:window.status=''; return true;";
            link_el.appendChild(img_el);
            
            SpanElemRef.replaceChild(link_el, SpanElemRef.childNodes[0]);
        }
    }
    obj = eval("document." + coll + "print");
    if (obj) {
        obj.innerHTML = "<a onMouseOver=\"javascript:window.status='Record must be saved before it can be printed.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_print_off.gif\" alt=\"Print\" title=\"Record must be saved before it can be printed.\"></a>";
    } else {
        var SpanElemRef = document.getElementById("print");


        if(SpanElemRef) {
            var img_el = document.createElement('img');
            img_el.src = "/images/button_print_off.gif";
            img_el.alt = "Print";
            img_el.title = "Record must be saved before it can be printed.";
    
            var link_el = document.createElement("a"); // link element
            link_el.accesskey = "s";
            link_el.onMouseOver = "javascript:window.status='Record must be saved before it can be printed.'; return true;";
            link_el.onMouseOut = "javascript:window.status=''; return true;";
            link_el.appendChild(img_el);
            
            SpanElemRef.replaceChild(link_el, SpanElemRef.childNodes[0]);
        }
    }
    obj = eval("document." + coll + "print_bottom");
    if (obj) {
        obj.innerHTML = "<a onMouseOver=\"javascript:window.status='Record must be saved before it can be printed.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_print_off.gif\" alt=\"Print\" title=\"Record must be saved before it can be printed.\"></a>";
    } else {
        var SpanElemRef = document.getElementById("print_bottom");


        if(SpanElemRef) {
            var img_el = document.createElement('img');
            img_el.src = "/images/button_print_off.gif";
            img_el.alt = "Print";
            img_el.title = "Record must be saved before it can be printed.";
    
            var link_el = document.createElement("a"); // link element
            link_el.accesskey = "s";
            link_el.onMouseOver = "javascript:window.status='Record must be saved before it can be printed.'; return true;";
            link_el.onMouseOut = "javascript:window.status=''; return true;";
            link_el.appendChild(img_el);
            
            SpanElemRef.replaceChild(link_el, SpanElemRef.childNodes[0]);
        }
    }
    obj = eval("document." + coll + "hrefPrint");
    if (obj) {
        obj.innerHTML = "<a onMouseOver=\"javascript:window.status='Record must be saved before it can be printed.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\">Print</a>";
    } else {
        var SpanElemRef = document.getElementById("hrefPrint");


        if(SpanElemRef) {
            var text_el = document.createTextNode("Print"); // link element
            var link_el = document.createElement("a"); // link element
            link_el.accesskey = "s";
            link_el.setAttribute('onMouseOver', "javascript:window.status='Record must be saved before it can be printed.'; return true;");
            link_el.onMouseOut = "javascript:window.status=''; return true;";
            link_el.appendChild(text_el);
            
            SpanElemRef.replaceChild(link_el, SpanElemRef.childNodes[0]);
        }
    }
    // code is non functional due to commenting out of print_bottom object.
    obj = eval("document." + coll + "print_bottom");
    if (obj) {
        obj.innerHTML = "<a onMouseOver=\"javascript:window.status='Record must be saved before it can be printed.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"/images/button_print_off.gif\" alt=\"Print\" title=\"Record must be saved before it can be printed.\"></a>";
    } else {
        var SpanElemRef = document.getElementById("print_bottom");


        if(SpanElemRef) {
            var img_el = document.createElement('img');
            img_el.src = "/images/button_print_off.gif";
            img_el.alt = "Print";
            img_el.title = "Record must be saved before it can be printed.";
    
            var link_el = document.createElement("a"); // link element
            link_el.accesskey = "s";
            link_el.onMouseOver = "javascript:window.status='Record must be saved before it can be printed.'; return true;";
            link_el.onMouseOut = "javascript:window.status=''; return true;";
            link_el.appendChild(img_el);
            
            SpanElemRef.replaceChild(link_el, SpanElemRef.childNodes[0]);
        }
    }
//    console.debug('end of modified.');
    
    // enable all jsave buttons
//    console.debug($('.jsavebutton').attr("disabled"));
    $('.jsavebutton').attr('disabled', false).css('color', 'black');
//    console.debug($('.jsavebutton').attr("disabled"));
    
    return true;
}

function displayValidationList() {
    //console.debug('val list: ', dojo.byId('validationList').style.display);
    if (dojo.byId('validationList').style.display == 'inline') {
        dojo.byId('validationList').style.display = 'none';
    } else {
        dojo.byId('validationList').style.display = 'inline';
    }
}

function hideAddButton(buttonId) {
	dojo.byId(buttonId).style.display = 'none';
}




function makeValidationListTable(errorCount, errorArray)
{	
	//
	// builds html table of error messages about field validity
	//
	//console.group('function:: makeValidationListTable')
    //console.debug('errorArray: ', errorArray);
    
    var valTable=document.createElement("Table");
    valTable.id = 'validationList';
    valTable.style.display = 'none';
    
    var tbody = document.createElement("tbody");
    valTable.appendChild(tbody);
    
//    var  oTR= valTable.insertRow(0);
//    var  oTD= oTR.insertCell(0);


    if(0 == errorCount)
    {
    	//console.debug('errorCount none: ', errorCount);
//    	row1.setAttribute('class', "bts");
//    	cell1.innerHTML = 'nothing here';
        var addRows = false;
    } else {
	    var row1 = document.createElement("TR");                         // create row element
	    var cell1 = document.createElement("TD");                       // create cell element
    	//console.debug('errorCount: ', errorCount);
    	cell1.innerHTML = '<span class="btsb">Checklist</span>:&nbsp;This page is valid for draft status but can&rsquo;t be finalized until the following issues are resolved:';
    	cell1.setAttribute('class', "bts");
    	cell1.align = 'left';
    	cell1.setAttribute('colspan', "2");
    	cell1.setAttribute('style', "width: 100%;");
        var addRows = true;
	    row1.appendChild(cell1);                                         // add cell to row
	    tbody.appendChild(row1);                                         // add row to table body
    }
//    class="bts" align="left" colspan="2" style="width: 100%;"
//    tbody.appendChild(oTD); 

    if(addRows)
    {
	    dojo.forEach(errorArray,                                                // loop through returned errors
		        function(valRow) {
		            //console.debug('building error text');
		            var rowCount = valTable.getElementsByTagName("TR").length;      // get row count of validationList
		
		            //console.debug('tbody3', tbody);
		            var row = document.createElement("TR");                         // create row element
		            var cell1 = document.createElement("TD");                       // create cell element
		            // add error message to the cell
		            cell1.innerHTML = "&#8226;";
		            cell1.setAttribute('valign', "top");
		            var cell2 = document.createElement("TD");                       // create cell element
		            // add error message to the cell
		            cell2.innerHTML = '<B>'+valRow['label']+'</B> ' + valRow['message'];
		            cell2.setAttribute('style', "width: 100%;");
		            row.appendChild(cell1);                                         // add cell to row
		            row.appendChild(cell2);                                         // add cell to row
		            tbody.appendChild(row);                                         // add row to table body
		            //console.debug('build error appendChild - END - this is good');
		        }
		    );
    }
    
    //console.groupEnd();
    return tbody;
}

function colorInvalidFields(errorCount, errorArray)
{	
	// changes color of divs on the page where the field is not valid for finalization
    if(0 == errorCount)
    {
    	return;
    } else {
    	$.each(errorArray, function(index, valRow) {
    		//console.debug(index, valRow);
    		colorMeRed(valRow['field'], valRow['wrapper']);
    	});
    }
}

function makeValidationStatusTable(errorCount)
{
	//console.group('function:: makeValidationStatusTable');
    var oTbl=document.createElement("Table");
    oTbl.id = 'validationStatus';
    oTbl.setAttribute('class', "formTop");
    var tbody = document.createElement("Body");
    oTbl.appendChild(tbody);
    
//    var  oTR= oTbl.insertRow(0);
//    var  oTD= oTR.insertCell(0);
    
    var row1 = document.createElement("TR");                         // create row element
    var cell1 = document.createElement("TD");                       // create cell element
	cell1.setAttribute('align', "left");
	cell1.setAttribute('class', "bts");
	cell1.setAttribute('nowrap', "nowrap");

    if(0 == errorCount)
    {
    	cell1.innerHTML = '<span class=\"btsb\">Status</span>: <span class=\"btsbRed\">Draft</span> (page is OK)';
    } else {
    	cell1.innerHTML = '<span class=\"btsb\">Status</span>: <span class=\"btsbRed\">Draft</span> (<a accesskey="v" href="javascript:displayValidationList();" onMouseOver="javascript:window.status=\'Toggle Validation Issues\'; return true" onMouseOut="javascript:window.status=\'\'; return true;"><span class="btsLBlue" style="text-decoration:underline;">click here</span></a> to view checklist)';
    }
//    nowrap="nowrap" align="left" class="bts"
    row1.appendChild(cell1);                                         // add cell to row
    tbody.appendChild(row1);                                         // add row to table body

    //console.groupEnd();
    return oTbl;
}




function enableSave()
{
//	console.debug('function:: enableSave()');
	var savebtn = dijit.byId('submitButton');
	if(savebtn.disabled)
	{
		// button is disabled
		savebtn.setAttribute('disabled', false);
	}
}
function disableSave()
{
	//console.debug('function:: disableSave()');
	var savebtn = dijit.byId('submitButton');
	if(!savebtn.disabled)
	{
		// button is disabled
		savebtn.setAttribute('disabled', true);
	}
}

function selectPage(event, url, button)
{
	
	// set the action to be passed when page is submitted
	dojo.byId('changePageAction').value = 'select';

//	console.debug('nextPage');
	var deferred = conditionalSave(event, false, url);
	if(false != deferred) {
//		console.debug('save happening');
	    deferred.addCallback(function(data) {
	    	// add page submit to callback stack
	    	// will be called when save finishes
	    	// what about errors?
	    	// I think even if it errors, it calls the submit
		    if (button == 2) {
		        document.getElementById('myform').action = document.getElementById('myform').action + '/button/' + button;
			}
		    if (button == 3) {
		        document.getElementById('myform').action = document.getElementById('myform').action + '/button/' + button + '/navPage3/' + dojo.byId('navPage3placeholder').value;
		    }
	    	document.forms['myform'].submit();
	    });

	} else {
	    if (button == 2) {
	        document.getElementById('myform').action = document.getElementById('myform').action + '/button/' + button;
		}
	    if (button == 3) {
	        document.getElementById('myform').action = document.getElementById('myform').action + '/button/' + button + '/navPage3/' + dojo.byId('navPage3placeholder').value;
	    }
		document.forms['myform'].submit();
	}
	
	
//	var savebtn = dijit.byId('submitButton');
//	dojo.byId('changePageAction').value = 'select';
//	if(savebtn) {
//		var savebtnDisabled = savebtn.disabled;
//        /* No More Save Button Disabled */
//		// if(savebtnDisabled)
//		// {
//			// saving of page NOT required
//			//console.debug('NO save');
//		// } else {
//
//        var modFlag = false;
//
//        $('.changed').each(function() {
//            modFlag = 1;
//        });
//
//        if (modFlag)
//        { 
//			var wait2finish = true;
//			// saving of page required
//			save(event, wait2finish, url);
//		 }
//	}
//	//console.debug('two - - - - - - -  - -', dojo.byId('returnResult').value);
//    if (button == 2)
//        document.getElementById('myform').action = 
//            document.getElementById('myform').action + '/button/' + button;
//
////    
//    if (button == 3) {
//        document.getElementById('myform').action = document.getElementById('myform').action + '/button/' + button + '/navPage3/' + dojo.byId('navPage3placeholder').value;
////        dojo.byId('navPage3').value = dojo.byId('navPage3placeholder').value;
////        console.debug('button', button, dojo.byId('navPage3').value);
//    }
////    console.debug('navPage3', document.getElementById('myform').navPage3.value);
//	document.forms['myform'].submit();
}

function nextPage(event, url)
{
	// set the action to be passed when page is submitted
	dojo.byId('changePageAction').value = 'next';

	var deferred = conditionalSave(event, false, url);
	if(false != deferred) {
	    deferred.addCallback(function(data) {
//			console.debug('remove savingDialog');
//	    	dijit.byId('savingDialog').hide();
	        try {
	        	$.unblockUI();// unblock the UI
	        } catch (error) {
	        	console.debug('blockIU not loading');
	        }

	    });
	    deferred.addCallback(function(data) {
	    	// add page submit to callback stack
	    	// will be called when save finishes
	    	// what about errors?
	    	// I think even if it errors, it calls the submit
	    	document.forms['myform'].submit();
	    });

	} else {
		document.forms['myform'].submit();
	}
}

function savePageDeferred(event, url)
{
//	console.debug('savePageDeferred');
	try {
		// setting ie8KillBackButtonWarning to true
		// should stop the "you're leaving this page" dialog
		ie8KillBackButtonWarning = true;
		
		var deferred = conditionalSave(event, false, url);
		if(false != deferred) {
//			console.debug('save happening - save page deferred');
		    deferred.addCallback(function(data) {
//				console.debug('remove savingDialog');
//		    	dijit.byId('savingDialog').hide();
		        try {
		        	$.unblockUI();// unblock the UI
		        } catch (error) {
		        	console.debug('blockIU not loading');
		        }

		    });
		    deferred.addCallback(function(data) {
		    	// reenable ie8 warnings that might have been
		    	// disabled above
		    	ie8KillBackButtonWarning = false;
		    });
		}

	} catch (e) {
		console.debug ("There was an error in savePageDeferred.", e.message);
	}

}
function prevPage(event, url)
{
	
	// set the action to be passed when page is submitted
	dojo.byId('changePageAction').value = 'prev';

//	console.debug('prevPage');
	var deferred = conditionalSave(event, false, url);
	if(false != deferred) {
//		console.debug('save happening');
	    deferred.addCallback(function(data) {
//			console.debug('remove savingDialog');
//	    	dijit.byId('savingDialog').hide();
	        try {
	        	$.unblockUI();// unblock the UI
	        } catch (error) {
	        	console.debug('blockIU not loading');
	        }

	    });
	    deferred.addCallback(function(data) {
	    	// add page submit to callback stack
	    	// will be called when save finishes
	    	// what about errors?
	    	// I think even if it errors, it calls the submit
	    	document.forms['myform'].submit();
	    });

	} else {
		document.forms['myform'].submit();
	}
	
	
//	var savebtn = dijit.byId('submitButton');
//	dojo.byId('changePageAction').value = 'prev';
//	
//	if(savebtn) {
//		var savebtnDisabled = savebtn.disabled;
//        /* No more Save Button Disabled */
//		// if(savebtnDisabled)
//		// {
//			// saving of page NOT required
//		// } else {
//        var modFlag = false;
//
//        $('.changed').each(function() {
//            modFlag = 1;
//        }); 
//
//        if (modFlag)
//        {
//			var wait2finish = true;
//			// saving of page required
//	//		console.debug('saving of page required');
//			save(event, wait2finish, url);
//		}
//	}
//	
//	//console.debug('prevPage - - - - - - -  - -', dojo.byId('returnResult').value);
//	document.forms['myform'].submit();
//		
}

function getContainerCell(node) {
//	console.debug('getContainerCell', node);
//	console.debug('getContainerCell', node.value);
//	console.debug('getContainerCell', node.widgetid);
	
    var body = dojo.body();
    while(node && node != body && !dojo.hasClass(node, "colorme")) {
//    	console.debug('checking node', node);
    	node = node.parentNode;
    }
//    console.debug('found node', node);
    if(dojo.hasClass(node, "colorme")){
    	return node;
    }
    return null;
}


function colorMe(evt)
{
    try {
//		console.debug('colorme', evt, evt.target);
		//	dojo.anim(getContainerCell(evt.target),{backgroundColor: "yellow"});

		    var input = evt.target;
		    if(input.value == input._initialValue) {
		        dojo.removeClass(getContainerCell(evt.target), "changed");
		    } else {
		        dojo.removeClass(getContainerCell(evt.target), "errored");
		        dojo.addClass(getContainerCell(evt.target), "changed");
		    }
    } catch (error) {
    	//execute this block if error
    	console.debug('colorme error', evt, evt.target);

    }


	//evt.style("backgroundColor","red");
	//inputElement.style("backgroundColor","red");
}
//function getParentId(node){
//	  while (node)   // false when node is undefined/null
//	  {
//	    var id = node.id;
//	    if (id) return id;
//	    node = node.parentNode;
//	  }
//	}
//function colorParent(node)
//{
//	
//	console.debug('colorParent', node, dojo.byId(node.id));
////	console.debug('container', getContainerCell(node));
//	
////    var body = dojo.body();
//	// && node != body
//    while(node) {// && !dojo.hasClass(node, "colorme")
//    	console.debug('checking node', node, getParentId(node));
//    	node = getParentId(node);
//    }
//
////	dojo.anim(getContainerCell(evt.target),{backgroundColor: "yellow"});
//
////    var input = evt.target;
////    if(input.value == input._initialValue) {
////        dojo.removeClass(getContainerCell(evt.target), "changed");
////    } else {
////        dojo.removeClass(getContainerCell(evt.target), "errored");
////        dojo.addClass(getContainerCell(evt.target), "changed");
////    }
//
//	//evt.style("backgroundColor","red");
//	//inputElement.style("backgroundColor","red");
//}


function colorMeById(id, wrapper)
{
    try {
    	//alert("in colorMeById");
//    	console.debug('colorMeById', id);
    	var wrapper = (wrapper == null) ? null : wrapper;

    	// if a wrapper is passed
    	// get the row prefix and add the wrapper
    	if(null != wrapper)
    	{
//    		console.debug('wrapper exists');
    		// radio button parsing numbers off end
    		dash = id.indexOf('-');
    		type = dojo.attr(id, "type");
    		if(null == type) {
    			type = dojo.attr(id.substring(0, dash), "type");
    		}
//    		console.debug('type', type);
    		if(dash > 0 && 'hidden' == type) {
//    			console.debug('editor');
    			// editor
    			// subform element
    			divID = wrapper+'-colorme';
    			id = id.substring(0, dash);
    			divToColor = dojo.byId(divID);
    		} else if(dash > 0) {
//    			console.debug('dash but not editor');
    			// subform element
//    			console.debug('id', id);
    			divID = id.substring(0, dash)+'-'+wrapper+'-colorme';
    			divToColor = dojo.byId(divID);
    		} else {
//    			console.debug('not editor');
    			divID = wrapper+'-colorme';
    			divToColor = dojo.byId(divID);
    		}	
    	} else {
//    		console.debug('no wrapper');
    		divID = id;
    		divToColor = dojo.byId(id+'-colorme');
    		if(null == divToColor)
    		{	
    			
    			// radio button parsing numbers off end
    			dash = id.lastIndexOf('-');
    			type = dojo.attr(id, "type");
    			
    			//console.debug('divToColor', id+'-colorme', divToColor == null);
    			
    			if(null == type) {
    				var firstPart = id.substring(0, dash);
//    				console.debug('divToColor2 ', firstPart);
    				if(dojo.byId(firstPart)) {
    					type = dojo.attr(firstPart, "type");
    				} else {
    					console.debug('ERROR: The element to be colored could not be found.', id, firstPart);
    					return false;
    				}
    			}
//    			console.debug('dash', dash, type);
    			if(dash > 0 && 'hidden' == type) {
//    				console.debug('editor');
    				id = id.substring(0, dash);
    				divID = id.substring(0, dash);
//    				console.debug('editor divID', divID);
    				divToColor = dojo.byId(divID+'-colorme');
    			} else {
//    				console.debug('not editor');
    				divID = id.substring(0, dash);
    				divToColor = dojo.byId(divID+'-colorme');
    			}
    		} else {
//    			console.debug('null == divToColor');
    		}
    	}
//    	console.debug('post');
    	var input = dojo.byId(id);
//    	console.debug('input', input, id);
//    	console.debug('divToColor', divToColor, divID);
    	
    	if(input != null) {
            if(null == divToColor) {
                console.debug(id + ' is missing it\'s colorme wrapper');
            } else {
                if(input.value == input._initialValue) {
                    dojo.removeClass(divToColor, "changed");
                } else {
                    dojo.removeClass(divToColor, "errored");
                    dojo.addClass(divToColor, "changed");
                }
            }
    	}
    } catch (error) {
    	console.log(error);
    	//execute this block if error
    }
    
//    console.debug('end of colorme');
	return true
}



function colorMeEditor(nodeid, originalValue)
{
	//alert("in colorMeEditor");
	console.debug('colorMeEditor', nodeid);
//	console.debug('value', dojo.byId(nodeid).value);

//    var input = evt.target;
//    if(input.value == input._initialValue) {
//        dojo.removeClass(getContainerCell(dojo.byId(nodeid)), "changed");
//    } else {
        dojo.removeClass(getContainerCell(dojo.byId(nodeid)), "errored");
        dojo.addClass(getContainerCell(dojo.byId(nodeid)), "changed");
//    }

	//evt.style("backgroundColor","red");
	//inputElement.style("backgroundColor","red");
}

function colorMeRed(id, wrapper)
{
//	console.debug('colorme', id);
//	console.debug('wrapper', wrapper);
	if(null == wrapper)
	{
		$('#'+id+'-colorme').addClass('errored');
		
		if(foundElement = dojo.byId(id+'-colorme'))
		{
//			dojo.addClass(foundElement, "errored");
		} else {
			console.debug('color red - field not found: ', foundElement);
		}
	} else
	{
		// radio button parsing numbers off end
		dash = id.indexOf('-');
		if(dash > 0)
		{
			// subform
			divID = id.substring(0, dash) +'-'+wrapper+'-colorme';
//			var divToColor = dojo.byId(divID);
//			dojo.addClass(divToColor, "errored");
			$('#'+divID).addClass('errored')
		} else {
			// main form
			divID = wrapper+'-colorme';
			$('#'+divID).addClass('errored')
//			var divToColor = dojo.byId(divID);
//			dojo.addClass(divToColor, "errored");
		}
	}
}

function refresh_date(node)
{
	// refresh date
	// ==============================================================================================================
    // store the style obj from the existing node
    var storeStyle = node.style;
    // if this dijit exists, destroy it
    if( dijit.byId(dojo.attr(node, "name")) ){
        dijit.byId(dojo.attr(node, "name")).destroy();
    }
    
    // build a new date widget
    var newDateBox = new PgDateTextBox({
        value: node.value,
        id: dojo.attr(node, "name"),
        name: dojo.attr(node, "name"),
        
        // copy styles from the original widget
        style: "width: "+storeStyle.width,
        
        // add modified
        onClick: function(v) {
            modified();
        }
    });
    newDateBox.placeAt(node, 'replace');
}

function refresh_editor(node)
{
//	console.debug(node, 'refresh_editor');
	// refresh editor
	// ==============================================================================================================
	// get the refresh_editor node and delete all children
	refreshContainer = dojo.byId(dojo.attr(node, "id")+"-Editor").parentNode;
    
	// get the editor before we remove it to store properties from it
	srcEditor = dojo.byId(dojo.attr(node, "id")+"-Editor");

	// build the editor name
	editorName = dojo.attr(node, "name");
	editorValue = dojo.attr(node, "value");

	// build the editor bracket
	leftBracket = editorName.indexOf('[');
	if(leftBracket > 0)
	{
		rightBracket = editorName.indexOf(']'); 
	
		subformName = editorName.substring(0, leftBracket);
		fieldName = editorName.substring(leftBracket+1, rightBracket);
		editorId = subformName+'-'+fieldName;
	} else {
		editorId = editorName;
	}
	
	// delete all children
	while (refreshContainer.hasChildNodes()) {
		refreshContainer.removeChild(refreshContainer.lastChild);
	}   
	

	// if the editor still exists in the dojo index, delete it
    if( dijit.byId(editorId) ){
//    	console.debug('element still existed');
        dijit.byId(editorId).destroy();
    }
    // for some reason, when there are no rows in a subtable, dojo still thinks the editor exists
    if( dijit.byId(editorId+'-Editor') ){
//    	console.debug(editorId+'-Editor', 'element still existed', dijit.byId(editorId+'-Editor'));
        dijit.byId(editorId+'-Editor').destroy();
    }
    
    // dojo editors have accompanying hidden input fields
    // we need to build our own
    // place the input field inside out refresh_editor container
    var input = document.createElement("input");
	    input.setAttribute("type", "hidden");
	    input.setAttribute("name", editorName);
	    input.setAttribute("id", editorId);
	    input.setAttribute("value", editorValue);
	refreshContainer.appendChild(input);
    
    // build a new editor widget
    // append '-Editor' to match the editors created by Zend Framework
    // place the editor inside our refresh_editor container

	// if the editor still exists in the dojo index, delete it
    if( dojo.byId(editorId+'-Editor') ){
    	console.debug('error: editor id exists');
    }
    if( dojo.byId(editorName+'-Editor') ){
    	console.debug('error: editor name exists');
    }

    var newEditor = new SrsEditorTextBox({
        id: editorId+'-Editor',
        name: editorName+'-Editor',
        value: editorValue 
    });

//    var newEditor = new dijit.Editor({height: '', extraPlugins: ['dijit._editor.plugins.AlwaysShowToolbar']}, dojo.byId('programmatic2')); dojo.query('#create2').orphan();
	newEditor.setAttribute("class", dojo.attr(srcEditor, "class"));
	newEditor.setAttribute("height", "");
	
    newEditor.placeAt(refreshContainer);
    
    
}
function refreshDijitsNew() {
//	console.debug('refreshDijits', zendDijits);
    dojo.forEach(zendDijits, function(info) {
//    	console.debug('info', info);
        var n = dojo.byId(info.id);
        if (null != n) {
            dojo.attr(n, dojo.mixin({ id: info.id }, info.params));
        }
    });
    dojo.parser.parse();
}

function refresh_button(node)
{
//	console.debug(node, 'refresh_button');
	//
	// function to remove the first and last lines of a string
	// designed to remove first line "function functionName(params) {" and last line "}"
	// leaving only the function guts
	// ==============================================================================================================
	var removeFunctionWrapping = function (formDef)
	{
	    var formArr = formDef.toString().split("\n");
		var returnData = "";
		for(i=1; i<(formArr.length-1); i++)
		{
			returnData += formArr[i] + "\n";
		}
		return returnData;
	}
	//
	// refresh button
	// ==============================================================================================================
    // store the style obj from the existing node
    var storeStyle = node.style;
    
    // if this dijit exists, destroy it
    if( dijit.byId(dojo.attr(node, "name")) ){
        dijit.byId(dojo.attr(node, "name")).destroy();
    }
    
    var newButton = new dijit.form.Button({
        label: dojo.attr(node, "label"),
        onClick: function(v) {
        	eval(removeFunctionWrapping(dojo.attr(node, "onclick")));
    	}
    });
    
    newButton.placeAt(node, 'replace');
}


function refresh_dijits(subformname, row)
{
//	console.debug('refresh_dijits subformname', subformname);
//	console.debug('refresh_dijits row', row);
	// refresh date fields
	// ==============================================================================================================
	// get nodes to refresh Goal
	if('all'==row)
	{		
		var foundDateFields = dojo.query("span.refresh_date input:first-child", subformname+'_parent');
	} else {
		var foundDateFields = dojo.query("span.refresh_date input:first-child", subformname+'_'+row);
	}
//	console.debug('foundDateFields', foundDateFields);
	dojo.forEach(foundDateFields, function(node){
//		console.debug('refresh date', node);
		refresh_date(node);
	});
	// ==============================================================================================================
	// ==============================================================================================================

	//
	// refresh button fields
	// ==============================================================================================================
	// get nodes to refresh
	if('all'==row)
	{		
		var foundButtonFields = dojo.query("span.refresh_button input:first-child", subformname+'_parent');
	} else {
		var foundButtonFields = dojo.query("span.refresh_button input:first-child", subformname+'_'+row);
	}
	dojo.forEach(foundButtonFields, function(node){
//		console.debug('refresh button', node);
		refresh_button(node);
	});
	// ==============================================================================================================
	// ==============================================================================================================

	//
	// refresh editors
	// ==============================================================================================================
	// get nodes to refresh
	if('all'==row)
	{		
		var foundEditorFields = dojo.query("span.refresh_editor input:first-child", subformname+'_parent');
	} else {
		var foundEditorFields = dojo.query("span.refresh_editor input:first-child", subformname+'_'+row);
	}
	dojo.forEach(foundEditorFields, function(node){
//		console.debug('refresh editor', node);
		refresh_editor(node);
	});

	
	
	//
	// refresh new editors
	// ==============================================================================================================
	// get nodes to refresh
	if('all'==row)
	{		
		var foundNewEditorFields = dojo.query("textarea", subformname+'_parent');
	} else {
		var foundNewEditorFields = dojo.query("textarea", subformname+'_'+row);
	}
	
//	console.debug(dojo.query("span.refresh_editor input:first-child", subformname+'_'+row), 'one');
//	console.debug(dojo.query("span.refresh_editor input", subformname+'_'+row), 'two');
//	console.debug(dojo.query("span.refresh_editor", subformname+'_'+row), 'three');
	dojo.forEach(foundNewEditorFields, function(info){
//    	console.debug('info', info);
//        var n = dojo.byId(info.id);
//        if (null != n) {
//            dojo.attr(n, dojo.mixin({ id: info.id }, info.params));
//        }
		refresh_editor(node);
//		refresh_textarea_editor(node);
	});
//    dojo.parser.parse();

	//	refreshDijitsNew();
	// ==============================================================================================================
	// ==============================================================================================================
}
function refresh_textarea_editor(node) {
//	console.debug('refresh_textarea_editor', node);
	// refresh textareaEditor
	// ==============================================================================================================
    // store the style obj from the existing node
    var storeStyle = node.style;
    // if this dijit exists, destroy it
    if( dijit.byId(dojo.attr(node, "name")) ){
        dijit.byId(dojo.attr(node, "name")).destroy();
    }
    
    // build a new widget
//    var newTextAreaEditor = new PgDateTextBox({
//        value: node.value,
//        id: dojo.attr(node, "name"),
//        name: dojo.attr(node, "name"),
//        
//        // copy styles from the original widget
//        style: "width: "+storeStyle.width,
//        
//        // add modified
//        onClick: function(v) {
//            modified();
//        }
//    });
//    newTextAreaEditor.placeAt(node, 'replace');

}

function addSubformRow(subformName)
{
	if('tinyMce'==$('input:radio[name=form_editor_type]:checked').val() || undefined == $('input:radio[name=form_editor_type]:checked').val()) {
		console.debug('tinyMce');
		// jQuery add TinyMce only
	    var formNum = $('#form_number').val();
	    var formID = $("#id_form_"+formNum).val();
	    var page = $('#page').val();
	    var tbl = $('#'+subformName+'_parent');
	    /*
	     * create row and get from server
	     */
		$.ajax({
			async: false,
			type: 'POST',
			dataType: 'json',
			url: '/form'+formNum+'/addsubformrow/id/'+formID+'/page/'+page+'/subformname/'+subformName,
			success: function(json) {
                var returneditems = json.items;
				var tabContainer = $('#'+subformName+'_tab_container');
				var countVarName = subformName+'-count';
				// update count of subform rows
				var subformCount = $('#'+countVarName).val(parseInt($('#'+countVarName).val(), 10)+1).val();
        		
				if(tabContainer.length>0)
        		{
					console.debug('tabContainer', tabContainer);
					var newTabId = subformName + '_' + subformCount;
					var tabName = getTabTitleFromSubformName(subformName)+subformCount;
					/*
					 * append tab to container and instanciate new tab
					 */
					$('#'+subformName+"_tab_container").append('<div id="'+newTabId+'">'+returneditems[0]['new_html']+'</div>')
		            tabContainer.tabs("add",'#'+newTabId, tabName);
                    initTinyMce(tabContainer.find('textarea'));
                } else {
        			console.debug('no tabContainer', tabContainer);
        			// no tab container - add row to parent table
        			var uniqueRowId = 'refresh_me_'+subformName+'_'+$('#'+countVarName).val();
        			var insertRow = '<tr><td id="'+uniqueRowId+'">' + returneditems[0]['new_html'] +  '</td></tr>';
        			$(tbl).append(insertRow);
        			if(0==$('#'+uniqueRowId+' textarea:tinymce').length) {
        				initTinyMce($('#'+uniqueRowId+' div.tinyMceEditor').children('textarea'));
                        // init jquery datepickers
                        $( ".datepicker" ).datepicker({
                            changeMonth: true,
                            changeYear: true
                        });

                    }
        		}

				/*
				 * update validation messages
				 */
				var validationTable = $('#validationList');
				$.each(returneditems[0]['validationArr'], function(index, object) {
					validationTable.append('<tr><td valign="top">&bull;</td><td style="width:100%;"><b>'+object.label+'</b> '+object.message+'</td></tr>');
				});
	        }   
		});
		
		if (arguments[1] != 'undefined') {
			window[arguments[1]]();
		}
		
	} else {
		//	console.debug('addSubformRow');
	    var formNum = dojo.byId("form_number").value;
	    var formID = dojo.byId("id_form_"+formNum).value;
	    var page = dojo.byId("page").value;
	    // ==============================================================================
	    // xhrGet
	    // load the main form from the server 
	    // ==============================================================================
	        dojo.xhrGet( {
	            // The following URL must match that used to test the server.
	            url: '/form'+formNum+'/addsubformrow/id/'+formID+'/page/'+page+'/subformname/'+subformName,
	            handleAs: "json",
	            sync: true,
	            load: function(data, ioArgs) {
	                // Now you can just use the object
					// after the page loads, disable the save button
	                // get items from the json object
	                var returneditems = data.items;
					var tbl = dojo.byId(subformName+"_parent");
					var countVarName = subformName+'-count';
					
					// update count of subform rows
					dojo.byId(countVarName).value = (dojo.byId(countVarName).value *1) + 1;
					tabNum = dojo.byId(countVarName).value;
					
	        		if(dijit.byId(subformName+"_tab_container"))
	        		{
	        			tabContainerFound = true;
	        		} else {
	        			tabContainerFound = false;
	        		}
	        		if(tabContainerFound)
					{	
						// build and insert a content pane
	                    if (subformName == 'ifsp_goals')
	                        var tabTitle = 'Goal ';
	                    else if (subformName == 'iep_form_004_goal')
	                        var tabTitle = 'Goal ';
	                    else if (subformName == 'ifsp_services')
	                        var tabTitle = 'Service ';
	                    else if (subformName == 'form_002_suppform')
	                        var tabTitle = 'Supplemental Form ';
	                    else if (subformName == 'iep_form_004_suppform')
	                    	var tabTitle = 'Supplemental Form ';
	                    else
						    var tabTitle = 'Tab ';
	
						tabname = subformName+'_'+tabNum;
						var tab4 = new dijit.layout.ContentPane(
							//properties
							{ 		
								title:tabTitle + tabNum,
								id:subformName + '_' + tabNum,
								content:""
							},
							tabname
						);
						
						var tc = dijit.byId(subformName+"_tab_container");
	
						tab4.setContent(returneditems[0]['new_html']);
						tc.addChild(tab4);
						tc.selectChild(tab4);
						
						// append stylesheets
						addEditorStyleSheets(subformName, tabNum);
						tab4.startup(); 
						
						
	
						// Changing validation messages - From Here ---------------
						currentValidationMessages = [];
						// returneditems[0]['validationArraySubform'] currently 
						// has validation for all forms
						var newValidationList = currentValidationMessages + "<br>---------<br/>" 
												+returneditems[0]['validationArraySubform'];
						
						//dojo.byId("validationList").innerHTML = newValidationList;
						dojox.html.set(dojo.byId("validationList"), newValidationList);
						
						// Until Here ---------------------------------------------
					} else {
						
						var uniqueRowId = 'refresh_me_'+subformName+'_'+dojo.byId(countVarName).value;
						
						var insertRow = '<tr><td id="'+uniqueRowId+'">' + returneditems[0]['new_html'] +  '</td></tr>';
						dojo.place(insertRow,tbl);
						dojo.parser.parse(dojo.byId(uniqueRowId));
					}
					if(returneditems[0]['maxRows'] == returneditems[0]['countSubrows'])
					{
						// disable the add button for this subrow
						// team_other-add_subform_row
						hideAddButton(subformName + '-add_subform_row');
					}
				    if(null == returneditems[0]['validationArr'])
				    {
				        var errorCount = 0;
				    } else {
				        var errorCount = returneditems[0]['validationArr'].length;
				    }
				    validationPostSave(errorCount, returneditems);
					return data;
	            }
	            // More properties for xhrGet...
	        });    
	    // ==============================================================================
	    // end xhrGet
	    // ==============================================================================
	    // ==============================================================================


        /**
         * run additional functions defined in sub js page
         */

        if (typeof(addSubFormAdditional) === 'function') {
            addSubFormAdditional();
        }

        updateFadingOtherTextboxes();
		$( ".datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	}
}

function updateFadingOtherTextboxes() {
    if(typeof setOtherFieldsDisplay == 'function') {
    	setOtherFieldsDisplay();
    } 
    if(typeof connectOtherCheckboxes == 'function') {
    	connectOtherCheckboxes();
    } 
}

function colorColormeDivs()
{
	// connect inputs wrapped in colorme divs
	// color them when they have been modified
	dojo.query(".colorme input:first-child").forEach(function(node){
		dojo.connect(node, 'onchange', 'colorMe');
	});	
	
	// date boxes need to change on blur instead of onchange
	// add the blurColorDate to the form element in the form definition
	// it should also have the colorme div decorator
	//
	dojo.query(".colorme .blurColorDate input:first-child").forEach(function(node){
		dojo.connect(node, 'onblur', 'colorMe');
	});	
}

function destroyRecursive(node){
    dojo.forEach(node.getDescendants(), function(widget){
        widget.destroyRecursive();
    });
}


function firstLoadColorValidationErrors(){
	// validationMsgs defined in application/layouts/scripts/layout.phtml
    dojo.forEach(validationMsgs['items'], function(validationArr)
    {
//    	console.debug('validation error - color fieldName: ', validationArr['field'], validationArr['wrapper']);
    	colorMeRed(validationArr['field'], validationArr['wrapper']); // color divs that are invalid
    });
}

function colorTabText(id_tab, tabNumber, color) {
//    dijit.byId(id_tab).tablist.getChildren()[tabNumber].domNode.style.color=color;
//    dijit.byId(id_tab).tablist.getChildren()[tabNumber].innerDiv.style.color=color;              
//    dijit.byId(id_tab).tablist.getChildren()[tabNumber].backgroundImage='images_dojo/tabValidateEnabled.gif';
    
//  dijit.byId(id_tab).tablist.getChildren()[tabNumber].innerDiv.style.backgroundColor=color;
    dijit.byId(id_tab).tablist.getChildren()[tabNumber].domNode.style.backgroundImage='url("images_dojo/tabValidateEnabled.gif")';
    dijit.byId(id_tab).tablist.getChildren()[tabNumber].innerDiv.style.backgroundImage='url("images_dojo/tabValidateHover.gif")';
    dijit.byId(id_tab).tablist.getChildren()[tabNumber].tabContent.style.backgroundImage='url("images_dojo/tabValidateEnabled.gif")';
    
//    dijit.byId(id_tab).tablist.getChildren()[tabNumber].tabContent.style.color=color;
}

function setTabText(id_tab, tabNumber, text) {
//	console.debug('setTabText', id_tab, tabNumber, dijit.byId(id_tab).tablist.getChildren()[tabNumber]);
//    console.debug('title', dijit.byId(id_tab).tablist.getChildren()[tabNumber].tabContent.title);                
//    dijit.byId(id_tab).tablist.getChildren()[tabNumber].innerDiv.title=text;              
//    dijit.byId(id_tab).tablist.getChildren()[tabNumber].tabContent.title=text;
}

function getSubformRowIdentifierFromId(id)
{
	dash = id.indexOf('-');
	if(dash > 0)
	{
		// subform element
		divID = id.substring(0, dash);
	} else {
		divID = '';
	}	
	return divID;
}

function getRowNumFromSubformRowIdentifier(rowIdent)
{
	dash = rowIdent.lastIndexOf('_');
	if(dash > 0)
	{
		// subform element
		divID = rowIdent.substring(dash+1);
	} else {
		divID = '';
	}	
	return divID;
}


/*
 * show or hide a field
 * gets the subform identifier and constructs the proper id
 */ 
function subformShowHideField(id, fieldToHide, value, matchValue) { 
//	console.debug('fieldToHide id', id, value, matchValue);
	rowIdentifier = getSubformRowIdentifierFromId(id);
	fieldToHide = rowIdentifier + '-' + fieldToHide + '-colorme';
//	console.debug('fieldToHide', fieldToHide);
	if(value == matchValue) 
	{
//		console.debug(fieldToHide, 'show');
//		fadeNode(fieldToHide, 'in');
		showHideAnimation(fieldToHide, 'show');
	} else {
//		console.debug(fieldToHide, 'hide');
//		fadeNode(fieldToHide, 'out');
		showHideAnimation(fieldToHide, 'hide');
	}
}

//function getGmtTime()
//{
//	  var curDateTime = new Date()
//	  var curHour = curDateTime.getHours() 
//	     + curDateTime.getTimezoneOffset()/60
//	  if (curHour > 24)  curHour -= 24
//	  if (curHour < 0) curHour += 24
//	  var curMin = curDateTime.getMinutes()
//	  var curSec = curDateTime.getSeconds()
//	  var curTime = 
//	    ((curHour < 10) ? "0" : "") + curHour + ":" 
//	    + ((curMin < 10) ? "0" : "") + curMin + ":" 
//	    + ((curSec < 10) ? "0" : "") + curSec 
////	  document.write(curTime + " GMT")
//	  return curDateTime;
//}
//
//function getChicagoTime()
//{
//	  var TimezoneOffset = -6  // adjust for time zone
//	  var localTime = new Date()
//	  var ms = localTime.getTime() 
//	             + (localTime.getTimezoneOffset() * 60000)
//	             + TimezoneOffset * 3600000
//	  var time =  new Date(ms) 
//	  var hour = time.getHours() 
//	  var minute = time.getMinutes()
//	  var second = time.getSeconds()
//	  var curTime = "" + ((hour > 12) ? hour - 12 : hour)
//	  if(hour==0) curTime = "12"
//	  curTime += ((minute < 10) ? ":0" : ":") + minute
//	  curTime += ((second < 10) ? ":0" : ":") + second
//	  curTime += (hour >= 12) ? " PM" : " AM"
////	  document.write(curTime + " US Pacific Time")
//	  return time;
//}
//
//function startTimer() { 
//	if(dojo.byId('zend_checkout_time')) countDownTimer('form_timer', dojo.byId('zend_checkout_time').value);
//}
//
//var t = new dojox.timing.Timer();
//function countDownTimer(divchannel, expdate) {
//	var redirect = false;
//	
//    dojo.subscribe("/"+divchannel,function(e){
//    	dojox.html.set(dojo.byId(divchannel), e);
////    	dojo.byId(divchannel).innerHTML = e;
//	});
//
//    var chi_datetime = new Date(expdate);
//	t.setInterval(990);
//	t.onTick = function() {
//		var local_time = new Date();
//		local_time.setTime(chi_datetime - getChicagoTime());
//		if((chi_datetime - getChicagoTime()) < 990 ) {
//			this.redirect = true;
//			this.stop();
//			return;
//		}
//		
//		dojo.publish("/"+divchannel, [local_time.format("MM:ss")+' minutes']);
//		
//	}
//	
//    this.onStart = function(){ console.debug('start'); console.debug('version', dojo.version);};
//    this.onStop = function(){ 
//    	if(this.redirect)
//    	{
//	    	this.redirect = false;
//	    	dojo.publish("/"+divchannel, ['expired']);
//	    	// page has not been modified if the save button is disabled
//			var savebtn = dojo.byId('submitButton');
//			if(savebtn) {
//				var savebtnDisabled = savebtn.disabled;
//                /* No more Save Button Disabled */
//				// if(savebtnDisabled)
//				// {
//					// saving of page NOT required
//					// console.debug('saving of page NOT required');
//				// } else { 
//				    var form_num = dojo.byId("form_number").value;
//					var wait2finish = true;
//					// saving of page required
////					console.debug('saving of page required');
//					save(null, true, "/form"+form_num+"/jsonupdateiep");
//	
//				// }
//			}
//			dojo.byId("mode").value = 'view';
//			document.forms['myform'].submit();
//    	}
//    };
//
//    t.onStart = this.onStart;
//    t.onStop = this.onStop;
//	
//  t.start();
//}

function callPageInit()
{
	// pageInit can be defined in the individual  
	// JS page for the form
	// if it exists, it will be called
	if(typeof pageInit == 'function') {
		pageInit();
	} 
	
}
function callPageReload(returneditems)
{
    try {
    	$.unblockUI();// unblock the UI
    } catch (error) {
    	console.debug('blockIU not loading');
    }
	
	// pageInit can be defined in the individual  
	// JS page for the form
	// if it exists, it will be called
	if(typeof pageReload == 'function') {
//		console.debug('page relod function found');
		pageReload(returneditems);
	} 
}




function attachSaveAction() {
//    console.debug('running attachSaveAction', dojo.version);
    
    // form id is stored on the form in a hidden field
    // get the value here
	try {
	  //Run some code here
		var form_num = dojo.byId("form_number").value;
	} catch(err) {
	  //Handle errors here
		return;
	}
    
//    var formID = dojo.byId('id_form_'+form_num).value;
    
    // attach save function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the submit button
    var button = dijit.byId("submitButton");
    var button2 = dijit.byId("submitButton2");
    var button3 = dijit.byId("submitButton3");
    if(button != null) dojo.connect(button, "onClick", function(evt){
        //Stop the submit event since we want to control form submission.
    	//save(evt, false, "/form"+form_num+"/jsonupdateiep");
//    	dojo.stopEvent(evt);
    	savePageDeferred(evt, "/form"+form_num+"/jsonupdateiep");
//    	return false;
    });
    if(button2 != null) dojo.connect(button2, "onClick", function(evt){ 
//    	dojo.stopEvent(evt);
//    	save(evt, false, "/form"+form_num+"/jsonupdateiep");
    	savePageDeferred(evt, "/form"+form_num+"/jsonupdateiep");    	
    });
    
    if(button3 != null) dojo.connect(button3, "onClick", function(evt){ 
//    	dojo.stopEvent(evt);
    	savePageDeferred(evt, "/form"+form_num+"/jsonupdateiep");
    });

    // attach save function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the student options menu
    var studentOptions = dojo.byId("student_options");
    //console.debug('studentOptions', studentOptions);
    if(studentOptions != null) dojo.connect(studentOptions, "onchange", 
    		function(evt){

                var ret = true;
                var modFlag = 0;

                /* Check to see if any elements were changed */
                $('.changed').each(function() {
                    modFlag = 1;
                });

                if (modFlag == 1) {
                    ret = confirm("ALERT! You haven't saved your changes.\nAre you sure you want to continue?");
                }

                if (ret) 
                {

			        var submitButton = dijit.byId('submitButton');
	                var submitButton2 = dijit.byId('submitButton2');
	                var submitButton3 = dijit.byId('submitButton3');
	                /* No more disabled save button 
			        if(null != submitButton && false == submitButton.disabled)
			    	{
			        	save(evt, false, "/form"+form_num+"/jsonupdateiep");
			        }
	                */
	                if(null != submitButton)                      
	                {
	                    if (modFlag)
	                    	savePageDeferred(evt, "/form"+form_num+"/jsonupdateiep");
	                    //save(evt, false, "/form"+form_num+"/jsonupdateiep");
	                }
	                if(null != submitButton2)                                      
	                {                    
	                    if (modFlag)
	                    	savePageDeferred(evt, "/form"+form_num+"/jsonupdateiep");                    
	                }
	                if(null != submitButton3)                                      
	                {                    
	                    if (modFlag)
	                    	savePageDeferred(evt, "/form"+form_num+"/jsonupdateiep");
	//                        save(evt, false, "/form"+form_num+"/jsonupdateiep");                    
	                }
	    
	    			//console.debug('redirect', studentOptions.value);
	                if (studentOptions.value != '') {
						if (studentOptions.value.match(/IEP/) == 'IEP' ||
							studentOptions.value.match(/MDT/) == 'MDT' ||
							studentOptions.value.match(/IFSP/) == 'IFSP' ||
							studentOptions.value.match(/Progress Report/) == 'Progress Report') {
							$.ajax({
								type: 'POST',
								dataType: 'json',
								url: '/student/get-most-recent/'+studentOptions.value,
								success: function(json) {
									if (json['success'] == '1')
										window.location.href = json['url'];
									else
										alert('The system was unable to locate the most recent form.');
								}
							});
	                    } else {
	                    	window.location.href = studentOptions.value;
	                    }
	
					}
	                
	                // Leaving to catch any missed cases
	    			if('View Student' == studentOptions.value) {
	    				window.location.href='https://iep.esucc.org/srs.php?area=student&sub=student&student='+dojo.byId('id_student').value+'&option=view';
	    				
	    			} else if ('Edit Student' == studentOptions.value) {
	    				window.location.href='https://iep.esucc.org/srs.php?area=student&sub=student&student='+dojo.byId('id_student').value+'&option=edit';
	    				
	    			} else if ('Student Charting' == studentOptions.value) {
	    				window.location.href='https://iep.esucc.org/srs.php?area=student&sub=student&student='+dojo.byId('id_student').value+'&option=charting';
	    				
	    			} else if ('Parent/Guardians' == studentOptions.value) {
	    				window.location.href='https://iep.esucc.org/srs.php?area=student&sub=student&student='+dojo.byId('id_student').value+'&option=parents';
	    				
	    			} else if ('Student Team' == studentOptions.value) {
	    				window.location.href='https://iep.esucc.org/srs.php?area=student&sub=student&student='+dojo.byId('id_student').value+'&option=team';
	    				
	    			} else if ('Student Forms' == studentOptions.value) {
	    				window.location.href='https://iep.esucc.org/srs.php?area=student&sub=student&student='+dojo.byId('id_student').value+'&option=forms';
	    				
	    			} else if ('Student Log' == studentOptions.value) {
	    				window.location.href='https://iep.esucc.org/srs.php?area=student&sub=student&student='+dojo.byId('id_student').value+'&option=log';
	    			}
                }
    		}
    );
    
    // attach nextPage function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the nextPage button
    var nextButton = dijit.byId("nextPage");
    if(nextButton != null) dojo.connect(nextButton, "onClick",  function(evt){ nextPage(evt, "/form"+form_num+"/jsonupdateiep"); });

    var nextButton2 = dijit.byId("nextPage2");
    if(nextButton2 != null) dojo.connect(nextButton2, "onClick",  function(evt){ nextPage(evt, "/form"+form_num+"/jsonupdateiep"); });

    var nextButton3 = dijit.byId("nextPage3");
    if(nextButton3 != null) dojo.connect(nextButton3, "onClick",  function(evt){ nextPage(evt, "/form"+form_num+"/jsonupdateiep"); });


    // attach prevPage function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the prevPage button
    var prevButton = dijit.byId("prevPage");
    if(prevButton != null) dojo.connect(prevButton, "onClick",  function(evt){ prevPage(evt, "/form"+form_num+"/jsonupdateiep"); });

    var prevButton2 = dijit.byId("prevPage2");
    if(prevButton2 != null) dojo.connect(prevButton2, "onClick",  function(evt){ prevPage(evt, "/form"+form_num+"/jsonupdateiep"); });

    var prevButton3 = dijit.byId("prevPage3");
    if(prevButton3 != null) dojo.connect(prevButton3, "onClick",  function(evt){ prevPage(evt, "/form"+form_num+"/jsonupdateiep"); });
    
    // attach selectPage function (defined in /public/js/srs_forms/common_form_functions.js)
    // to the navPage select menu
    var pageSelectButton = dojo.byId("navPage");
    if(pageSelectButton != null) dojo.connect(pageSelectButton, "onchange",  function(evt){ selectPage(evt, "/form"+form_num+"/jsonupdateiep", 1); });

    var pageSelectButton2 = dojo.byId("navPage2");
    if(pageSelectButton2 != null) dojo.connect(pageSelectButton2, "onchange",  function(evt){ selectPage(evt, "/form"+form_num+"/jsonupdateiep", 2); });

    var pageSelectButton3 = dojo.byId("navPage3placeholder");
    if(pageSelectButton3 != null) dojo.connect(pageSelectButton3, "onchange",  function(evt){ 
    	selectPage(evt, "/form"+form_num+"/jsonupdateiep", 3); });
    
//  var pageSelectButton = dijit.byId("navPage_1");
//  if(pageSelectButton != null) dojo.connect(pageSelectButton, "onchange",  function(evt){ selectPage(evt, "/form"+form_num+"/jsonupdateiep"); });
//
//  var pageSelectButton = dijit.byId("navPage_2");
//  
//  if(pageSelectButton != null) dojo.connect(pageSelectButton, "onchange",  function(evt){ selectPage(evt, "/form"+form_num+"/jsonupdateiep"); });
//  console.debug('pageSelectButton', pageSelectButton);

//    console.debug('end of attach save');
}


function getParentIdBeginningWith(node, beginWith)
{
    try {
  	  while (node)   // false when node is undefined/null
	  {
	    var id = node.id;
	    if (id.startsWith(beginWith)) return id;
	    node = node.parentNode;
	  }

    } catch (error) {
    	console.debug('javscript error in getParentIdBeginningWith');
    }
	
}

function getNodeIdBeginningWith(node, beginWith)
{
    try {
    	var id = node.id;
    	if (id.startsWith(beginWith)) return id;
      } catch (error) {
      	console.debug('javscript error in getNodeIdBeginningWith');
      }
}
function getNodeIdEndsWith(node, endsWith)
{
    try {
    	var id = node.id;
    	if (id.endsWith(endsWith)) return true;
    	return false;
      } catch (error) {
      	console.debug('javscript error in getNodeIdEndsWith');
      }
}





//=========================================================================================================
// animation 
//=========================================================================================================
	dojo.require("dojo.fx");
	var currentAnimation;
	var wipeOut;
	var wipeIn;
//=========================================================================================================
//=========================================================================================================
	//starts with
	String.prototype.startsWith = function(str)
	{
		return (this.match("^"+str)==str);
	};
	
	//ends with 
	String.prototype.endsWith = function(str)
	{
		return (this.match(str+"$")==str);
	};
//=========================================================================================================
//=========================================================================================================

	function showHideAnimation(id, type) {
//		console.debug('showHideAnimation', id);
		if(dojo.byId(id)) {
			switch(type) {
				case 'hide':
			        currentAnimation = dojo.fx.wipeOut({node:id, duration: 1000});
			        break;
			    case 'show': 
			        currentAnimation = dojo.fx.wipeIn({node:id, duration: 1000});
			        break;
			}
			// Play the animation. Without this call, it will not run.
			if(type == 'show') {
//				console.debug('show');
			    currentAnimation.play();
			} else if(type == 'hide' && dojo.style(id, 'display') != 'none') {
//				console.debug('hide allowed');
				currentAnimation.play();
			}
		} else {
			console.debug('ERROR: showHideAnimation NODE NOT FOUND:', id);
		}
	}
	
	function toggleShowHideRow(element, parentPrefix,  value) {
		
		rowContainerId = getParentIdBeginningWith(element, parentPrefix);
//		console.debug("found row container: ", rowContainerId, value);
	
		if(value == 1) {
			modified();
			showHideAnimation(rowContainerId, 'hide');
		} 
		else {
			showHideAnimation(rowContainerId, 'show');
		}
	}
	
	function toggleShowOnMatchById(matchElementValue, matchValue, showHideId) {
//		console.debug('toggleShowHideOnMatchById matchElementValue', matchElementValue);
//		console.debug('toggleShowHideOnMatchById matchValue', matchValue);
//		console.debug('toggleShowHideOnMatchById showHideId', showHideId);
		if(matchElementValue == matchValue) {
			showHideAnimation(showHideId, 'show');
		} else {
			showHideAnimation(showHideId, 'hide');
		}
	}
	function toggleShowOnMatchByIdSubform(matchElementValue, matchValue, showHideId, id) {
		console.debug('toggleShowHideOnMatchById matchElementValue', matchElementValue);
		console.debug('toggleShowHideOnMatchById matchValue', matchValue);
		console.debug('toggleShowHideOnMatchById showHideId', showHideId);
		console.debug('toggleShowHideOnMatchById id', id);
		rowIdentifier = getSubformRowIdentifierFromId(id);
//		console.debug('rowIdentifier:', rowIdentifier+'-'+showHideId);
		if(matchElementValue == matchValue) {
			showHideAnimation(rowIdentifier+'-'+showHideId, 'show');
		} else {
			showHideAnimation(rowIdentifier+'-'+showHideId, 'hide');
		}
	}
	
	
	function doAnimation(index) {
	    switch(index) {
	      case 1:
	        currentAnimation = wipeOut;
	        break;
	      case 2: 
	        currentAnimation = wipeIn;
	        break;
	      case 3:
	        //Chain two animations to run in sequence.
	        //Note the array passed as an argument.
	        currentAnimation = dojo.fx.chain([wipeOut, wipeIn, wipeOut, wipeIn]);
	        break;
	    }
	    //Play the animation. Without this call, it will not run.
	    currentAnimation.play();
	}
	
	function fadeNode(nodeId, inOut, delayBeforeStart)
	{
		//console.debug('fade', nodeId, dojo.byId(nodeId));
	    var delayBeforeStart = (delayBeforeStart == null) ? 0 : delayBeforeStart;
		if('out' == inOut)
		{
			dojo.fadeOut({ // returns a dojo._Animation
		        // this is an Object containing properties used to define the animation
		        node:nodeId,
		        delay: delayBeforeStart
			}).play();
		} else {
			// default to 'in'
			dojo.fadeIn({ // returns a dojo._Animation
		        // this is an Object containing properties used to define the animation
		        node:nodeId,
		        delay: delayBeforeStart
			}).play();
		}
	}
//=========================================================================================================
//=========================================================================================================

//function redirect(url) {
//	window.location.href=url;
//}

//
//attach functions to be run when page is done loading
//

function createWindow2(pageURL, pageTitle, pageWidth, pageHeight, resizeable, scrollbars) {
    var URL = pageURL;
    var windowName = pageTitle;
    var features = 'width=' + pageWidth;
    features += ',height='      + pageHeight;
    features += ',resizable='      + resizeable;
    features += ',scrollbars='      + scrollbars;
    return window.open (URL, windowName, features);
}

function redirectToIep() {
	
}

function recordAction(formObj, action, docRoot, area, sub, keyName, id, page) {
    //confirm(action);
    //alert( "record actions, action =" + action + ", docRoot = " + docRoot, ", area = " + area + "sub = " + sub + ", keyName= " + keyName + "id = " + id + ", " + page);
    var ret = true;
    var modFlag = 0;
    var submitButton = dijit.byId('submitButton');
    var submitButton2 = dijit.byId('submitButton2');
    var submitButton3 = dijit.byId('submitButton3');
    /* No more Save Button Disabled 
    if(submitButton && false == submitButton.disabled)
	{
    	modFlag = 1;
    }
    */

    /* Check to see if any elements were changed */
    $('.changed').each(function() {
        modFlag = 1;
    });
    
    switch (action) {
        case "cancel":
            if (modFlag == 1) {
                ret = confirm("ALERT! You haven't saved your changes.\nAre you sure you want to cancel?");
            }
            break;
        case "done":
            if (modFlag == 1) {
                ret = confirm("ALERT! You haven't saved your changes.\nAre you sure you want to exit?");
            }
            break;
        case "doneclose":
            if (modFlag == 1) {
                ret = confirm("ALERT! You haven't saved your changes.\nAre you sure you want to exit?");
            }
            break;
        case "revert":
            if (modFlag == 1) {
                ret = confirm("Discard ALL changes and revert to\nthe last saved version?");
            }
            break;
        case "save":
            // validationForm010Check(this.form);
            break;
        case "dupe":
            break;
        case "PRrefresh":
            formObj.PRrefresh.value = true;
            formObj.PRrefreshLocation.value = docRoot;
            if (modFlag == 1 || formObj.document.value == "" || formObj.fatal.value == 1) {
                action = "save";
            }
            break;
        default:
            formObj.nextPage.value = action;
            if (modFlag == 1 || formObj.document.value == "" || formObj.fatal.value == 1) {
                action = "save";
            }
            //alert( formObj.document.value );
            break;
    }
    if (ret) {
        switch (action) {
            case "revert":
                formObj.reset();
//                modFlag = 0;
//                obj = eval("document." + coll + "revert");
//                if (obj) {
//                    obj.innerHTML = "<a onMouseOver=\"javascript:window.status='Record can&rsquo;t be reverted because no changes have been made.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"images/button_revert_off.gif\" alt=\"Revert\" title=\"No changes have been made.\"></a>";
//                }
//                obj = eval("document." + coll + "revert_bottom");
//                if (obj) {
//                    obj.innerHTML = "<a onMouseOver=\"javascript:window.status='Record can&rsquo;t be reverted because no changes have been made.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"images/button_revert_off.gif\" alt=\"Revert\" title=\"No changes have been made.\"></a>";
//                }
//                obj = eval("document." + coll + "save");
//                if (obj) {
//                    obj.innerHTML = "<a onMouseOver=\"javascript:window.status='Record can&rsquo;t be saved because no changes have been made.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"images/button_save_off.gif\" alt=\"Save\" title=\"No changes have been made.\"></a>";
//                }
//                obj = eval("document." + coll + "save_bottom");
//                if (obj) {
//                    obj.innerHTML = "<a onMouseOver=\"javascript:window.status='Record can&rsquo;t be saved because no changes have been made.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"images/button_save_off.gif\" alt=\"Save\" title=\"No changes have been made.\"></a>";
//                }
//                obj = eval("document." + coll + "hrefPrint");
//                //confirm( "tweaking print");
//                if (obj) {
//                    obj.innerHTML = "<a accesskey=\"p\" href=\"javascript:print('" + docRoot + "/form_print.php?area=" + area + "&sub=" + sub + "&" + keyName + "=" + id + "', '" + id + "', 'scrollbars=1,status=1,toolbar=1,resizable=1,location=1,width=800,top=10,left=10');\" onMouseOver=\"javascript:window.status='Click here to view a printable PDF version of this document.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\">print</a>";
//                }
//                
//                obj = eval("document." + coll + "print_bottom");
//                if (obj) {
//                //confirm( "tweaking print_bottom");
//                    if ( sub=="form_001" || sub=="form_002" || sub=="form_005" || sub=="form_006" || sub=="form_007" || sub=="form_008" || sub=="form_009"  ) {
//                    obj.innerHTML = "<a accesskey=\"p\" href=\"javascript:print('" + docRoot + "/form_print.php?area=" + area + "&sub=" + sub + "&" + keyName + "=" + id + "', '" + id + "', 'scrollbars=1,status=1,toolbar=1,resizable=1,location=1,width=800,top=10,left=10');\" onMouseOver=\"javascript:window.status='Click here to view a printable PDF version of this document.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"images/button_print.gif\" alt=\"Print\" title=\"Print (shortcut key = P)\"></a>";
//                    } else {
//                    obj.innerHTML = "<a accesskey=\"p\" href=\"javascript:print('" + docRoot + "/srs.php?sub=" + sub + "&" + keyName + "=" + id + "&page=" + page + "&option=print', '" + id + "', 'scrollbars=1,status=1,toolbar=1,resizable=1,location=1,width=800,top=10,left=10');\" onMouseOver=\"javascript:window.status='Click here to view a printer friendly version of this page.'; return true;\" onMouseOut=\"javascript:window.status=''; return true;\"><img src=\"images/button_print.gif\" alt=\"Print\" title=\"Print (shortcut key = P)\"></a>";
//                    }
//                }
                break;
            case "doneclose":
                window.close();
                break;
            default:
                /**
                 * replacing with jQuery
                 * 20120918 jlavere
                 */
                console.debug('record action', dojo.byId('form_number').value);
            	formObj.action = '/form' + dojo.byId('form_number').value + '/' + action  + '/document/' + dojo.byId('id_form_'+dojo.byId('form_number').value).value;
//            	console.debug( formObj.action );
                formObj.submit();
        }
    }
}
function setToRefresh() {
	
	if(dojo.byId('refresh_page')) {
		dojo.byId('refresh_page').value = true;
	} else {
//		console.debug('setToRefresh() has been called, but the dojo element could not be found.');
		
		myform = dojo.byId("myform");
		
	    var input = document.createElement("input");
	    input.setAttribute("type", "hidden");
	    input.setAttribute("name", 'refresh_page');
	    input.setAttribute("id", 'refresh_page');
	    input.setAttribute("value", true);
	    myform.appendChild(input);

	}
}

function checkEditedStatus(url)
{
     var ret = true;
     var modFlag = 0;

     /* Check to see if any elements were changed */
     $('.changed').each(function() {
          modFlag = 1;                
     });

     if (modFlag == 1) 
        ret = confirm("ALERT! You haven't saved your changes.\nAre you sure you want to continue?");

     if (ret)
        window.location.href = url;
}

function showDataAssistants() {
	// requires animation.js
	try {
		// search for items with dataAssistant class
		dojo.query('.dataAssisstant').forEach(
		  function(ele){
			  // if a properly named dialog exists,
			  // display the helper link
			  if(dojo.byId('dialog__'+ele.id)) {
				  fadeIn(ele);
			  }
		  }
		)
    } catch (error) {
    	console.debug('catch - showDataAssistants');
    }  
    //console.debug('end - showDataAssistants');

}
function resetModFlag() {
	// unset the modified variable
	// this allows modFlag aware functions
	// to be reset
	modFlag = undefined;
}


function html_entity_decode (string, quote_style) {
    // Convert all HTML entities to their applicable characters  
    // 
    // version: 1102.614
    // discuss at: http://phpjs.org/functions/html_entity_decode    // +   original by: john (http://www.jd-tech.net)
    // +      input by: ger
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman    // +   improved by: marc andreu
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: Ratheous
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Nick Kolosov (http://sammy.ru)    // +   bugfixed by: Fox
    // -    depends on: get_html_translation_table
    // *     example 1: html_entity_decode('Kevin &amp; van Zonneveld');
    // *     returns 1: 'Kevin & van Zonneveld'
    // *     example 2: html_entity_decode('&amp;lt;');    // *     returns 2: '&lt;'
    var hash_map = {},
        symbol = '',
        tmp_str = '',
        entity = '';    tmp_str = string.toString();
 
    if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
        return false;
    } 
    // fix &amp; problem
    // http://phpjs.org/functions/get_html_translation_table:416#comment_97660
    delete(hash_map['&']);
    hash_map['&'] = '&amp;'; 
    for (symbol in hash_map) {
        entity = hash_map[symbol];
        tmp_str = tmp_str.split(entity).join(symbol);
    }    tmp_str = tmp_str.split('&#039;').join("'");
 
    return tmp_str;
}
/// Replaces commonly-used Windows 1252 encoded chars that do not exist in ASCII or ISO-8859-1 with ISO-8859-1 cognates.
//var replaceWordChars = function(text) {
//    var s = text;
//    // smart single quotes and apostrophe
//    s = s.replace(/[\u2018|\u2019|\u201A]/g, "\'");
//    // smart double quotes
//    s = s.replace(/[\u201C|\u201D|\u201E]/g, "\"");
//    // ellipsis
//    s = s.replace(/\u2026/g, "...");
//    // dashes
//    s = s.replace(/[\u2013|\u2014]/g, "-");
//    // circumflex
//    s = s.replace(/\u02C6/g, "^");
//    // open angle bracket
//    s = s.replace(/\u2039/g, "<");
//    // close angle bracket
//    s = s.replace(/\u203A/g, ">");
//    // spaces
//    s = s.replace(/[\u02DC|\u00A0]/g, " ");
//    return s;
//}
var sanitizeString = function(text) {
//function sanitizeString(text) {
	//-> Replace all of those weird MS Word quotes and other high characters
//	$badwordchars = array (
//		"\xe2\x80\x98", // left single quote
//		"\xe2\x80\x99", // right single quote
//		"\xe2\x80\x9c", // left double quote
//		"\xe2\x80\x9d", // right double quote
//		"\xe2\x80\x94", // em dash
//		"\xe2\x80\x93", // em dash 2
//		"\xe2\x80\xa6", // elipses
//		"\xc2\xa0"		// space
//		)
//	;
//	$fixedwordchars = array ("'", "'", '"', '"', '&mdash;', '&mdash;', '...', ' ');
//	return  ( str_replace ( $badwordchars, $fixedwordchars, $string ) );

	var s = text;
	// smart single quotes and apostrophe
	s = s.replace(/[\u2018|\u2019|\u201A]/g, "\'");
	// smart double quotes
	s = s.replace(/[\u201C|\u201D|\u201E]/g, "\"");
	// ellipsis
	s = s.replace(/\u2026/g, "...");
	// dashes
	s = s.replace(/[\u2013|\u2014]/g, "-");
	// circumflex
	s = s.replace(/\u02C6/g, "^");
	// open angle bracket
	s = s.replace(/\u2039/g, "<");
	// close angle bracket
	s = s.replace(/\u203A/g, ">");
	// spaces
	s = s.replace(/[\u02DC|\u00A0]/g, " ");
	return s;

}

var stripWordFormatting = function(text) {
    var s = text;    
    // Meta tags, link tags, and prefixed tags
    s = s.replace(/(<meta\s*[^>]*\s*>)|(<\s*link\s* href="file:[^>]*\s*>)|(<\/?\s*\w+:[^>]*\s*>)/gim, ""); 

    // MS class tags and comment tags.
    s = s.replace(/(\s*class="Mso[^"]*")|(<!--(.|\s){1,}?-->)/gim, "");//

    // MS class tags and comment tags.
    s = s.replace(/\sstyle\=(\'|\")(.*?)(\1)/gim, "");//
    
    // blank p tags
//  s = s.replace(/(<p[^>]*>\s*(\&nbsp;|\u00A0)*\s*<\/p[^>]*>)|(<p[^>]*>\s*<font[^>]*>\s*(\&nbsp;|\u00A0)*\s*<\/\s*font\s*>\s<\/p[^>]*>)/ig, ""); 
//    s = s.replace(/(<p[^>]*>\s*<font[^>]*>\s*(\&nbsp;|\u00A0| )*\s*<\/\s*font\s*>\s<\/p[^>]*>)/gim, ""); 

    // blank font tags with p wrappers
    s = s.replace(/(<p[^>]*>\s*<font[^>]*>\s*(&nbsp;|\u00A0| )*\s*<\/\s*font\s*>\s*<\/p[^>]*>)/gim, "$2"); 
    
    // full font tags
    s = s.replace(/<font[^>]*>([^<]*)<\/\s*font\s*>/gim, "$1"); 
    s = s.replace(/<font[^>]*>/gim, ""); 
    s = s.replace(/<\/font>/gim, ""); 

    // Strip out styles containing mso defs and margins, as likely added in IE and are not good to have as it mangles presentation.
//    s = s.replace(/(style="[^"]*mso-[^;][^"]*")|(style="margin:\s*[^;"]*;")/gim, "");
    // Scripts (if any)
    s = s.replace(/(<\s*script[^>]*>((.|\s)*?)<\\?\/\s*script\s*>)|(<\s*script\b([^<>]|\s)*>?)|(<[^>]*=(\s|)*[("|')]javascript:[^$1][(\s|.)]*[$1][^>]*>)/gim, "");
    return s;
}

// ====================================================================================================
// editor processing
// ====================================================================================================
var updateInlineValue_messageText;
var updateInlineValue_counter_visible_difference;
var updateInlineValue_counter_hidden_difference;

function editorInputCleaner(updateValue) {
	// zero counters
	preLength=0;
	postLength=0;
	wsLength=0;
	susLength=0;

	debugCount = false;
	debugValue = true;
	
	preCleanLen = updateValue.length;
	if(debugCount) console.debug('preCleanLen:', updateValue.length, stripHtml(updateValue).length);
	
	// Replace all of those weird MS Word quotes and other high characters
	updateValue = sanitizeString(updateValue);
//	dojo.byId('debugger').value += "___sanitizeString----------------------------------\r\n" + updateValue;
	
	// set preLength AFTER sanitize because the multi-byte 
	// conversion can throw off counts
	preLength = stripHtml(updateValue).length;
	if(debugValue) console.debug('updateValue 2', updateValue);
	if(debugCount) console.debug('___after sanitizeString:', updateValue.length, stripHtml(updateValue).length);
	
	// stripWordFormatting
	updateValue = stripWordFormatting(updateValue);
	if(debugValue) console.debug('updateValue 3', updateValue);
	if(debugCount) console.debug('after stripWordFormatting:', updateValue.length, stripHtml(updateValue).length);
//	dojo.byId('debugger').value += "___stripWordFormatting----------------------------------\r\n" + updateValue;
	
	// strip tag internal style and class tags
	updateValue = strip_styleClassPadding(updateValue);
	postLength = stripHtml(updateValue).length;
	if(debugValue) console.debug('updateValue 4', updateValue);
	if(debugCount) console.debug('after strip_styleClassPadding:', updateValue.length, stripHtml(updateValue).length);
//	dojo.byId('debugger').value += "___strip_styleClassPadding----------------------------------\r\n" + updateValue;
	
	updateValue = strip_multipleSpaces(updateValue);
	wsLength = stripHtml(updateValue).length;
	if(debugValue) console.debug('updateValue 5', updateValue);
	if(debugCount) console.debug('after strip_multipleSpaces set wsLength:', updateValue.length, stripHtml(updateValue).length);
//	dojo.byId('debugger').value += "___strip_multipleSpaces----------------------------------\r\n" + updateValue;
	
	// run through JS Html Parser to make sure it's valid html
	// this helps stop the nested <p> issue on windows IE
	updateValue = HTMLtoXML(updateValue);
	susLength = stripHtml(updateValue).length;
	if(debugValue) console.debug('updateValue 6', updateValue);
	if(debugCount) console.debug('after HTMLtoXML:', updateValue.length, stripHtml(updateValue).length);
//	dojo.byId('debugger').value += "___HTMLtoXML----------------------------------\r\n" + updateValue;
	
	updateValue = strip_multipleSpaces(updateValue);
	wsLength += stripHtml(updateValue).length;
	if(debugValue) console.debug('updateValue 7', updateValue);
	if(debugCount) console.debug('after strip_multipleSpaces:', updateValue.length, stripHtml(updateValue).length);
//	dojo.byId('debugger').value += "___strip_multipleSpaces----------------------------------\r\n" + updateValue;
	
	updateValue = replaceParagraphsWithDivs(updateValue);
//	updateValue = replaceEmptySpansWithSpanBr(updateValue);
//	 console.debug('replaceParagraphsWithDivs 88', updateValue);
	 
	updateInlineValue_counter_visible_difference += (preLength - postLength);
	updateInlineValue_counter_hidden_difference += (postLength - wsLength);
	
	if(preCleanLen != updateValue.length) {
		return editorInputCleaner(updateValue);
	} else {
		return updateValue;
	}
}
function revertEditor(editorID, editorRevertId) {
//	console.debug('revertEditor:', editorID, editorRevertId);
	updateValue = dojo.byId(editorRevertId).value;
	if('-Editor' == editorID.substring(editorID.length-7))
	{
		var hiddenEditorID = editorID.substring(0, editorID.length-7);
		dojo.byId(hiddenEditorID).value = updateValue;
		dijit.byId(editorID).setValue(updateValue);
	}

}

function updateInlineValue(editorID, updateValue, purify, revertLinkFlag){
	
	debugValue = false;
	
	// for editors
	console.debug('PROCESS TEXT PRIOR TO EDITOR PROCESSING', updateValue);
	
	updateInlineValue_counter_visible_difference=0;
	updateInlineValue_counter_hidden_difference=0;
	
	updateInlineValue_messageText = 'Your text has been cleaned. ';
	
	editorMessageId = editorID+'-message';
	editorRevertId = editorID+'-revert';

//	purify = false;
	if (typeof purify === 'undefined') {
//		console.debug('NO PURIFY');
	} else if ('<br />' == updateValue) {
//		console.debug('NO PURIFY...value empty.');
	} else if(purify === true) {
//		console.debug('PURIFY');
	    try {
	    	
	    	
	    	updateValue = editorPreProcess(updateValue, debugValue);
	    	
			// be sure words dont jam together
			updateValue = lineBreakToSpace(updateValue);
//			if(debugValue) console.debug('lineBreakToSpace', updateValue);

			
	//		updateValue = spacesToUnderscores(updateValue);
	//		console.debug('spacesToUnderscores', updateValue);
			
			// decode html entities
//			updateValue = dojox.html.entities.decode(updateValue);
//			if(debugValue) console.debug('updateValue',  editorID, updateValue);
			//dojo.byId('debugger').value += "___dojox.html.entities.decode----------------------------------\r\n" + updateValue;
			
//			updateValue = ajacentParagraphTags(updateValue);
//			if(debugValue) console.debug('ajacentParagraphTags', updateValue);

			// run the cleaner
			updateValue = editorInputCleaner(updateValue);
			if(debugValue) console.debug('updateValue',  editorID, updateValue);
			//dojo.byId('debugger').value += "___editorInputCleaner----------------------------------\r\n" + updateValue;
			
			
			
			updateValue = trimText(updateValue);
			if(debugValue) console.debug('trimText', updateValue);
			//dojo.byId('debugger').value += "___trimText----------------------------------\r\n" + updateValue;
			
			// replace <span> </span> with <span>&nbsp;</span>
			// this allows the user to click on the line created by the span
//			updateValue = spanSpaceToNonBreakingSpace(updateValue);
			
//			updateValue = removeLineBreaksAndIeEmptyParagraphs(updateValue);
			
			// update message displayed to user
			if(0 == updateInlineValue_counter_visible_difference) {
				updateInlineValue_messageText += 'There were no modifications to visible text. ';
			} else {
				updateInlineValue_messageText += updateInlineValue_counter_visible_difference+' visible character(s) were removed. ';
			}
			if(0 < updateInlineValue_counter_hidden_difference) {
				updateInlineValue_messageText += updateInlineValue_counter_hidden_difference+' whitespace character(s) were removed.';
			}

	    } catch (error) {
	    	//execute this block if error
	    	console.debug('ERROR');
	    	updateInlineValue_messageText = 'There was an error processing the submitted text. Please contact the system administrator for help.';
	    }
		
		
//		dojo.fadeOut({ 
//			node:editorMessageId, 
//			delay:5000,
//		    onEnd: function(){
//	              // executed when the animation is done
//		    	dojo.byId(editorMessageId).style.display = 'none';
//	      },
//	    }).play();
		
	}
	
    try {
		//alert(editorID);
		if('-Editor' == editorID.substring(editorID.length-7))
		{
			//alert('Updating');
			//console.debug('editor found inside updateInlineValue');
			var hiddenEditorID = editorID.substring(0, editorID.length-7);
			var hiddenElement = dojo.byId(hiddenEditorID);
			var originalValue = hiddenElement.value;
			
	
	        // set values into page elements
			dijit.byId(editorID).setValue(updateValue);
			dojo.byId(hiddenEditorID).value = updateValue;
			revertDiv = dojo.byId(editorRevertId);
			if(null != revertDiv) {
				dojo.destroy(revertDiv);
			}
			dojo.place('<input type="hidden" id="'+editorRevertId+'" value="'+dojox.html.entities.encode(originalValue)+'" />', editorID, 'before');
			if(revertLinkFlag) {
				updateInlineValue_messageText += '<a href="javascript:void" onclick="javascript:revertEditor(\''+editorID+'\', \''+editorRevertId+'\');">Revert</a>'
			}
		}
    } catch (error) {
    	//execute this block if error
    	updateInlineValue_messageText = 'There was an error trying to insert the processed text. Please contact the system administrator for help.';
    }
	

	messageDiv = dojo.byId(editorMessageId);
//	console.debug('messageDiv', messageDiv);
	if(null != messageDiv) {
		dojo.destroy(messageDiv);
	}
	dojo.place('<div id="'+editorMessageId+'">'+updateInlineValue_messageText+'</div>', editorID, 'before');
	
	countTextArea(editorID);
}
var currentFocus = '';
$().ready(function() {

   /*	
   $('#submitButton').focus(function() {
       currentFocus = 'submitButton';
   });

   $('#submitButton').blur(function() {
       currentFocus = '';
   });

   $('#submitButton3').focus(function() {
       currentFocus = 'submitButton';
   });

   $('#submitButton3').blur(function() {
       currentFocus = '';
   });
   */

   try {
   $('.jsavebutton').mouseenter(function() {
       currentFocus = 'submitButton';
   });

   $('.jsavebutton').mouseleave(function() {
       currentFocus = '';
   });
   } catch (error) {
        //execute this block if error
        updateInlineValue_messageText = 'There was an error trying to insert the processed text. Please contact the system administrator for help.';
   }

});
/*
 * called when users exit a rich text editor
 */
function updateInlineValueTest(editorID, updateValue, purify, revertLinkFlag){

	//console.debug('PROCESS TEXT PRIOR TO EDITOR PROCESSING', updateValue);
	debugValue = false;
	
	// for editors
	
	updateInlineValue_counter_visible_difference=0;
	updateInlineValue_counter_hidden_difference=0;
	
	updateInlineValue_messageText = 'Your text has been cleaned. ';
	
	editorMessageId = editorID+'-message';
	editorRevertId = editorID+'-revert';

	submitObj = new Object();
	x = new Object();
	
	submitObj.data = dojo.toJson(updateValue);
	submitObj.id_editor = editorID;
//	submitObj.id_form = dojo.attr('formnum', 'value');
	
    var formNum = dojo.byId("form_number").value;
    try {
    	submitObj.id_form = dojo.byId("id_form_"+formNum).value;
    } catch (error) {
    	//execute this block if error
    	//'There was an error getting the form id';
    }
	
    // send to server to be saved
	var xhrArgs = {
		content : submitObj,
		handleAs : "json",
        url: '/form'+formNum+'/processeditor',
		// sync: wait2finish, // should we wait till the call is done before
		// continuing
		sync : true,
		load : updateEditorCallback,
		error : updateEditorError
	};
	var deferred = dojo.xhrPost(xhrArgs); 
	
}

/*
 * CALLBACK for updateInlineValueTest
 */
function updateEditorCallback(data)
{
	var form_num = dojo.byId("form_number").value;
	var returneditems = data.items;
	editorID = returneditems[0]['id_editor'];

	updateValue = returneditems[0]['id_editor_data'];
	
	editorMessageId = editorID+'-message';
	editorRevertId = editorID+'-revert';
	
	console.debug('editorID', editorID, editorID.substring(editorID.length-4));

    try {
		if('-Editor' == editorID.substring(editorID.length-7))
		{
			var hiddenEditorID = editorID.substring(0, editorID.length-7);
			var hiddenElement = dojo.byId(hiddenEditorID);
			var originalValue = hiddenElement.value;

	        // set values into page elements
			dijit.byId(editorID).setValue(updateValue);
			dojo.byId(hiddenEditorID).value = updateValue;
		}
        if('_ifr' == editorID.substring(editorID.length-4))
        {
            var hiddenEditorID = editorID.substring(0, editorID.length-4);
            var hiddenElement = dojo.byId(hiddenEditorID);
            var originalValue = hiddenElement.value;

            // save to background field
            $('#' + hiddenEditorID).val(updateValue); // dirty hack to fix ajax requested page to save first time
            $('#' + hiddenEditorID).tinymce().save();

            // save to server
            tinyMceSaveToEditorHistory(hiddenEditorID, updateValue);

            // make sure editor shows it's modified
            // and enable the save buttons
            colorMeById(hiddenEditorID);
            modified('', '', '', '', '', '');


//            // set values into page elements
//            dijit.byId(editorID).setValue(updateValue);
//            dojo.byId(hiddenEditorID).value = updateValue;

            colorMeEditor(hiddenEditorID, '');
        }
    } catch (error) {
    	//execute this block if error
    	updateInlineValue_messageText = 'There was an error trying to insert the processed text. Please contact the system administrator for help.';
        console.debug('updateInlineValue_messageText', updateInlineValue_messageText);
    }
 
    
    try {
    if ('submitButton' == currentFocus) { savePageDeferred(null, "/form"+form_num+"/jsonupdateiep"); } 
    } catch (error) {
        //execute this block if error
        updateInlineValue_messageText = 'There was an error trying to insert the processed text. Please contact the system administrator for help.';
    }
   

    /*    
    setTimeout(function() { 
    	console.debug('jSaveFocused', jSaveFocused);
    	if ('mouseover' == jSaveFocused) { savePageDeferred(null, "/form"+form_num+"/jsonupdateiep"); } }, 200);
     */

	console.debug('end updateInlineValueTest');
}

function updateEditorError(error) {
	alert('error'+ error);
}
/* is used 200111201
 * called on textarea editors - inside tabs
 * called from spell checker
 */
function updateInlineValueTextArea(textareaID, updateValue, purify) {
	// textareaEditors
    var hiddenElement = dojo.byId(textareaID);
    var originalValue = hiddenElement.value;
    colorMeEditor(textareaID, originalValue);
    
    //
	submitObj = new Object();
	x = new Object();
	
	submitObj.data = dojo.toJson(updateValue);
	submitObj.id_editor = textareaID;
	
    var formNum = dojo.byId("form_number").value;
	
    // send to server to be saved
	var xhrArgs = {
		content : submitObj,
		handleAs : "json",
        url: '/form'+formNum+'/processeditor',
		// sync: wait2finish, // should we wait till the call is done before
		// continuing
		sync : true,
		load : updateTextAreaCallback,
		error : updateEditorError
	};
	dojo.xhrPost(xhrArgs); 
}
/*
 * CALLBACK for updateInlineValueTest
 */
function updateTextAreaCallback(data)
{
	var returneditems = data.items;
	editorID = returneditems[0]['id_editor'];

	updateValue = returneditems[0]['id_editor_data'];
	
    try {
    	dojo.byId(editorID).value = updateValue;
    } catch (error) {
    	//execute this block if error
    	updateInlineValue_messageText = 'There was an error trying to insert the processed text. Please contact the system administrator for help.';
    }
//	console.debug('end updateInlineValueTextArea');
}


function showDialogTwo(editorContent) {
    // set the content of the dialog:
	purifierDialog = new dijit.Dialog({
        title: "Programatic Dialog Creation",
        style: "width: 700px"
    });
	purifierDialog.attr("content", editorContent);
	purifierDialog.show();
}
function countTextArea(editorID) {
	maxlimit = 50000;
	editorCounterId = editorID+'-counter';
	editor = dijit.byId(editorID).getValue();
	if(editor.length > maxlimit) {
		messageStyle = 'background-color:red;';
		counterMessage = 'This editor has exceeded the recommended capacity of '+maxlimit+' characters. Please remove some text before saving. Current count:'+editor.length+' characters.'
	} else {
		messageStyle = '';
		counterMessage = 'This editor currently contains '+editor.length+' characters. The max is '+maxlimit+'. '+(maxlimit-editor.length)+' remain.'; 
	}
	counterDiv = dojo.byId(editorCounterId);
//	console.debug('counterDiv', counterDiv);
	if(null != counterDiv) {
		dojo.destroy(counterDiv);
	}
	dojo.place('<div id="'+editorCounterId+'" style="'+messageStyle+'">'+counterMessage+'</div>', editorID, 'before');
	
}








var editorPreProcess = function(updateValue, debugValue, debugCount) {
//	console.debug('editorPreProcess', updateValue);

	//console.debug('match count', updateValue.match(/(.*?)<p>(.*?)<p>(.*?)<\/p><\/p>(.*?)/gim));
	// hoping to keep this browser independent
	
	// remove styles before decoding because of
	// double quoted fonts
	// <span style="font-size: 12pt; font-family: &quot;Lucida Grande&quot;;">
	// becomes 
	// <span style="font-size: 12pt; font-family: "Lucida Grande";">
	// after decoding
	updateValue = removeStyles(updateValue);
	if(debugValue) console.debug('initial styles removed', updateValue);
	//dojo.byId('debugger').value += "___removeStyles----------------------------------\r\n" + updateValue;

	// remove <style> tag as it's contents may remain
	// when strip() is run and that throws off char counts
	updateValue = strip_styleTagsWhole(updateValue);
	//dojo.byId('debugger').value += "___strip_styleTagsWhole----------------------------------\r\n" + updateValue;
	
//	preLength = stripHtml(updateValue).length;
	if(debugValue) console.debug('updateValue 1', updateValue);
//	if(debugCount) console.debug('___after strip_styleTagsWhole:', updateValue.length, stripHtml(updateValue).length);
	
	updateValue = msEmptyLineToBr(updateValue);
	if(debugValue) console.debug('msEmptyLineToBr', updateValue);
	//dojo.byId('debugger').value += "___msEmptyLineToBr----------------------------------\r\n" + updateValue;
	
	updateValue = trimText(updateValue);

	
//	console.debug('editorPreProcess', updateValue);
	
	return updateValue;
}


var removeStyles = function(text) {
	// remove styles before decoding because of
	// double quoted fonts
	// <span style="font-size: 12pt; font-family: &quot;Lucida Grande&quot;;">
	// becomes 
	// <span style="font-size: 12pt; font-family: "Lucida Grande";">
	// after decoding
	var s = text;    
    // MS class tags and comment tags.
    s = s.replace(/\sstyle\=(\'|\")(.*?)(\1)/gim, "");//
    return s;
}
var replaceParagraphsWithDivs = function(text) {
	var s = text;    
    s = s.replace(/<p>(&nbsp;|\u00A0| )*<\/p>/gim, "<div></div>");
    s = s.replace(/<p>/gim, "<div>");
    s = s.replace(/<\/p>/gim, "</div>");
    return s;
}
var replaceEmptySpansWithSpanBr = function(text) {
	var s = text;    
    s = s.replace(/<span><\/span>/gim, "<span><br /></span>");//(&nbsp;|\u00A0| )*
    return s;
}
var strip_styleTagsWhole = function(text) {
    var s = text;    
    // Style tags
    s = s.replace(/(?:<style([^>]*)>([\s\S]*?)<\/style>|<link\s+(?=[^>]*rel=['"]?stylesheet)([^>]*?href=(['"])([^>]*?)\4[^>\/]*)\/?>)/gi, ""); 
    return s;
}

var msEmptyLineToBr= function(text) {
    var s = text;    
    
    // ff on win
    // WORD Empty Paragraph
	s = s.replace(/<p class="MsoNormal">(&nbsp;|\u00A0| )*<\/p>/gim, "<br />");
	
	// FF on mac - spans are added inside the p when pasting in 
	// WORD Empty Paragraph
	s = s.replace(/<p class="MsoNormal"([^>]*)><span([^>]*)>(&nbsp;|\u00A0| )*<\/span><\/p>/gim, "<br />");
	return s;
}


var spacesToUnderscores = function(text) {
    var s = text;    
    // MS class tags and comment tags.
    s = s.replace(/ /gim, "_");//
    return s;
}
//function stripHtml(html)
var stripHtml = function(html) {
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent||tmp.innerText;
}
var strip_styleTagsWhole = function(text) {
    var s = text;    
    // Style tags
    s = s.replace(/(?:<style([^>]*)>([\s\S]*?)<\/style>|<link\s+(?=[^>]*rel=['"]?stylesheet)([^>]*?href=(['"])([^>]*?)\4[^>\/]*)\/?>)/gi, ""); 
    return s;
}

var strip_styleClassPadding= function(text) {
    var s = text;    
    // remove additional class, padding
    s = s.replace(/\sclass\=(\'|\")(.*?)(\1)/gim, ""); 
    s = s.replace(/\scellpadding\=(\'|\")(.*?)(\1)/gim, ""); 
    
    return s;
}
var strip_multipleSpaces= function(text) {
    var s = text;    
    // condense multiple whitespaces
    s = s.replace(/ +/gim, " "); 
//    s = s.replace(/<[\S]+?><\/[\S]+?>/gim, "");
    
    //This javascript code removes all 3 types of line breaks
//    s = s.replace(/\n/g,"").replace(/\r/g,"");
    return s;
}
var lineBreakToSpace= function(text) {
    var s = text;    
    
    //replace line breaks with spaces
//    s = s.replace(/\n/gim," ").replace(/\r/gim," ");
    s = s.replace(/([^>])\n([^<])/gim,"$1 $2").replace(/([^>])\r([^<])/gim,"$1 $2");
    return s;
}
var removeLineBreaksAndIeEmptyParagraphs= function(text) {
    var s = text;    
    
    //replace line breaks with spaces
    s = s.replace(/[\u000d]/gim, ""); // \r
	s = s.replace(/[\u000a][\u000a]/gim, ""); // \n
	s = s.replace(/[\u000a]/gim, ""); // \n
	s = s.replace(/[\u2028]/gim, ""); // Line separator
	s = s.replace(/[\u2029]/gim, ""); // Paragraph separator
	
	if(dojo.isIE >= 6){
		s = s.replace(/<p><\/p>/gim, ""); // IE Empty Paragraph
	}
//	if(dojo.isIE >= 8){
		s = s.replace(/<p> <\/p>/gim, ""); // IE Empty Paragraph
//	}
    return s;
}
var spanSpaceToNonBreakingSpace= function(text) {
    var s = text;    
    
    //replace line breaks with spaces
//    s = s.replace(/<p><span> *<\/span><\/p>/gim,"<BR />");
    return s;
}

var ajacentParagraphTags= function(text) {
    var s = text;    
	s = s.replace(/<\/p><p>/gim, "</p><p>");
	return s;
}
var trimText= function(text) {
    var s = text;    
	s = s.replace(/^\s*/gim, "");
	s = s.replace(/^\r\n*/gim, "");
	s = s.replace(/^\r*/gim, "");
	s = s.replace(/^\n*/gim, "");
	return s;
}

//var clickInsert= function(text) {
//	alert('here');
//    var s = text;    
//	s = s.replace(/&apos;/gim, "'");
//	return s;
//}

//dojo.addOnLoad(
//		function() { 
//			setTimeout(
//					function() { 
//						dojo.byId('debugger').value += 'Hidden.....' +dojo.byId('present_lev_perf').value;
//						dojo.byId('debugger').value += 'Onload.....' +dijit.byId('present_lev_perf-Editor').getValue();
//						}, 
//						3000
//						)
//						} 
//		);
//
//function setFromHidden(editorID) {
//	var hiddenEditorID = editorID.substring(0, editorID.length-7);
//	var val = dojo.byId(hiddenEditorID).value;
//	dijit.byId(editorID).setValue(clickInsert(val));
//}


//function layoutAdjustNormal() {
//	// display left content pane
//	dojo.style(dijit.byId('srs_left').domNode, "display", "inline");
//
//	// make center content pane normal size
//	dojo.style(dijit.byId('srs_mainPane').domNode, "left", "170px");
//	dojo.style(dijit.byId('srs_mainPane').domNode, "width", "735px");
//
//	// show page_navigation_controlbar
//	dojo.style(dojo.byId('page_navigation_controlbar'), "display", "none");
//	dojo.style(dojo.byId('page_navigation_controlbar2'), "display", "none");
//}
//function layoutAdjustWide() {
//	// hide left content pane
//	dojo.style(dijit.byId('srs_left').domNode, "display", "none");
//
//	// make center content pane wider and align left
//	dojo.style(dijit.byId('srs_mainPane').domNode, "left", "0px");
//	dojo.style(dijit.byId('srs_mainPane').domNode, "width", "900px");
//	
//	// show page_navigation_controlbar
//	dojo.style(dojo.byId('page_navigation_controlbar'), "display", "inline");
//	dojo.style(dojo.byId('page_navigation_controlbar2'), "display", "inline");
//	
//	
//}



function editorModified(id) {
	console.debug('editorModified');
//	alert('here');
}

function addEditorStyleSheets(subformName, tabNum) {
	// append stylesheets
	// loop through widgets looking for editors
	dojo.query('[widgetid]', dojo.byId(subformName + '_' + tabNum)).forEach(
		function(widget) {
			if('-Editor' == widget.id.substring(widget.id.length-7))
			{
				editor = dijit.byId(widget.id);
				editor.addStyleSheet('/css/dojo_editor_additional_test.css');
				if(dojo.isIE >= 9){ // only IE9 and below
					console.debug('add stylesheet 9');
					editor.addStyleSheet('/css/dojo_editor_additional_IE9.css');
				} else if(dojo.isIE >= 8){ // only IE8 and below
					console.debug('add stylesheet 9');
					editor.addStyleSheet('/css/dojo_editor_additional_IE8.css');
				}
			}
		}
	);
}


function getSubTabCheckedValue(radioName, containerNode) {
	var retValue = "";
	dojo.query('input:checked', containerNode).forEach(
		function(childNode) {
			if(radioName == childNode.name) {
				retValue = childNode.value;
				return childNode.value;
			}
		});
	return retValue;
}

function editorHistory(fieldName) {
	var formNum = dojo.byId('form_number').value;
	linkString = 'http://'+window.location.host+'/editor-history/index/formnum/'+parseFloat(formNum);
	linkString += '/id/'+dojo.byId('id_form_'+formNum).value;
	linkString += '/field/'+fieldName;
	window.open(linkString,'_newtab');
}
function editorEmpty(editorId) {
	data = $('#'+editorId).val();
	if(''==data) return true;
	if('<br _moz_editor_bogus_node="TRUE" />'==data) return true;
	if('<br />'==data) return true;
	return false;
}
function setTextareaContents(editorId, value) {
	$('#'+editorId).val(value);
	$('#'+editorId+'_iframe').contents().find('body').html(value);	// tinyMce editors
	$('#'+editorId+'-Editor_iframe').contents().find('body').html(value); // dojo editors
	return true;
}

function setEditorContents(editorId, value) {
	$('#'+editorId).val(value);
	$('#'+editorId+'-Editor_iframe').contents().find('body').html(value);
	return true;
}

//dojo
dojo.addOnLoad(attachSaveAction);
dojo.addOnLoad(showDataAssistants);

// jquery
$().ready(function() {
//	console.debug('enable datepicker');
	$( ".datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true
	});
	/*
	 * hack to refresh editors becaues they're erroring in FF11
	 * remove if we find a better editor like tinyMce
	 */
	$('.dijitTab').live('click', function() {
		console.debug('refresh editors for FF11');
	    $('.dijitEditor').toggleClass("refreshEditor");
	});
	var $tabs = $('.jTabContainer').tabs({
	    add: function(event, ui) {
	    	console.debug('ui.panel.id', ui);
	        $tabs.tabs('select', '#' + ui.panel.id);
	    },
	    create: function(event, ui) {
//            console.debug('create');
	    	initTinyMce($(this).find('div.ui-tabs-panel:not(.ui-tabs-hide)').find('div.tinyMceEditor').children('textarea'));
            // init jquery datepickers

        },
	    select: function(event, ui) {
            /**
             * this code runs when you add a tab
             */
			console.debug('selecting', ui.panel.id);
			if(0==$('#'+ui.panel.id+' textarea:tinymce').length) {
                console.debug('select', $('#'+ui.panel.id+' div.tinyMceEditor'), $('#'+ui.panel.id+' div.tinyMceEditor').children('textarea'));
				initTinyMce($('#'+ui.panel.id+' div.tinyMceEditor').children('textarea'));
                $( ".datepicker" ).datepicker({
                    changeMonth: true,
                    changeYear: true
                });
            }
		}
	});
});



/**
 * new 201308 - jlavere
 */

/**
 * update date 2 when it's empty and date 1 changes
 * @param date1selector - should identify datepicker element
 * @param date2selector
 * @param endDate
 */
function setDate2WhenDate1ChangesByClass(date1selector, date2selector, endDate)
{
    var parsedDate = $.datepicker.parseDate('yy-mm-dd', endDate);
    $(date1selector).change(function() {
    	if ('' == $(this).closest('div').find(date2selector).val())
    	{
    		$(this).closest('div').find(date2selector).datepicker('setDate', parsedDate);
    	}
    });
}
