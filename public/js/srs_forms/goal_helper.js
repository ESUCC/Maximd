//	    if(v){
			a=new Array();
			b=new Array();
//		}
		  //==============================================================
		  //=========   B array  =========================================
		  //==============================================================

            var bArrIndex = -1;
            var bLvl1;
            var bLvl2;
            var bLvl3;

            function bO(txt,val) {
                bLvl1++;
                b[bArrIndex][bLvl1] = new Array();
                b[bArrIndex][bLvl1].text = txt;
                b[bArrIndex][bLvl1].value = val;
                bLvl2 = -1;
            }
            
            function bOO(txt,val) {
                bLvl2++;
                b[bArrIndex][bLvl1][bLvl2] = new Array();
                b[bArrIndex][bLvl1][bLvl2].text = txt;
                b[bArrIndex][bLvl1][bLvl2].value = val;
                bLvl3 = -1;
            }
            
            function bOOO(txt,val) {
                bLvl3++;
                b[bArrIndex][bLvl1][bLvl2][bLvl3] = new Array();
                b[bArrIndex][bLvl1][bLvl2][bLvl3].text = txt;
                b[bArrIndex][bLvl1][bLvl2][bLvl3].value = val;
            }
            
            function newBarray(){
                bArrIndex++;
                b[bArrIndex] = new Array();
                bLvl1 = -1;
            }

function resize_2_local_settings() {
	window.resizeTo(screen.width,screen.height)
}

function getFormNum (formName) {
        var formNum =-1;
        for (i=0;i<document.forms.length;i++){
				tempForm = document.forms[i];
				if (formName == tempForm) {
				        formNum = i;
				        correctForm = tempForm;
				        break;
				}
        }
        return formNum;
}


function jmp(form, elt)
{
        if (form != null) {
				with (form.elements[elt]) {
				        if (0 <= selectedIndex)
								location = options[selectedIndex].value;
				}
        }
}





//==============================================================
//=========   build B array  ===================================
//==============================================================
// fill array
//if (v) { // ns 2 fix
//newBarray();
//} // if (v)

function bRelate(formName,elementNum,j,selectID,subSelectID) {
	console.debug("bRelate: ", formName, elementNum, j, selectID, subSelectID);
	console.debug('option', typeof(Option), Option);

    chosenOption = 0;
//    if(v){
		var formNum = getFormNum(formName);
		if (formNum>=0) {
	        with (document.forms[formNum].elements[elementNum + 1]) {
				for(i=options.length-1;i>0;i--) options[i] = null; // null out in reverse order (bug workarnd)
				console.debug("b[j]: ", b[j]);
				for(i=0;i<b[j].length;i++){
					console.debug("b[j][i].text: ", b[j][i].text);
			        options[i] = new Option(b[j][i].text,b[j][i].value); 
			        if(selectID == b[j][i].value && (selectID) != 0) {
							chosenOption = i;
			        }
				}
				options[chosenOption].selected = true;
	        }
	        with (document.forms[formNum].elements[elementNum + 2]) {
				for(i=options.length-1;i>0;i--) options[i] = null;
				options[0] = new Option("Waiting for Topic","");
			}
		}
//    } else {
//    	console.debug("bRelate - error - v not found. ");
//    }
}

