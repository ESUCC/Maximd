var modified = function () {}
var colorMeById = function () {}
function saveIepDataCard(e, controllerRoute, theDialog, finalize) {
    if (undefined == finalize) {
        finalize = false;
    }
    var content = $(e.target).closest('div.ui-dialog').find('form').serializeArray();
    $('.ui-dialog-buttonpane').hide();
    $('.errors').remove();
    return $.ajax({
        type: "POST",
        dataType: "json",
        url: '/' + controllerRoute + '/jsonupdateiep',
        data: content,
        success: function (response) {
            if (response.items && response.items.length > 0 && undefined != response.items[0]['validationArr'] && response.items[0]['validationArr'].length > 0) {
                $.each(response.items[0]['validationArr'], function (index, validationErrorRow) {
                    $(theDialog).find('#' + validationErrorRow.field).closest('.colorme').addClass('errored');
                });
                $('.ui-dialog-buttonpane').show();
            } else if (response.items && response.items.length > 0 && finalize) {
                var docId = response.items[0][response.identifier];
                return $.ajax({
                    type: "POST",
                    dataType: "html",
                    url: '/' + controllerRoute + '/finalize/document/' + docId,
                    data: {confirm: 'Confirm'},
                    success: function (response) {
                        $(theDialog).dialog('close')
                    },
                    fail: function () {
                        content.before('<p class="errors">An error occurred.</p>');
                    },
                    always: function () {
                        $('.ui-dialog-buttonpane').show();
                    }
                });
            } else {
                console.debug(response);
            }
        },
        fail: function () {
            content.before('<p class="errors">An error occurred.</p>');
        },
        always: function () {
            $('.ui-dialog-buttonpane').show();
        }
    });
}
function formsScreen(studentId) {
    return $.ajax({
        type: "POST",
        dataType: "html",
        url: '/student/search-forms/id_student/' + studentId,
        data: content,
        success: function (response) {
            console.debug('response', response);
        },
        fail: function () {
            content.before('<p class="errors">An error occurred.</p>');
        },
        always: function () {
            $('.ui-dialog-buttonpane').show();
        }
    });
}

