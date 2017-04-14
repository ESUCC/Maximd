function toggleShowOther() {
    if ($('#meeting_type_eligible_ifsp').is(':checked') || $('#meeting_type-1').is(':checked')) {
        // hide other_desc
        $('#additional-attendees').show();
    } else {
        $('#additional-attendees').hide();
    }
}

$().ready(function () {
	$('#additional-attendees').hide();
    toggleShowOther();
    $('#meeting_type_eligible_ifsp').click(function () {
    	if ($('#meeting_type_eligible_ifsp').is(':checked')) {
    		$('#additional-attendees').show();
    	} else if (!$('#meeting_type-1').is(':checked')) {
    		$('#additional-attendees').hide();
    	}
    });
    
    $('#meeting_type-1').click(function () {
        $('#additional-attendees').show();
    });
    
    $('#meeting_type-2').click(function () {
    	if (!$('#meeting_type_eligible_ifsp').is(':checked')) {
    		$('#additional-attendees').hide();
    	}
    });
    
    $('#meeting_type-3').click(function () {
    	if (!$('#meeting_type_eligible_ifsp').is(':checked')) {
    		$('#additional-attendees').hide();
    	}
    });
    
    $('#meeting_type_eligible_iep').on('click', function() {
    	meetingTypeContainer()
    });
});

function meetingTypeContainer() {
	var obj = $('#meeting_type_eligible_iep');
	if (obj.is(':checked')) {
		$('.meeting_type_containter').show();
	} else {
		$('.meeting_type_containter').hide();
	}
}