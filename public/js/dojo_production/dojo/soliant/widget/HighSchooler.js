/*
	Copyright (c) 2004-2011, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["soliant.widget.HighSchooler"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["soliant.widget.HighSchooler"] = true;
// dojo.provide allows pages to use all of the
// types declared in this resource.
dojo.provide("soliant.widget.HighSchooler");

// dojo.require the necessary dijit hierarchy
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

dojo.declare("soliant.widget.HighSchooler", [ dijit._Widget, dijit._Templated ], {
	
	// Path to the template
	templateString : dojo.cache("soliant.widget", "HighSchooler/HighSchooler.html", "<div class=\"errorReporter\">\n  <div class=\"avatar\">\n    <img dojoattachpoint=\"imageNode\"></img>\n  </div>\n  <div>\n    <ul class=\"profile\">\n      <li><span class=\"label\">Name</span>\n\t    <span dojoattachpoint=\"nameNode\"></span>\n\t  </li>\n\t  <li><span class=\"label\">Grade</span>\n\t\t<span dojoattachpoint=\"gradeNode\"></span>\n\t  </li>\n\t  <li><span class=\"label\">Hero</span>\n\t\t  <span dojoattachpoint=\"heroNode\"></span>\n\t  </li>\n\t</ul>\n  </div>\n</div>\n"),

	// Widget properties
	fullName : "",
	grade : "",
	hero : "",
	stereotype : "",

//	test: 'monkey',
	
	// Manipulate the widget's DOM, when ready
	postCreate : function() {
		// Using the attributes defined via dojoattachpoint
		this.nameNode.innerHTML = this.fullName;
		this.gradeNode.innerHTML = this.grade;
		this.heroNode.innerHTML = this.hero;

		// Setting the avatar to be the appropriate image
		var keys = {
			emo : "emo.png",
			geek : "geek.png",
			punk : "punk.png"
		};
		var key = keys[this.stereotype] || "na.png";
		var imagePath = dojo.moduleUrl("widgets",
				"themes/highschool/images/" + key).toString();

		// Set the 'src' attribute on our <img>
		dojo.attr(this.imageNode, "src", imagePath);
	}
});

}
