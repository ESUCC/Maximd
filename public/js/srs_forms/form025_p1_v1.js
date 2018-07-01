function toggleShowOther() {
    if ($('#schedule_other').is(':checked')) {
        // hide other_desc
        $('#schedule_other_text-colorme').slideDown();
    } else {
        $('#schedule_other_text-colorme').slideUp();
    }
}

$().ready(function () {
    toggleShowOther();
    $('#schedule_other').click(function () {
        $('#schedule_other_text-colorme').slideToggle();
    });
});