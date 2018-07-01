// dojo.provide allows pages to use all of the
// types declared in this resource.
dojo.provide("soliant.widget.FileUploader");

// dojo.require the necessary dijits for templated
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

// dojo.require the necessary dijits for widgets
// used in the template
dojo.require("dijit.form.Form");
dojo.require("dijit.form.Button");
dojo.require("dijit.form.TextBox");

dojo.require("dojox.form.Uploader");
dojo.require("dojox.form.uploader.plugins.Flash");
dojo.require("dojox.form.uploader.FileList");
dojo.require("dojox.form.FileInput");
dojo.require("dojo.io.iframe");

dojo.declare("soliant.widget.FileUploader", [ dijit._Widget, dijit._Templated ], {
	
	// Path to the template
	templateString : dojo.cache("soliant.widget", "FileUploader/FileUploader.html"),

	// are there widgets in the template
	widgetsInTemplate : true,
	
	// applied to the root element
	baseClass: "fileUploader",
	
	// declare attributes
	// you need to declare any attributes that will be used
	// in the template file
	saveurl: '/file-uploader/save',
	saveurlIE: '/file-uploader/save-ie',
	uploaderId: '',
	document_id: null,
	form_number: null,
	parent_id:'',
	fileMask: ["PDF File", "*.pdf"],
	
	uploader: '',
//	isDebug: true,
	
	// setting to true will force all browsers to use the old input style
	forceFileInput: false,

	startup: function() {
		this.inherited(arguments);
		if(null == this.document_id || null == this.form_number) {
			console.debug('Document id or form number are null.');
			return;		
		}
	},
	
	postCreate: function(){
	    // Get a DOM node reference for the root of our widget
	    var domNode = this.domNode;
	 
	    // Run any parent postCreate processes - can be done at any point
	    this.inherited(arguments);
	    
	    if(dojo.isIE || this.forceFileInput) {
			// remove the file list
	    	this.FileUploaderForm.destroyDescendants();
			dojo.destroy(this.FileUploaderForm.attr('id'));

	    	console.debug('running IE style');
	    	// place an old style browse button for a file input upload
	    	this.uploader = new dojox.form.FileInput({
				"class":"FileInputIE",
				
			},this.FileUploaderDivIE);
			this.uploaderId = this.uploader.attr('id');

			
//		dojo.connect(this.uploader, "onComplete", function(dataArray){
//			console.debug('pmoney');
//			dojo.forEach(dataArray, function(d){
//		        console.debug('pmoney');
//		    });
//		});;
			
			// add additional functionality for the upload button
			dojo.connect(this.uploader, "_matchValue", this, "_matchValue");
			dojo.connect(this.uploader, "reset", this, "reset");
			
			// force new css rules
			dojo.removeClass(domNode, "soliantFileUploader");
			dojo.addClass(domNode, "soliantFileUploaderIE");

	    } else {
	    	this.FileUploaderFormIE.destroyDescendants();
			dojo.destroy(this.FileUploaderFormIE.attr('id'));
	    	
	    	console.debug('running normal style');
	    	// place a new style multi file uploader
	    	this.uploader = new dojox.form.Uploader({
	    		label:"Click to select a PDF.",
    			showProgress:true,
    			serverTimeout:30000,
    		    degradable:true,
    		    forceFlash:true,
    			isDebug:true,
	    		
//	    		onComplete:function(dataArray){ console.debug('gorilla'); },
	    		fileMask:this.fileMask
	    	});
	    	dojo.connect(this.uploader, "onComplete", this, "onComplete");
//	    	dojo.connect(this.uploader, "onChange", this, "onChange");
			this.uploaderId = this.uploader.attr('id');
		    this.FileUploaderDiv.appendChild(this.uploader.domNode);

		    // add the uploaerID from the uploader object
		    this.uploadFormFileList.attr('uploaderId', this.uploader.attr('id'));
	    }
	    
	},
	onComplete:function(dataArray){
		console.debug('onComplete');
	},
//	onChange:function(dataArray){
//		console.debug('onChange');
//	},
	upload:function(){
		console.debug('upload');
	    if(dojo.isIE || this.forceFileInput) {
	    	// upload the file with old style input
	    	// IE compatible
//	    	this.FileUploaderFormIE.action="/file-uploader/save";
//	    	this.FileUploaderFormIE.method="POST";
//	    	this.FileUploaderFormIE.enctype="multipart/form-data";
	    	
			submitObj = new Object();
			submitObj.form_number = this.form_number;
			submitObj.document_id = this.document_id;
	    	dojo.io.iframe.send({
			  form: this.FileUploaderFormIE.attr('id'),
		      content:submitObj,
		      url:this.saveurlIE,
		      load : dojo.hitch(this, "callbackIE")
    	   });	    	

	    } else {
			submitObj = new Object();
			submitObj.form_number = this.form_number;
			submitObj.document_id = this.document_id;
			var uploader = dijit.byId(this.uploaderId)
			var result = uploader.upload(submitObj);
			
			dojo.connect(uploader, 'onProgress', function(dataArray){ console.debug('yahoo1'); })
			dojo.connect(uploader, 'onComplete', function(dataArray){ console.debug('ahmen1'); })
			console.debug('result', result);
		    if(null != this.parent_id) {
		    	//console.debug('run REG parent update ', this.parent_id, deferred);
		    	//dijit.byId(this.parent_id).search();
		    }
			
	    }
//	    if(null != this.fileListId) {
//	    	dijit.byId(this.fileListId).search();
//	    }
	},
	
	// FileInput additional functions
	// when either of these functions are call in FileInput
	// they are then called here
	callbackIE:function(data,ioArgs){
		dojo.fadeIn({ node: this.ResultMessage, duration:275 }).play();
		if(data){
			var dataObj = dojo.fromJson(data);
			if(dataObj.status && dataObj.status == "success"){
				// add success message
				this.ResultMessage.innerHTML = "Your file has been uploaded.";
				// reset the uploader for another file
				dijit.byId(this.uploaderId).reset();
			}else{
				// add fail message
//				console.debug('dataObj.errorMessage');
//				console.debug(dataObj.errorMessage);
//				console.debug(dataObj);
//				console.log(dataObj.errorMessage);
//				console.log(dataObj);
				if(dataObj.errorMessage) { // not working
					this.ResultMessage.innerHTML = dataObj.errorMessage;
				} else {
					this.ResultMessage.innerHTML = "There was an error uploading the file.";
				}

				
			}
		}else{
			// add fail message
			this.ResultMessage.innerHTML = "There was an error uploading the file.";
		}
//		dojo.fadeOut({ node: this.UploadButtonIE, duration:275 }).play();
		dojo.fadeOut({ node: this.ResultMessage, duration:275, delay:5000 }).play();
		
	    if(null != this.parent_id) {
//	    	console.debug('run IE parent update ', this.parent_id);
	    	dijit.byId(this.parent_id).search();
	    }
		return data;

	},
	
	_matchValue:function(data,ioArgs){
		//console.debug('_matchValue');
		u = dijit.byId(this.uploaderId);
		if(u.inputNode.value){
			dojo.style(this.UploadButtonIE, "visibility", "visible");
			dojo.fadeIn({ node: this.UploadButtonIE, duration:275 }).play();
		}
		//this.upload();
		
	},
//	_change:function(){
//		console.debug('_change');
//		u = dijit.byId(this.uploadFormFileList);
//		console.debug('u', u, u.length);
//		if(!u.fileList.length){
//			console.debug('files in list');
//		}
//		dojo.fadeIn({ node: this.UploadButton, duration:275 }).play();		
//	},
	reset:function(data,ioArgs){
		dojo.fadeOut({ node: this.UploadButtonIE, duration:275 }).play();
	}
	
});