// j is the index of the district item in the array, ie the [num] in the array of the dist
// k is the county index in the array
// elementNum is menu index of the menu on the page
function bRelate2(formName,elementNum,j,selectID) {

        chosenOption = 0;
//        if(v){
				var formNum = getFormNum(formName);

				// find first menu's selection
				k = document.forms[formNum].elements[elementNum - 1].selectedIndex;
				if(k<0)k=0; // precaution against missing selected in first menu list - abk
				with (document.forms[formNum].elements[elementNum + 1]) {
				        for(i=options.length-1; i>=0; i--) options[i] = null; // null out in reverse order (bug workarnd)

				        if(k > 0) {
				        	if(b[k][j].length > 0) {
								for(i=0; i< b[k][j].length; i++){
								        options[i] = new Option(); 
								        options[i].text = b[k][j][i].text; 
								        options[i].value = b[k][j][i].value; 

								        if((selectID == b[k][j][i].value) && (selectID) != 0) {
												chosenOption = i;
								        }
								}
								options[chosenOption].selected = true;
							} else {
								options[0] = new Option(); 
								options[0].text = "None"; 
								options[0].value = "None"; 
							}
				        } else {
								options[0] = new Option("...waiting for district", "");
								        with (document.forms[formNum].elements[elementNum]) {
												options[0] = new Option("...waiting for county", "");
								        }
				        }
				}
				//bRelate3(formName,elementNum + 2,0, 0);
//         }
}

function bRelate3(formName,elementNum,j,selectID) {

        //alert(elementNum);
        chosenOption = 0;
//        if(v){
				var formNum = getFormNum(formName);

				// find first menu's selection
				l = document.forms[formNum].elements[elementNum - 3].selectedIndex;
				k = document.forms[formNum].elements[elementNum - 2].selectedIndex;
				j = document.forms[formNum].elements[elementNum - 1].selectedIndex;

				with (document.forms[formNum].elements[elementNum + 2]) {
				        for(i=options.length-1; i>=0; i--) options[i] = null; // null out in reverse order (bug workarnd)

				        //alert(        "bRelate3  \n" +  "l: " + l + "\n" +  "k: " + k + "\n" +  "j: " + j + "\n" +  "b[l][k][j].length: " + b[l][k][j].length);

				        for(i=0; i< b[l][k][j].length; i++){
								options[i] = new Option(); 
								options[i].text = b[l][k][j][i].text; 
								options[i].value = b[l][k][j][i].value; 

								if((selectID == b[l][k][j][i].value) && (selectID) != 0) {
								        chosenOption = i;
								}
				        }
				        //options[chosenOption].selected = true;
				}

				//alert(        "bRelate3  \n" +  "l: " + l + "\n" +  "k: " + k + "\n" +  "j: " + j  );
         
         
         
//         }
}



// selectID is the ID of the element that you want selected
// subSelectID is the ID of the element that you want selected in the next menu
// elementNum is the index of the menu to change
function relate(formName,elementNum,j,selectID,subSelectID) {
        chosenOption = 0;
//        if(v){
				var formNum = getFormNum(formName);
				if (formNum>=0) {
				        with (document.forms[formNum].elements[elementNum + 1]) {
								for(i=options.length-1;i>0;i--) options[i] = null; // null out in reverse order (bug workarnd)
								for(i=0;i<a[j].length;i++){
								        options[i] = new Option(a[j][i].text,a[j][i].value); 
								        if(selectID == a[j][i].value && (selectID) != 0) {
												chosenOption = i;
								        }
								}
								options[chosenOption].selected = true;
				        }
				 //relate2(formName,elementNum + 1,chosenOption,0, subSelectID);
				}
//        }
}


function objSearch(formName) {
    var formNum = getFormNum(formName);
    if(document.formGoal.objectiveCode.value == '' && document.formGoal.objDomainSelect.value != '' && document.formGoal.topicSelect.value != '' && document.formGoal.subtopicSelect.value != '') {
			//alert("inside");
			document.formGoal.objectiveCode.value = '';
			document.formGoal.conditionCode.value = '';
    }
    formName.submit();
}


function conSearch(formName) {
	formName.submit();
}

function chooseConDomain() {
	dojo.byId('conditionCode').value = '';
}

function selectCondition(selectValue) {
	if(selectValue != '') {
		for(i=0; i<document.formGoal.conditionSelect.length; i++){
			if(document.formGoal.conditionSelect[i].value == selectValue) {
				dojo.byId('displayCon').innerHTML = document.formGoal.conditionSelect[i].text + ", ";
				document.formGoal.displayConHid.value = document.formGoal.conditionSelect[i].text + ", ";
				document.formGoal.displayConValHid.value = selectValue;
				break;
			}    	
		}
	}
}


