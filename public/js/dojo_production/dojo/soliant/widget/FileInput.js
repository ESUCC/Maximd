/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["soliant.widget.FileInput"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["soliant.widget.FileInput"] = true;
dojo.provide("soliant.widget.FileInput");
dojo.require("dijit.form._FormWidget");
dojo.require("dijit._Templated");
dojo.declare("soliant.widget.FileInput", [ dijit.form._FormWidget, dijit._Templated ], {
	
	// summary: A styled input type="file"
	//
	// description: A input type="file" form widget, with a button for
	// uploading to be styled via css,
	// a cancel button to clear selection, and FormWidget mixin to provide
	// standard
	// dijit.form.Form
	//
	// label: String
	// the title text of the "Browse" button
	label : "Browse ...",

	// cancelText: String
	// the title of the "Cancel" button
	cancelText : "Cancel",

	// name: String
	// ugh, this should be pulled from this.domNode
	name : "uploadFile",

	templateString:"<div class=\"dijitFileInput\" dojoAttachPoint=�containerNode�>\n\t<input id=\"${id}\" class=\"dijitFileInputReal\" type=\"file\" dojoAttachPoint=\"fileInput\" name=\"${name}\" />\n    <div class=\"dijitFakeInput\">\n\t\t<input class=\"dijitFileInputVisible\" type=\"text\" dojoAttachPoint=\"focusNode, inputNode\" />\n\t    <span class=\"dijitFileInputText\" dojoAttachPoint=\"titleNode\">${label}</span>\n\t    <span class=\"dijitFileInputButton\" dojoAttachPoint=\"cancelNode\" dojoAttachEvent=\"onclick:_onClick\">\n\t    \t${cancelText}\n\t\t</span>\n    </div>\n</div>\n",

	startup : function() {
		// summary: listen for changes on our real file input
		this.inherited("startup", arguments);
		this._listener = dojo.connect(this.fileInput, "onchange", this, "_matchValue");
		this._keyListener = dojo.connect(this.fileInput, "onkeyup", this, "_matchValue");
	},
	
	_matchValue : function() {
		// summary: set the content of the upper input based on the
		// semi-hidden file input
		this.inputNode.value = this.fileInput.value;
		if (this.inputNode.value) {
			this.cancelNode.style.visibility = "visible";
			dojo.fadeIn({
				node : this.cancelNode,
				duration : 275
			}).play();
		}
	},

	_onClick : function(/* Event */e) {
		// summary: on click of cancel button, since we can't clear the
		// input because of
		// security reasons, we destroy it, and add a new one in it's place.
		// Disconnect the listeners so they're not orphaned, and cleanly
		// remove the tag
		dojo.disconnect(this._listener);
		dojo.disconnect(this._keyListener);
		this.domNode.removeChild(this.fileInput);
		// Fade our the cancel button so we no longer can press it
		dojo.fadeOut({
			node : this.cancelNode,
			duration : 275
		}).play();
		// Create an identical input tag
		this.fileInput = document.createElement('input');
		this.fileInput.setAttribute("type", "file");
		this.fileInput.setAttribute("id", this.id);
		this.fileInput.setAttribute("name", this.name);
		dojo.addClass(this.fileInput, "dijitFileInputReal");
		// this.domNode is the root DOM node of the widget
		this.domNode.appendChild(this.fileInput);
		// Finally, connect the listeners to this new node.
		this._keyListener = dojo.connect(this.fileInput, "onkeyup", this, "_matchValue");
		this._listener = dojo.connect(this.fileInput, "onchange", this, "_matchValue");
		this.inputNode.value = "";
	},

	_onMyButtonClick : function(evt) {
		//do something with the event
		console.debug('inside');
	}


});

}
