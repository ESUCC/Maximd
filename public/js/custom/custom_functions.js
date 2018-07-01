// /etc/httpd/srs-zf/public/js/custom 
function displayValidationList() {
    console.debug('val list: ', dojo.byId('validationList').style.display);
    if (dojo.byId('validationList').style.display == 'inline') {
        dojo.byId('validationList').style.display = 'none';
    } else {
        dojo.byId('validationList').style.display = 'inline';
    }
}


function makeValidationListTable(errorCount, errorArray)
{	
	//
	// builds html table of error messages about field validity
	//
	console.group('function:: makeValidationListTable')
    console.debug('errorArray: ', errorArray);
    
    var valTable=document.createElement("Table");
    valTable.id = 'validationList';
    valTable.style.display = 'none';
    
    var tbody = document.createElement("tbody");
    valTable.appendChild(tbody);
    
//    var  oTR= valTable.insertRow(0);
//    var  oTD= oTR.insertCell(0);


    if(0 == errorCount)
    {
    	console.debug('errorCount none: ', errorCount);
//    	row1.setAttribute('class', "bts");
//    	cell1.innerHTML = 'nothing here';
        var addRows = false;
    } else {
	    var row1 = document.createElement("TR");                         // create row element
	    var cell1 = document.createElement("TD");                       // create cell element
    	console.debug('errorCount: ', errorCount);
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
		            console.debug('building error text');
		            var rowCount = valTable.getElementsByTagName("TR").length;      // get row count of validationList
		
		            console.debug('tbody3', tbody);
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
		            console.debug('build error appendChild - END - this is good');
		        }
		    );
    }
    
    console.groupEnd();
    return tbody;
}




function updatePage(gotopage)
{
	dojo.byId('page').value = gotopage;
	document.surveyform.submit();
}
function navigateToPage(gotopage, formObj)
{
    console.debug(gotopage, formObj);
    console.debug($(formObj).closest('form').find('#page'));
    console.debug($(formObj).closest('form').find('#page')[0]);
	$(formObj).closest('form').find('#page').first().val(gotopage);
	formObj.form.submit();
}

function addQuestion(questionID, add_before_id, add_after_id)
{
	dojo.byId('addquestionid').value = questionID;
	dojo.byId('add_before_id').value = add_before_id;
	dojo.byId('add_after_id').value = add_after_id;
	document.surveyform.submit();
}
