$(function() {
    /**
     * delete student chart template
     */
    $(document).on('click', '.btn-remove-student-chart-template', function(e) {
        e.preventDefault();
        console.debug('remove', this.href);

        clickedLink = $(this);

        var r=confirm("Are you sure you want to remove this Student Chart Template?")
        if (r==true) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: this.href+'/submit/remove',
                beforeSend: function(data) {
                    clickedLink.closest('fieldset').after('<fieldset id="deleting">' +
                        '<div><img src="/images/loading.gif" alt="loading" />' +
                        '<br />Deleting ...</div></fieldset>');
                    clickedLink.closest('fieldset').slideUp();
                },
                success: function(json) {
                    if(json.deleted) {
                        console.debug(clickedLink);
                        clickedLink.closest('fieldset').slideUp().remove();
                        $('#deleting').slideUp().remove();
                    } else {
                        alert('There was an error deleting this Student Chart Template.');
                    }
                }
            });

        }
    });
});