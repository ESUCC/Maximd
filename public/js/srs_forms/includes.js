// this file is located at:
//
//   <server_root>/js/srs_forms

 // This is a layer file. It's like any other Dojo module, except that we
 // don't put any code other than require/provide statements in it. When we
 // make a build, this will be replaced by a single minified copy of all
 // the modules listed below, as well as their dependencies, all in the
 // right order:

dojo.provide("includes.js");

dojo.require("dijit.layout.ContentPane");
dojo.require("dijit.layout.TabContainer");
dojo.require("dijit.layout.BorderContainer");
dojo.require("dijit.Calendar");
dojo.require("dijit.Editor");
dojo.require("dijit._editor.plugins.AlwaysShowToolbar");
dojo.require("dijit.form.Form");
dojo.require("dijit.form.TextBox");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.DateTextBox");
dojo.require("dijit.form.TimeTextBox");
dojo.require("dijit.Dialog");
dojo.require("dojo.parser");
dojo.require("dojox.json.ref");
dojo.require("dojox.timing._base");
dojo.require("dojox.html._base");

dojo.require("dijit._editor.plugins.ViewSource");
dojo.require("dojox.editor.plugins.PrettyPrint");
dojo.require("dijit._editor.plugins.AlwaysShowToolbar");
dojo.require("dojox.html.entities");
dojo.require("dojo.fx");
dojo.require("dijit.ProgressBar");
dojo.require("dijit.form.ComboBox");

// finally, some app-specific modules
dojo.require("soliant.widget.ErrorReporter");
dojo.require("soliant.widget.StudentSearch");

// fileUploader, fileList
dojo.require("soliant.widget.FileUploader");
dojo.require("soliant.widget.FileList");
