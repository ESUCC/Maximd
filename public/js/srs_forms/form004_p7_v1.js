function childQualifies() {
    if ($("input[name='transportation_yn']:checked").val() == 0)
        $("#transportation_why").val('Not Necessary');
}

function configureAssessmentDisplay(event) {
    if($('[name="district_assessments[]"]').length > 0) {
        var displayRadioButtons = 'The child will participate in district-wide assessment WITH accommodations, as specified:' == $('[name="assessment_accom"]:checked').val();
        if(displayRadioButtons && '' != $('#assessment_desc').val() && undefined != event) {
            var r = confirm("Accessment description is not empty, are you sure you want to clear it?");
            if (r == true) {
                tinyMCE.get('assessment_desc').setContent('');
                tinyMCE.get('assessment_desc').save()
            } else {
                return false;
            }
        }
        if(!displayRadioButtons && undefined != $('[name="district_assessments[]"]:checked').val() && undefined != event) {
            var x = confirm("District assessments is not empty, are you sure you want to clear it?");
            if (x == true) {
                $('[name="district_assessments[]"]').prop('checked', false);
            } else {
                return false;
            }
        }
        if(displayRadioButtons) {
            $('#district_assessments-colorme').show();
            $('#assessment_desc-colorme').hide();
        } else {
            $('#district_assessments-colorme').hide();
            $('#assessment_desc-colorme').show();
        }
    } else {
        console.debug('district assessments not present');
    }
}
/**
 * on page ready
 */
$(function() {
    // configure future clicks
    $('[name="assessment_accom"]').on('click', configureAssessmentDisplay);

    // run on page load
    configureAssessmentDisplay();
});