/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/



function firstLoad(){
	var node = dojo.byId("explanation");
//	console.debug('node', dojo.attr(node, "height"));

//	refresh_editor(node);
	
//	refreshContainer = node.parentNode;
//	
//	
//	console.debug('node', dojo.attr(node, "alt"));
//	console.debug('node', dojo.attr(node, "title"));
////	console.debug('node', node.getValue());
//
//	var exPlug = dojo.attr(node, "extraPlugins");
//	console.debug('node', exPlug);
//	
//	var newEdit = new dijit.Editor({
//			height: ''
//	});
//	
//	
//	dojo.attr(newEdit, "extraPlugins", exPlug)
//	console.debug('node', newEdit);
//	
//	// delete all children
//	while (refreshContainer.hasChildNodes()) {
//		refreshContainer.removeChild(refreshContainer.lastChild);
//	}   
//
//	newEdit.placeAt(dojo.byId("explanation"));
}

dojo.addOnLoad(firstLoad);



function firstLoadod(){
	var node = dojo.byId("explanation");
//	console.debug('node', dojo.attr(node, "height"));
//	console.debug('node', dojo.attr(node, "extraPlugins"));
//	console.debug('node', dojo.attr(node, "alt"));
//	console.debug('node', dojo.attr(node, "title"));
//	console.debug('node', node.value);

	var newEdit = new dijit.Editor({
		height: dojo.attr(node, "height"), 
//		extraPlugins: dojo.attr(node, "extraPlugins"), 
//		onFocus: dojo.attr(node, "onFocus"), 
//		alt: dojo.attr(node, "alt"), 
//		title: dojo.attr(node, "title"), 
//		value: dojo.attr(node, node.value), 
	}, dojo.byId('explanation'));
	
	console.debug('node', newEdit);
}

