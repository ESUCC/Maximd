var searchFormsUrl = '/student/search-student-forms/page/1';

$(document).ready(function() {
	$('#search').click(function() {
		runSearchCall(searchFormsUrl);
	});
	
	// This submits the form on enter
	addSubmitOnChange('#formSearchForm', searchFormsUrl);

    runSearchCall(searchFormsUrl);
});

function formCreateType(formId, studentId, isDemoStudent) {
		window.location.href = "/form"+formId+"/create/student/"+studentId;
}

function formCreateTypeOld(formId, studentId) {
	
}

function addSubmitOnChange(formId, url) {
	$(formId+' input').add(formId+' select').change(function() {
        runSearchCall(searchFormsUrl);
    });
}

//function addSubmitOnEnter() {
//	$('input').keypress(function(e){
//	     if(e.which == 13){
//	    	 runSearchCall('/student/search-student-forms/page/1/');
//	     }
//	});
//}
//
//function exportToCSV(id) {
//	$('#studentSearchForm').attr('action', '/student/export-result-list-to-csv/id/'+id);
//    $('#studentSearchForm').submit();
//}
//
//function printResults(id) {
//    var searchForm = $('#studentSearchForm').serialize();
//    window.open('/student/print-results/?id='+id+'&'+searchForm, 'Print_Results', 'width=925,height=600,scrollbars=1');
//}

function runSearchCall(url)
{
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: url,
		data: $('#formSearchForm').serialize(),
		beforeSend: function(data) {
			$('#column-container').hide();
        	var id = 'searchResults';
            var position = $('#' + id).position(); 
            $('.loading').css('top', $('#' + id).offset().top);
            $('.loading').css('left', $('#' + id).offset().left-1);
            $('.loading').css('width', $('#' + id).width()-2);
            $('.loading').css('height', $('#' + id).height());
            $('.loading').fadeIn();
        },
		success: function(json) {
			$('#searchResults').html(json['result']);
			$('#searchResults .studentOptions').each(function() { 
				$(this).change(function() {
					if ($(this).val() != '')
						window.location.href = 'https://iep.unl.edu/srs.php?area=student&sub=student'+$(this).val();
				});
			});
			$('.loading').fadeOut();
			
			$('.paginator li a').click(function() {
				runSearchCall($(this).attr('href'), null);
			});

            /**
             * jlavere
             * setup callback for student form menu
             */
            $('#searchResults select.formMenuSelect').on('change', function(event){
                var text = $(this).find(":selected").text();
                var href = $(this).find(":selected").attr('href');
                console.debug('text', text);
                if ('Log' == text) {
                    window.open(href, "myWindow", "status=1, height = 500, width = 800, resizable = 0, scrollbars = 1");
                } else if ('Print' == text) {
                	var win = window.open(href,'_blank');
                	win.focus();
                } else {
                    window.location.href = href;
                }

            });

        }
	});
}


