$(function() {
	/*
	 * setup a button for the reporter
	 */
	$("#view-helper-error-reporter-create-button").click(function() {
		
		
		$("#view-helper-error-reporter-form-dialog").dialog("open");
	});
	/*
	 * setup the dialog
	 */
	$("#view-helper-error-reporter-form-dialog").dialog({
		autoOpen : false,
		height : 300,
		width : 350,
		modal : true,
		buttons : {
			"Submit Error" : function() {
				// hide the error reporting dialog
				$(this).dialog("close");
				
				// build data to be submitted as json
				data = new Object();
				data.errorDescription = $('#view-helper-error-reporter-description').val();
				data.formNumber = $('#view-helper-error-reporter-form-number').val();
				data.formId = $('#view-helper-error-reporter-form-id').val();
				data.pageNumber = $('#view-helper-error-reporter-page-number').val();
				data.studentId = $('#view-helper-error-reporter-student-id').val();
				data.link = document.URL; // add the current url to the passed data
				
				submitObj = new Object();
				submitObj.data = dojo.toJson(data);

				// submit the post and process result
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: '/gdata/process-editor/',
					data:submitObj,
					url : '/error-reporting/report',
					success : function(json) {
						var returneditems = json.items;
						var successCode = returneditems[0]['id_editor_data'];
						alert('Your error request has been submitted.');
					},
					error : function(data, ioArgs) {
						console.debug('error');
						alert('There was an error while trying to submit your issue. Please contact an administrator.');
					}
				});
			},
			Cancel : function() {
				$(this).dialog("close");
			}
		},
		close : function() {},
		open : function() {
			var formNum = $('#form_number').val();
		    var formID = $("#id_form_"+formNum).val();
		    var page = $('#page').val();
			
		    $('#view-helper-error-reporter-form-number-display').html('Form: '+formNum);
		    $('#view-helper-error-reporter-form-id-display').html('Form ID: '+formID);

		    $('#view-helper-error-reporter-page-number').val(page);
		    $('#view-helper-error-reporter-form-number').val(formNum);
		    $('#view-helper-error-reporter-form-id').val(formID);
		    
		}
	});

});
