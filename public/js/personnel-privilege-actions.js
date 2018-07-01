$(function() {
    /**
     * delete student chart template
     */
    $(document).on('click', '.btn-delete-privilege', function(e) {
        e.preventDefault();
        console.debug('remove', this.href);

        clickedLink = $(this);

        var r=confirm("Are you sure you want to remove this Privilege?")
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
    $(document).on('click', '.btn-update-privilege', function(e) {
        e.preventDefault();
        console.debug('remove', this.href);

        clickedLink = $(this);

        var r=confirm("Are you sure you want to update this Privilege?")
        if (r==true) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: this.href+'/submit/remove',
                beforeSend: function(data) {
                    clickedLink.closest('fieldset').slideUp();
                },
                success: function(json) {
                    if(json.success) {
                        console.debug('clickedLink', clickedLink.attr('href'));
                        if('Active' == json.status) {
                            /**
                             * priv is now active
                             * switch link to inactivate
                             */
                            clickedLink.closest('span').siblings('.column_status').html('Active');
                            clickedLink.html('Inactivate');
                            clickedLink.attr('href', clickedLink.attr('href').replace('/status/Active', '/status/Inactive'));
                        } else if('Inactive' == json.status) {
                            /**
                             * priv is now inactive
                             * switch link to activate
                             */
                            clickedLink.closest('span').siblings('.column_status').html('Inactive');
                            clickedLink.html('Activate');
                            clickedLink.attr('href', clickedLink.attr('href').replace('/status/Inactive', '/status/Active'));
                        }
                        clickedLink.closest('fieldset').slideDown();

                    } else {
                        alert('There was an error deleting this Student Chart Template.');
                    }
                }
            });

        }
    });
});