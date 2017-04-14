/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["soliant.editor.plugins.SrsPasteFromWord"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["soliant.editor.plugins.SrsPasteFromWord"] = true;
dojo.provide("soliant.editor.plugins.SrsPasteFromWord");

dojo.require("dijit._editor._Plugin");
dojo.require("dijit.form.Button");
dojo.require("dijit.Dialog");
dojo.require("dojo.i18n");
dojo.require("dojo.string");
dojo.require("dojox.html.format");

dojo.requireLocalization("soliant.editor.plugins", "SrsPasteFromWord", null, "");

dojo.declare("soliant.editor.plugins.SrsPasteFromWord",dijit._editor._Plugin,{
	// summary:
	//		This plugin provides PasteFromWord cabability to the editor.  When 
	//		clicked, a dialog opens with a spartan RichText instance to paste
	//		word content into via the keyboard commands.  The contents are 
	//		then filtered to remove word style classes and other meta-junk
	//		that tends to cause issues.

	// _filters: [private] Array
	//		The filters is an array of regular expressions to try and strip out a lot
	//		of style data MS Word likes to insert when pasting into a contentEditable.
	//		Prettymuch all of it is junk and not good html.  The hander is a place to put a function 
	//		for match handling.  In most cases, it just handles it as empty string.  But the option is
	//		there for more complex handling.
	_filters: [
		// Meta tags, link tags, and prefixed tags
		{regexp: /(<meta\s*[^>]*\s*>)|(<\s*link\s* href="file:[^>]*\s*>)|(<\/?\s*\w+:[^>]*\s*>)/gi, handler: ""},  
		// Style tags
		{regexp: /(?:<style([^>]*)>([\s\S]*?)<\/style>|<link\s+(?=[^>]*rel=['"]?stylesheet)([^>]*?href=(['"])([^>]*?)\4[^>\/]*)\/?>)/gi, handler: ""}, 
		// MS class tags and comment tags.
		{regexp: /(class="Mso[^"]*")|(<!--(.|\s){1,}?-->)/gi, handler: ""},
		// blank p tags
		{regexp: /(<p[^>]*>\s*(\&nbsp;|\u00A0)*\s*<\/p[^>]*>)|(<p[^>]*>\s*<font[^>]*>\s*(\&nbsp;|\u00A0)*\s*<\/\s*font\s*>\s<\/p[^>]*>)/ig, handler: ""}, 
		// Strip out styles containing mso defs and margins, as likely added in IE and are not good to have as it mangles presentation.
		{regexp: /(style="[^"]*mso-[^;][^"]*")|(style="margin:\s*[^;"]*;")/gi, handler: ""},
		// Scripts (if any)
		{regexp: /(<\s*script[^>]*>((.|\s)*?)<\\?\/\s*script\s*>)|(<\s*script\b([^<>]|\s)*>?)|(<[^>]*=(\s|)*[("|')]javascript:[^$1][(\s|.)]*[$1][^>]*>)/ig, handler: ""}
	],
	
	_updateContent: function(node){
		// You don't need to handle anything when editor state is updated. 
		// So just leave this empty.
		// Apply all the filters to remove MS specific injected text.\
		
		var content = node.innerHTML;
		console.debug('updateContent', content);
		var i;
		for(i = 0; i < this._filters.length; i++){
			var filter  = this._filters[i];
//			content = content.replace(filter.regexp, filter.handler);
		}
		content = 'test';
		
		// Paste it in.
		this.editor.execCommand("inserthtml", content);

//		// Paste it in.
//		node.innerHTML = 'jesse';
//		return node;
	},
	onchange: function() {
		alert('test');
	},
	
	_initExitUpdate: function(){
		console.debug('_initExitUpdate', this.editor);
//		this.editor.onchange: dojo.hitch(this, "_updateContent");
//		dojo.hitch(this, "_save")
	},
	setEditor: function(editor){
		// summary:
		//		Over-ride for the setting of the editor.
		// editor: Object
		//		The editor to configure for this plugin to use.
		this.editor = editor;
		this._uId = dijit.getUniqueId(this.editor.id);
		this.connect(this.editor, "onchange", this.onchange);
//		this.editor.contentDomPostFilters.push(dojo.hitch(this, this._updateContent));
//		console.debug('setEditor', this.editor.get("value"));
//		this._initButton();
//		this._initExitUpdate();
	}

});

// Register this plugin.
dojo.subscribe(dijit._scopeName + ".Editor.getPlugin",null,function(o){
	if(o.plugin){ return; }
	var name = o.args.name.toLowerCase();
	if(name === "srspastefromword"){
		o.plugin = new soliant.editor.plugins.SrsPasteFromWord();
	}
});

}
