dependencies ={
layers: [
    {
        // where to put the output relative to the Dojo root in a build
        name: "../APP/srslayer.js",
        
        // what to name it (redundant w/ or example layer)
        //resourceName: "App.Global",
        
        // what other layers to assume will have already been loaded
        // specifying modules here prevents them from being included in
        // this layer's output file
        layerDependencies: [
            "dojo.js"
        ],
        
        // which modules to pull in. All of the depedencies not
        // provided by dojo.js or other items in the "layerDependencies"
        // array are also included.
        dependencies: [
			"APP",
			"APP.Global",

			"dijit.layout.ContentPane",
			"dijit.layout.TabContainer",
			"dijit.layout.BorderContainer",
			"dijit.Calendar",
			"dijit.Editor",
			"dijit._editor.plugins.AlwaysShowToolbar",
			"dijit.form.Form",
			"dijit.form.TextBox",
			"dijit.form.Button",
			"dijit.form.DateTextBox",
			"dijit.form.TimeTextBox",
			"dijit.Dialog",
			"dojo.parser",
			"dojox.json.ref",
			"dojox.timing._base",
			"dojox.html._base",

//			"APP.custom_functions",
//			"APP.common_form_functions",
			"APP.timer_countdown_multiple",
			"APP.date_format",

        ]
    },

],
prefixes: [
	// buildscript will try to copy folders relative to the release into the release folder
	[ "dijit", "../dijit" ],
	[ "dojox", "../dojox" ],
	[ "APP", "/etc/httpd/srs-zf/public/js/srs_forms" ],
]
};

// the build process
// 
// 
// remove file that conflicts with svn
// svn rm public/js-src/dojo_release/srsdojo/dojox/grid/compatGrid.tar.gz --force
//mv /usr/local/zend/apache2/htdocs/dojobuildfolder/APP/*.js /usr/local/zend/apache2/htdocs/srs-zf/public/APP
//mv /usr/local/zend/apache2/htdocs/dojobuildfolder/APP/nls/*.js /usr/local/zend/apache2/htdocs/srs-zf/public/APP/nls
//rm -Rf /usr/local/zend/apache2/htdocs/dojobuildfolder/

/*
 *  jesse dev
	cd /usr/local/zend/share/ZendFramework/externals/dojo/util/buildscripts
	./build.sh profileFile=/usr/local/zend/apache2/htdocs/srs-zf/scripts/dojoBuildScripts/srs.profile.js action=release releaseDir=/usr/local/zend/apache2/htdocs/srs-zf/public/js/dojo_release/ releaseName="srsdojo"
	cd /usr/local/zend/apache2/htdocs/srs-zf

*
*	live
	cd /usr/local/zend/share/ZendFramework/externals/dojo/util/buildscripts
	./build.sh profileFile=/etc/httpd/srs-zf/scripts/dojoBuildScripts/srs.profile.js action=release releaseDir=/etc/httpd/srs-zf/public/js/dojo_release/ releaseName="srsdojo"

*
*/