function selectAStandard(selectValue) {
	if(selectValue != '') {
		//alert(selectValue);
		//alert(document.formGoal.selectStandard.length);
		for(i=0; i<document.formGoal.selectStandard.length; i++){
			//alert(document.formGoal.selectStandard.length);
			if(document.formGoal.selectStandard[i].value == selectValue) {
				dojo.byId('displayStd').innerHTML = document.formGoal.selectStandard[i].text;
				document.formGoal.displayStandardHid.value = document.formGoal.selectStandard[i].text;
				document.formGoal.displayStandardValHid.value = selectValue;
				break;
			}    	
		}
	}
}

function selectObjective(selectValue) {
	if(selectValue != '') {
        for(i=0; i<document.formGoal.objectiveSelect.length; i++){
        	//alert(document.formGoal.objectiveSelect[i].value + " = " + selectValue);
        	if(document.formGoal.objectiveSelect[i].value == selectValue) {
				//alert(document.formGoal.objectiveSelect[i].text);
				if(document.formGoal.objectiveSelect[i].text.substring(0,4) == "will") {
					document.formGoal.displayObjHid.value = document.formGoal.objectiveSelect[i].text;
					dojo.byId('displayObj').innerHTML = document.formGoal.objectiveSelect[i].text;
				} else {
					document.formGoal.displayObjHid.value = "will " + document.formGoal.objectiveSelect[i].text;
					dojo.byId('displayObj').innerHTML = "will " + document.formGoal.objectiveSelect[i].text;
				}
        		document.formGoal.displayObjValHid.value = selectValue;
        		break;
        	}    	
        }
	}
}

function resetConDomain(selectValue) {
    for(i=0; i<document.formGoal.domainSelect.length; i++){
    	//alert(document.formGoal.domainSelect[i].value + " = " + selectValue);
    	if(document.formGoal.domainSelect[i].value == selectValue) {
			document.formGoal.domainSelect[i].selected = true;
    		break;
    	}    	
    }
}

function resetStandardDomain(selectValue) {
    for(i=0; i<document.formGoal.standardMenu.length; i++){
    	//alert(document.formGoal.domainSelect[i].value + " = " + selectValue);
    	if(document.formGoal.standardMenu[i].value == selectValue) {
			document.formGoal.standardMenu[i].selected = true;
    		break;
    	}    	
    }
}

function resetObjDomain(selectValue) {
    for(i=0; i<document.formGoal.objDomainSelect.length; i++){
    	//alert(document.formGoal.objDomainSelect[i].value + " = " + selectValue);
    	if(document.formGoal.objDomainSelect[i].value == selectValue) {
			document.formGoal.objDomainSelect[i].selected = true;
    		domainIndex = i;
    		break;
    	}    	
    }
}

function resetObjTopic(selectValue) {
	try {
	    for(i=0; i<document.formGoal.topicSelect.length; i++){
	    	console.debug(document.formGoal.topicSelect[i].value + " = " + selectValue);
	    	if(document.formGoal.topicSelect[i].value == selectValue) {
				document.formGoal.topicSelect[i].selected = true;
	    		topicIndex = i;
	    		break;
	    	}    	
	    }
	} catch (error) {
		console.debug('javscript error in resetObjTopic');
	}
}

function resetObjSubtopic(selectValue) {
	try {
	    for(i=0; i<document.formGoal.subtopicSelect.length; i++){
	    	if(document.formGoal.subtopicSelect[i].value == selectValue) {
				document.formGoal.subtopicSelect[i].selected = true;
	    		break;
	    	}    	
	    }
	} catch (error) {
		console.debug('javscript error in resetObjSubtopic');
	}
    
}

