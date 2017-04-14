dependencies ={
layers: [
    {
        
        // where to put the output relative to the Dojo root in a build
        name: "../srs_forms/includes.js",
                
        // which modules to pull in. All of the depedencies not
        // provided by dojo.js or other items in the "layerDependencies"
        // array are also included.
        dependencies: [
            "soliant.widget",
            "srs_forms.includes",

//			"srs_forms.custom_functions",
//			"srs_forms.common_form_functions",
 			"srs_forms.timer_countdown_multiple",
 			"srs_forms.date_format",

        ]
    }
],
prefixes: [
	// buildscript will try to copy folders relative to the release into the release folder
	[ "dijit", "../dijit" ],
	[ "dojox", "../dojox" ],
	[ "srs_forms", "../../../srs_forms"],
	[ "soliant", "../../soliant"],
	[ "srs_css", "../../../../css"]
]
};

// the build process
// -- this is no longer the process.
// current process replaces all old code and doesn't require maintenence
// 
// remove file that conflicts with svn
// svn rm public/js-src/dojo_release/srsdojo/dojox/grid/compatGrid.tar.gz --force
//mv /usr/local/zend/apache2/htdocs/dojobuildfolder/APP/*.js /usr/local/zend/apache2/htdocs/srs-zf/public/APP
//mv /usr/local/zend/apache2/htdocs/dojobuildfolder/APP/nls/*.js /usr/local/zend/apache2/htdocs/srs-zf/public/APP/nls
//rm -Rf /usr/local/zend/apache2/htdocs/dojobuildfolder/

/*
 *  go to the dir and run the build
 *  
    cd /usr/local/zend/apache2/htdocs/srs-zf/public/js-src/dojo_development/dojo/util/buildscripts
  	./build.sh profileFile=/usr/local/zend/apache2/htdocs/srs-zf/scripts/dojoBuildScripts/srs.profile.js action=release cssOptimize=comments optimize=shrinkSafe releaseDir="../../../../dojo_production_20110531"
	
	
*/