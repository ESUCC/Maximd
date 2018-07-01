

function consideredButNotNecessary(fieldName, value) {
	
	if('tinyMce'==$('input:radio[name=form_editor_type]:checked').val()) {
		// if value true, add content to the editor
		if(value) {
			if(tinyMCE.get(fieldName)) {
				tinyMCE.get(fieldName).setContent('This was considered by the IEP team, but was deemed unnecessary at the time.<br />'+tinyMCE.get(fieldName).getContent());
				tinyMCE.get(fieldName).save();
			}
		}

	} else {
		// remove when we go tinyMce
		try{
			if(dijit.byId(fieldName)) {
				var editor = dijit.byId(fieldName);
			} else if(dijit.byId(fieldName+'-Editor')) {
				var editor = dijit.byId(fieldName+'-Editor');
			}
			if(value){
				var stripped = editor.attr("value").replace(/(<([^>]+)>)/ig,"");
				stripped = stripped.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,'');
				if (stripped != "" && stripped !="\n") editor.attr("value", editor.attr("value") + "<br/>");
				var regex = /This was considered by the IEP team, but was deemed unnecessary at the time./;
				var match = editor.attr("value").search(regex);
				if (match == -1){
					editor.attr("value", editor.attr("value") + "This was considered by the IEP team, but was deemed unnecessary at the time.<BR />");
				}
				updateInlineValueTextArea(fieldName, editor.attr("value"));
			}
			
		} catch(error) {
			console.debug('Error in consideredButNotNecessary, possibly editor not found.');
		}
	}
	
	
	
}

function makeLarge(id)
{
	var node = dijit.byId("student_strengths-Editor");
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