function insertGoalText(rownum, runType) {
	try {
		if(runType == "Goal_Helper") {
	        theText = window.opener.dijit.byId('iep_form_004_goal_'+rownum+'-measurable_ann_goal').attr("value");
		} else if(runType == "Obj_Helper") {
			theText = window.opener.dojo.byId('iep_form_004_goal_'+rownum+'-short_term_obj').value;
		}
		
		if(theText == '' || theText.substring(theText.length-1, theText.length) == "\n" || theText.substring(theText.length-1, theText.length) == "\r") {
			neededReturn = "";
		} else {
			neededReturn = "<BR />";
		}

		if(document.formGoal.displayConHid.value != '' && document.formGoal.displayObjHid.value != '' && document.formGoal.displayStandardHid.value != '') {
			if(runType == "Goal_Helper") {
				//window.opener.dijit.byId('iep_form_004_goal_'+rownum+'-measurable_ann_goal-Editor').setValue(theText + neededReturn + document.formGoal.displayConHid.value  + document.formGoal.studentName.value + " " + document.formGoal.displayObjHid.value + " " + document.formGoal.displayStandardHid.value);
				window.opener.dijit.byId('iep_form_004_goal_'+rownum+'-measurable_ann_goal').attr("value", theText + neededReturn + document.formGoal.displayConHid.value  + document.formGoal.studentName.value + " " + document.formGoal.displayObjHid.value + " " + document.formGoal.displayStandardHid.value);
				window.opener.updateInlineValueTextArea('iep_form_004_goal_'+rownum+'-measurable_ann_goal',  theText + neededReturn + document.formGoal.displayConHid.value  + document.formGoal.studentName.value + " " + document.formGoal.displayObjHid.value + " " + document.formGoal.displayStandardHid.value)

			} else if(runType == "Obj_Helper") {
				//window.opener.dijit.byId('iep_form_004_goal_'+rownum+'-short_term_obj-Editor').setValue(theText + neededReturn + document.formGoal.displayConHid.value  + document.formGoal.studentName.value + " " + document.formGoal.displayObjHid.value + " " + document.formGoal.displayStandardHid.value);
				window.opener.dijit.byId('iep_form_004_goal_'+rownum+'-short_term_obj').attr("value", theText + neededReturn + document.formGoal.displayConHid.value  + document.formGoal.studentName.value + " " + document.formGoal.displayObjHid.value + " " + document.formGoal.displayStandardHid.value);
				window.opener.updateInlineValueTextArea('iep_form_004_goal_'+rownum+'-short_term_obj',  theText + neededReturn + document.formGoal.displayConHid.value  + document.formGoal.studentName.value + " " + document.formGoal.displayObjHid.value + " " + document.formGoal.displayStandardHid.value);
			}
			opener.modified('', '', '', '', '', '');
			close();
		} else {
			alert("You must select a condition and an objective.");
		}

	} catch (error) {
		console.debug('javscript error in insertGoalText');
	}

	
}

var domainIndex;
var topicIndex;
var subtopicIndex;

function init() {
//	alert(window.name);
	window.focus();
	console.debug('asdf', dojo.byId('objDomainSelect').selectedIndex);
	if(dojo.byId('objDomainSelect').selectedIndex > 0) {
		bRelate(document.formGoal,3,dojo.byId('objDomainSelect').selectedIndex,0,0);
		resetObjTopic(dojo.byId('resetTopicSelect').value);

		console.debug('topicSelect', dojo.byId('topicSelect').selectedIndex);
		console.debug('sub topicSelect', dojo.byId('resetSubtopicSelect').value);
		if(dojo.byId('topicSelect').selectedIndex > 0) {
			bRelate2(document.formGoal,4,dojo.byId('topicSelect').selectedIndex,0,0);
			resetObjSubtopic(dojo.byId('resetSubtopicSelect').value);
		}
	}
}
dojo.addOnLoad(init);
