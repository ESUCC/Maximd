// dojo.provide allows pages to use all of the
// types declared in this resource.
dojo.provide("soliant.widget.HighSchooler");

// dojo.require the necessary dijit hierarchy
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

dojo.declare("soliant.widget.HighSchooler", [ dijit._Widget, dijit._Templated ], {
	
	// Path to the template
	templateString : dojo.cache("soliant.widget", "HighSchooler/HighSchooler.html"),

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