$(document).ready(function () {

    //$('#searchField0').val('get_name_school(id_county,id_district,id_school)');
    //$('#searchValue0').val('Hastings');

    // mute datatable alerts
    $.fn.dataTableExt.sErrMode = 'mute';

    // override inputs to send datatable
    $('.searchTemplate').off();
    $('.searchTemplate').keypress(function (e) {
        if (e.which == 13) {
            $('#nssrsSearchResults').DataTable().ajax.reload();
        }
    });

    var counter = 1;
    if($('.template').parent().parent().html()) {
        var templateHTML = $('.template').parent().parent().html().replace(/template/, ' ');
        $('.template select').change(function() {
            var tmpHTML = templateHTML.replace(/searchFieldTemplate\-searchFieldTemplate0/, 'searchField'+counter);
            tmpHTML = tmpHTML.replace(/searchFieldTemplate\[searchFieldTemplate0\]/, 'searchField[search'+counter+']');
            tmpHTML = tmpHTML.replace(/searchValueTemplate\-searchValueTemplate0/, 'searchValue'+counter);
            tmpHTML = tmpHTML.replace(/searchValueTemplate\[searchValueTemplate0\]/, 'searchValue[search'+counter+']');
            tmpHTML = tmpHTML.replace(/class=\"removeHidden\"/, ' id="remove'+counter+'" class="remove" ');
            $('#search-fields').append(tmpHTML);
            $('#remove'+counter).click(function(){
                $(this).parent().parent().remove();
            });
            $('#searchField'+counter).val($(this).val()).attr('selected', 'selected');
            $('#searchValue'+counter).focus();
            var isGrade = false;
            if ('s.grade' == $(this).val() ||
                's.gradegreaterthan' == $(this).val() ||
                's.gradelessthan' == $(this).val()) {
                isGrade = true;
            }
            $(this).val('').attr('selected', 'selected');
            counter++;
            //addSubmitOnEnter();
            addGradeSelectOptions('#searchField'+(counter-1), '#searchValue'+(counter-1), (counter-1), isGrade);
            addEthnicGroupOptions('#searchField'+(counter-1), '#searchValue'+(counter-1), (counter-1), 'ethnic_group' == $(this).val());
            addPrimaryLanguageOptions('#searchField'+(counter-1), '#searchValue'+(counter-1), (counter-1), 'primary_language' == $(this).val());
            addPrimaryServiceOptions('#searchField'+(counter-1), '#searchValue'+(counter-1), (counter-1), 'primaryOrRelatedService' == $(this).val());
        });
    }

    $('#format').off();

    function addGradeSelectOptions(fieldId, valueId, counter, isGrade) {
        var OriginalHTML = $(valueId).parent().html();
        $(fieldId).change(function() {
            if ('s.grade' == $(this).val() ||
                's.gradegreaterthan' == $(this).val() ||
                's.gradelessthan' == $(this).val()) {
                $(valueId).replaceWith('<select id="'+valueId.substr(1)+'" class="searchTemplate" name="searchValue[search'+counter+']">'+$('#gradeOptions').html()+'</select>');
            } else {
                $(valueId).replaceWith(OriginalHTML);
            }
        });
        if (isGrade) {
            $(valueId).replaceWith('<select id="'+valueId.substr(1)+'" class="searchTemplate" name="searchValue[search'+counter+']">'+$('#gradeOptions').html()+'</select>');
        }
    }
    function addEthnicGroupOptions(fieldId, valueId, counter, isEthnicGroup) {
        var OriginalHTML = $(valueId).parent().html();
        $(fieldId).change(function() {
            if ('ethnic_group' == $(this).val()) {
                $(valueId).replaceWith('<select id="'+valueId.substr(1)+'" class="searchTemplate" name="searchValue[search'+counter+']">'+$('#ethnicGroupOptions').html()+'</select>');
            } else {
                $(valueId).replaceWith(OriginalHTML);
            }
        });
        if (isEthnicGroup) {
            $(valueId).replaceWith('<select id="'+valueId.substr(1)+'" class="searchTemplate" name="searchValue[search'+counter+']">'+$('#ethnicGroupOptions').html()+'</select>');
        }
    }
    function addPrimaryLanguageOptions(fieldId, valueId, counter, isPrimaryLanguage) {
        var OriginalHTML = $(valueId).parent().html();
        $(fieldId).change(function() {
            if ('primary_language' == $(this).val()) {
                $(valueId).replaceWith('<select id="'+valueId.substr(1)+'" class="searchTemplate" name="searchValue[search'+counter+']">'+$('#primaryLanguageOptions').html()+'</select>');
            } else {
                $(valueId).replaceWith(OriginalHTML);
            }
        });
        if (isPrimaryLanguage) {
            $(valueId).replaceWith('<select id="'+valueId.substr(1)+'" class="searchTemplate" name="searchValue[search'+counter+']">'+$('#primaryLanguageOptions').html()+'</select>');
        }
    }
    function addPrimaryServiceOptions(fieldId, valueId, counter, isPrimaryOrRelatedService) {
        var OriginalHTML = $(valueId).parent().html();
        $(fieldId).change(function() {
            if ('primaryOrRelatedService' == $(this).val()) {
                $(valueId).replaceWith('<select id="'+valueId.substr(1)+'" class="searchTemplate" name="searchValue[search'+counter+']">'+$('#primaryServiceOptions').html()+'</select>');
            } else {
                $(valueId).replaceWith(OriginalHTML);
            }
        });
        if (isPrimaryOrRelatedService) {
            $(valueId).replaceWith('<select id="'+valueId.substr(1)+'" class="searchTemplate" name="searchValue[search'+counter+']">'+$('#primaryServiceOptions').html()+'</select>');
        }
    }
    function addSubmitOnEnter() {
        $('.srsSearchForm input').unbind('keypress');
        $('.srsSearchForm input').keypress(function(e){
            if(e.which == 13){
                runSearchCall('/student/search-student/page/1/');
            }
        });
    }

    function buildAddFormDialog(controllerRoute, title, createOrEditUrl) {
        $.post(createOrEditUrl, {}).done(function (response) {
            var parentErrorString = "Students must have at least one parent record to create forms.";
            var countParentError = (response.match(new RegExp(parentErrorString, 'g')) || []).length;

            var draftErrorString = "A draft form of this type already exists for this student.";
            var countDraftError = (response.match(new RegExp(draftErrorString, 'g')) || []).length;

            if (countParentError) {
                $('<div>' + parentErrorString + '</div>').dialog({
                    title: 'Error',
                    resizable: false,
                    height: 160,
                    modal: true,
                    buttons: {
                        "Ok": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            } else if (countDraftError) {
                $('<div>' + draftErrorString + '</div>').dialog({
                    title: 'Error',
                    resizable: false,
                    height: 160,
                    modal: true,
                    buttons: {
                        "Ok": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            } else {
                var displayForm = $(response).find('form');
                displayForm.find('ul#nav').closest('table').hide();
                displayForm.find('div#page_navigation_controlbar').hide();
                displayForm.find('div#formSectionHead').hide();
                displayForm.find('div#savingDialog').hide();
                displayForm.find('div.noprint').hide();
                displayForm.dialog({
                    title: title,
                    height: 560,
                    width: 660,
                    modal: true,
                    buttons: {
                        'Cancel': function () {
                            $(this).dialog('close');
                        },
                        'Save Draft': function (e) {
                            var theDialog = this;
                            saveIepDataCard(e, controllerRoute, this)
                                .done(function () {
                                    $(theDialog).dialog('close');
                                    var nssrsDataTable = $('#nssrsSearchResults').DataTable();
                                    nssrsDataTable.ajax.reload();
                                });
                        },
                        'Save and Finalize': function (e) {
                            var theDialog = this;
                            saveIepDataCard(e, controllerRoute, this, true)
                                .done(function () {
                                    $(theDialog).dialog('close');
                                    var nssrsDataTable = $('#nssrsSearchResults').DataTable();
                                    nssrsDataTable.ajax.reload();
                                });
                        }
                    }
                });

                if (response.success) {
                    df.resolve(response.data);
                } else {
                    //content.before(buildErrors(response, content));
                }
            }
        }).fail(function () {
                content.before('<p class="errors">An error occurred.</p>');
            }
        );
    }

    function buildFormCenterDialog(studentId) {
        $.post('/student/search-forms/id_student/' + studentId, {}).done(function (response) {
            var parentErrorString = "Students must have at least one parent record to create forms.";
            var countParentError = (response.match(new RegExp(parentErrorString, 'g')) || []).length;

            var draftErrorString = "A draft form of this type already exists for this student.";
            var countDraftError = (response.match(new RegExp(draftErrorString, 'g')) || []).length;

            if (countParentError) {
                $('<div>' + parentErrorString + '</div>').dialog({
                    title: 'Error',
                    resizable: false,
                    height: 160,
                    modal: true,
                    buttons: {
                        "Ok": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            } else if (countDraftError) {
                $('<div>' + draftErrorString + '</div>').dialog({
                    title: 'Error',
                    resizable: false,
                    height: 160,
                    modal: true,
                    buttons: {
                        "Ok": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            } else {
                var displayForm = $(response).find('form');
                displayForm.find('ul#nav').closest('table').hide();
                displayForm.find('div#page_navigation_controlbar').hide();
                displayForm.find('div#formSectionHead').hide();
                displayForm.find('div#savingDialog').hide();
                displayForm.find('div.noprint').hide();
                displayForm.dialog({
                    title: 'Form Center',
                    height: 560,
                    width: 660,
                    modal: true,
                    buttons: {
                        'Cancel': function () {
                            $(this).dialog('close');
                        },
                        'Save Draft': function (e) {
                            var theDialog = this;
                            saveIepDataCard(e, controllerRoute, this)
                                .done(function () {
                                    $(theDialog).dialog('close');
                                    var nssrsDataTable = $('#nssrsSearchResults').DataTable();
                                    nssrsDataTable.ajax.reload();
                                });
                        },
                        'Save and Finalize': function (e) {
                            var theDialog = this;
                            saveIepDataCard(e, controllerRoute, this, true)
                                .done(function () {
                                    $(theDialog).dialog('close');
                                    var nssrsDataTable = $('#nssrsSearchResults').DataTable();
                                    nssrsDataTable.ajax.reload();
                                });
                        }
                    }
                });

                if (response.success) {
                    df.resolve(response.data);
                } else {
                    //content.before(buildErrors(response, content));
                }
            }
        }).fail(function () {
                content.before('<p class="errors">An error occurred.</p>');
            }
        );
    }

    $('#maxRecs').change(function(e) {
        $('#nssrsSearchResults').DataTable().page.len($(e.target).val());
    });

    var properties = {
        processing: true,
        serverSide: true,
        ordering: false,
        pageLength: $('#maxRecs').val(),
        //dom: '<"top"i><"dtFilterHidden"f>rtp', //<"dtFilterHidden"f> required for initial load in Safari
        dom: '<"top"i><"dtFilterHidden">rtp', // removed f - global search box
        paging: true,
        drawCallback: function (settings) {
            $('#nssrsSearchResults').find('.nssrsAction').change(function (e) {
                var selectedOption = $(e.target).val();
                var studentId = $(e.target).closest('tr').data('studentId');
                var nssrsType = $(e.target).closest('tr').data('nssrsType');
                var id_nssrs_transfers = $(e.target).closest('tr').data('id_nssrs_transfers');

                if ('Forms Screen' == selectedOption) {
                    buildFormCenterDialog(studentId);
                } else if ('View Report Data' == selectedOption) {
                    getStudentNssrsReport(studentId, nssrsType, id_nssrs_transfers);
                } else if ('Edit Student' == selectedOption) {
                    document.location = '/student/edit/id_student/'+studentId;

                } else if ('Add IEP Data Card' == selectedOption || 'Add MDT Data Card' == selectedOption ||
                    'Edit IEP Data Card' == selectedOption || 'Edit MDT Data Card' == selectedOption) {
                    if ('Add IEP Data Card' == selectedOption) {
                        var controllerRoute = 'form023';
                        var title = 'IEP Data Card';
                        var createOrEditUrl = '/form023/create/student/' + studentId;

                    } else if ('Edit IEP Data Card' == selectedOption) {
                        var controllerRoute = 'form023';
                        var title = 'IEP Data Card';
                        var createOrEditUrl = '/form023/edit/document/' + $(e.target).closest('tr').data('draft_id_form_023') + '/page/1';

                    } else if ('Add MDT Data Card' == selectedOption) {
                        var controllerRoute = 'form022';
                        var title = 'MDT Data Card';
                        var createOrEditUrl = '/form022/create/student/' + studentId;

                    } else if ('Edit MDT Data Card' == selectedOption) {
                        var controllerRoute = 'form022';
                        var title = 'MDT Data Card';
                        var createOrEditUrl = '/form022/edit/document/' + $(e.target).closest('tr').data('draft_id_form_022') + '/page/1';
                    }
                    buildAddFormDialog(controllerRoute, title, createOrEditUrl);
                }

                // clear menu
                $(e.target).val('');
            });
        },
        ajax: {
            "url": "/report/report-search",
            "timeout": 90000, // sets timeout to 3 seconds
            "data": function (data) {
                var searchField = new Array();
                var searchValue = new Array();
                data['search_fields'] = {};
                for (i = 0; i < 11; i++) {
                    if ($('#' + 'searchField' + i).val() && $('#' + 'searchValue' + i).val()) {
                        searchField.push($('#' + 'searchField' + i).val());
                        searchValue.push($('#' + 'searchValue' + i).val());
                    }
                }
                //data.maxRecs = $('#maxRecs').val();
                data.searchField = searchField;
                data.searchValue = searchValue;
                data.searchStatus = $('input[name="searchStatus"]:checked').val();
                data.reportFormat = $('#format').val();
                return data;
            },
            "type": "POST",
        },
        columns: [{
            "title": "Complete?",
            "name": "nssrsComplete",
        }, {
            "title": "Name",
            "name": "name_full",
        }, {
            "title": "Type",
            "name": "nssrsType",
        }, {
            "title": "Current IFSP/IEP?",
            "name": "currentIep",
        }, {
            "title": "Current MDT?",
            "name": "currentMdt",
        }, {
            "title": "NSSRS ID#",
            "name": "nssrsId"
        }, {
            "title": "Exit Date",
            "name": "exitDate"
        }, {
            "title": "Exit Reason",
            "name": "exitCode"
        }, {
            "title": "Options",
            "name": "nssrsOptions"
        },]
    };

    properties.ajax.error = function handleAjaxError(xhr, textStatus, error) {
        console.debug('error xhr, textStatus, error');
        if (textStatus === 'timeout') {
            console.debug('The server took too long to send the data.');
        } else {
            console.debug('An error occurred on the server. Please try again in a minute.');
        }
    }

    var oTable = $('#nssrsSearchResults').DataTable(properties);
    $('#search').off('click');
    $('#search').click(function () {
        var nssrsDataTable = $('#nssrsSearchResults').DataTable();
        nssrsDataTable.ajax.reload();
    });
    $('#showAll').hide();

});

function getStudentNssrsReport(studentId, nssrsType, id_nssrs_transfers) {

    return $.ajax({
        type: "POST",
        dataType: "html",
        url: '/report/nssrs-get-student-report',
        data: {
            id_student: studentId,
            nssrsType: nssrsType,
            id_nssrs_transfers: id_nssrs_transfers
        },
        success: function (response) {
            var displayForm = $(response).find('#mainPane_body > div:first');
            $(displayForm).find('a').attr('target', '_blank');
            $(displayForm).find('#searchContainer').removeAttr('id');
            displayForm.dialog({
                title: 'Student Forms',
                height: 700,
                width: 800,
                modal: true,
                open: function () {
                    $(this).find(".datepicker").datepicker({
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true
                    });
                },
                buttons: {
                    'Close': function () {
                        $(this).dialog('close');
                    },
                    'Save': function (e) {
                        var dialogRef = $(this);
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: '/report/nssrs-get-student-report',
                            data: {
                                id_student: studentId,
                                nssrsType: 'saveTransfer',
                                id_nssrs_transfers: id_nssrs_transfers,
                                form: displayForm.find('form').serializeArray()
                            },
                            success: function (response) {
                                // remove existing error coloring and messages
                                $('span.btsRed').remove();
                                $('tr.btsRed').removeClass('btsRed');
                                $('.redGreenDot').css('color', 'green');

                                if(response.errorMessages && Object.keys(response.errorMessages).length) {
                                    $.each(response.errorMessages, function(index, element) {
                                        if($('td#'+index)) {
                                            $('#'+index+'-label').addClass('btsRed'); // color the label cell
                                            $('#'+index+'-colorme').after('<span class="btsRed">'+element[Object.keys(element)[0]]+'</span>');
                                            $('#'+index+'-dot').css('color', 'red');
                                        }
                                    });
                                    $('#formValid').html('<span class="btsRed">Incomplete</span>');

                                } else {
                                    $('#formValid').html('<span style="color: green">Complete</span>');
                                }
                                if(response.commaSeparated) {
                                    $('#commaSeparated').text(response.commaSeparated);
                                }

                                if (true == response.success) {
                                    $('#nssrsSearchResults').DataTable().ajax.reload();
                                }
                            },
                            fail: function () {
                                content.before('<p class="errors">An error occurred.</p>');
                            },
                            always: function () {
                                $('.ui-dialog-buttonpane').show();
                            }
                        });
                    }
                }
            });
        },
        fail: function () {
            content.before('<p class="errors">An error occurred.</p>');
        },
        always: function () {
            $('.ui-dialog-buttonpane').show();
        }
    });
}
