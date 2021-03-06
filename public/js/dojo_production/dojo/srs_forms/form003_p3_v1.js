/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


function toggleTeamMemberAbsences(on_off_checkbox) {
	
//	console.debug('on_off_checkbox', on_off_checkbox);
	if(on_off_checkbox == 1) {
		//dojo.byId("on_off_checkbox").innerHTML = "Uncheck box to remove absence reports.";
		
		// Sets the first label of the parent of the checkbox
		dojox.html.set(dojo.byId("on_off_checkbox").parentNode.getElementsByTagName('label')[0], 'Uncheck box to remove absence reports.');
		dojo.byId("subformToChange").style.visibility = "visible";
	} else {
		//dojo.byId("on_off_checkbox").innerHTML = "Check the box to report team member absence(s).";
		dojox.html.set(dojo.byId("on_off_checkbox").parentNode.getElementsByTagName('label')[0], 'Check the box to report team member absence(s).');
		dojo.byId("subformToChange").style.visibility = "hidden";
	}
}

function toggleTeamMemberInput(value, id) {
//	console.debug('toggleTeamMemberInput');
	dash = id.indexOf('-');
	if(dash > 0) {
		id = 'team_member_input-'+id.substring(0, dash);
		divToToggle = dojo.byId(id);
	}
	
	if(value == 'mod') {
		dojo.byId(divToToggle).style.display = "inline";
	} else {
		dojo.byId(divToToggle).style.display = "none";
	}
}

//function toggleTeamMemberRole(element) {
//	id = element.id;
//	console.debug('toggleTeamMemberRole', element.id);
//	dash = id.indexOf('-');
//	if(dash > 0) {
//		id = id.substring(0, dash) + '-absence_role_other';
//	}
//	divToToggle = dojo.byId(id);
//	console.debug('divToToggle', id);
//	subformShowHideField(element.id, id, element.value, 'Other (Please Specify)');
//
//}
