/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/




function consideredButNotNecessary(fieldName, value) {
	
//	console.debug('fieldName', fieldName);
//	console.debug('value', value);
	
	try{
		if(dijit.byId(fieldName)) {
			var editor = dijit.byId(fieldName);
		} else if(dijit.byId(fieldName+'-Editor')) {
			var editor = dijit.byId(fieldName+'-Editor');
		}
	//	console.debug('editor', editor);
		
	//	console.debug('consideredButNotNecessary', fieldName, value, editor.attr("value"));
		if(value){
			var stripped = editor.attr("value").replace(/(<([^>]+)>)/ig,"");
			stripped = stripped.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,'');
			if (stripped != "" && stripped !="\n") editor.attr("value", editor.attr("value") + "<br/>");
			var regex = /This was considered by the IEP team, but was deemed unnecessary at the time./;
			var match = editor.attr("value").search(regex);
			if (match == -1){
				editor.attr("value", editor.attr("value") + "This was considered by the IEP team, but was deemed unnecessary at the time.<BR />");
			}
			//dijit.byId(fieldName).value = "Considered<BR>" + dijit.byId(fieldName).value;
			//editor.onChange();
			//alert(editor);
			//alert(editor);
			updateInlineValueTextArea(fieldName, editor.attr("value"));
		}
		
	} catch(error) {
		console.debug('Error in consideredButNotNecessary, possibly editor not found.');
	}
	
}

function makeLarge(id)
{
//	textBigger.play();
	var node = dijit.byId("student_strengths-Editor");
//	console.debug('node', node);
	
//	var node = dojo.byId("student_strengths");
//	console.debug('node', node);
	dojo.fadeOut({
        node: node,
        properties: {
    		fontSize: {end: 25, unit: "px"}
		}
	}).play();
}


var textBigger = dojo.animateProperty(
{
	node: "student_strengths-Editor",
	duration: 1000,
	properties: {
		fontSize: {end: 25, unit: "px"}
	}
});

var textSmaller = dojo.animateProperty(
{
	node: "animDiv",duration: 1000,
	properties: {
		fontSize: {end: 10, unit: "px"}
	}
});
