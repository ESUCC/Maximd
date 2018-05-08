$(function() {
    /**
     * override main cancel/done button
     */
    $('#cancel').click(function() {
        window.location.href = 'https://iep.unl.eduu/srs.php?area=personnel&sub=list';
        return false;
    });
    /**
     * override links for view and edit and remove
     */
    $(document).on('click', '.btn-view', function(e) {
        e.preventDefault();
        var loadingId = $(this).closest('dd').attr('id')+'_loading';
        $('#'+loadingId).remove();
        getPrivilegeForm(loadingId, this, this.href, 'edit');
    });
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        var loadingId = $(this).closest('dd').attr('id')+'_loading';
        $('#'+loadingId).remove();
        getPrivilegeForm(loadingId, this, this.href, 'edit');
    });
    $(document).on('click', '.btn-remove', function(e) {
        e.preventDefault();
        console.debug('remove', this.href);

        clickedLink = $(this);

        var r=confirm("Are you sure you want to remove this privilege?")
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
                        alert('There was an error deleting this contact.');
                    }
                }
            });

        }
    });
    /**
     * inside the view/edit form, override the submit and cancel buttons
     */
    $(document).on('click', '.list-container #submit', function(e) {
        e.preventDefault();
        var loadingId = $(this).closest('dd').attr('id')+'_loading';
        savePrivilegeForm($(this).closest('form'), loadingId, this, this.href)
    });
    $(document).on('click', '.list-container #cancel', function(e) {
        e.preventDefault();
        $(this).closest('form').closest('fieldset').remove();
    });
});
/* Get the rows which are currently selected */
function fnGetSelected(oTableLocal) {
    var aReturn = new Array();
    var aTrs = oTableLocal.fnGetNodes();
    for (var i = 0; i < aTrs.length; i++) {
        if ($(aTrs[i]).hasClass('row_selected')) {
            aReturn.push(aTrs[i]);
        }
    }
    return aReturn;
}

/**
 * fetch a form via ajax
 * @param loadingId id of the form
 * @param element the element that was clicked (a link)
 * @param url to fetch the form
 * @param viewEdit is form being requested for view or edit
 */
function getPrivilegeForm(loadingId, element, url, viewEdit) {
    console.debug('getPrivilegeForm', "/images/loading.gif");
    $.ajax({
        type: 'GET', // posting will invoke save routines
        dataType: 'json',
        url: url,
        beforeSend: function(data) {
            /* add loading icon and text */
            $(element).closest('fieldset').after('<fieldset id="'+loadingId+'"><div><img ' +
                'src="/images/loading.gif" alt="loading" /><br />Loading ...</div></fieldset>');
        },
        error: function(json) {
            console.debug('error with getPrivilegeForm');
        },
        success: function(json) {
            /* add the form to the page */
            $('#'+loadingId).html(json.result);

            /* init the date pickers */
            $( ".datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true
            });
        }
    });
}
/**
 * save existing form to the server
 * @param form the html form to be saved
 * @param loadingId the id of the element where the loading icon is currently
 * this is the location that the form will be inserted
 * @param element the element that was clicked (a link)
 * @param url to save the form
 */
function savePrivilegeForm(form, loadingId, element, url) {
    console.debug('savePrivilegeForm');
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/personnel/edit-privilege/submit/save/id_privileges/'+$(form).find('#id_privileges').val(),
        data: form.serialize(),
        beforeSend: function(data) {
            $(form).find(":input").attr("disabled", true);
            $(form).before('<div class="loading_wrapper"><img src="/images/loading.gif" ' +
                'alt="loading" /><br />Saving ...</div>');
            $(form).slideUp();
        },
        error: function(json) {
            console.debug('error with savePrivilegeForm');
        },
        success: function(json) {
            if(true==json.saved) {
                $(form).closest('fieldset').find('.loading_wrapper').html('Saved.');
                window.setInterval(function () {
                    var fieldSet = $(form).closest('fieldset');
                    fieldSet.slideUp('slow');
                    var prevFieldSet = fieldSet.prev();
                    prevFieldSet.find('.column_status').html($(form).find('#status').val()+"&nbsp");
                }, 1000);
            } else {
                $(form).closest('fieldset').find('.loading_wrapper').remove();
                $(form).replaceWith(json.result);
                $(form).slideDown('slow');
            }
        }
    });
}
