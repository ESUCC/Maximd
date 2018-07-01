function getStudentIdFromStudentOptionsList(item) {
    var student = '';
    $(item).children().each(function(i, option) {
        if(option.value) {
            var keyValueArr = option.value.split('&');
            $.each(keyValueArr, function(index, keyValue) {
                arr = keyValue.split('=');
                if('student'==arr[0]) {
                    student = arr[1];
                }
            });
        }
    });
    return student;
}
function sortUnorderedList(ul, sortDescending) {
    var mylist = $('#'+ul);
    var listitems = mylist.children('li').get();
    listitems.sort(function(a, b) {
        return $(a).text().toUpperCase().localeCompare($(b).text().toUpperCase());
    })
    $.each(listitems, function(idx, itm) { mylist.append(itm); });
}

function checkboxDisplay(toggle) {
    if(toggle) {
        /**
         * add checkboxes to left of student list
         * scrape student_id from the options list
         * which displays regardless of output type
         */
        $('.studentOptions').each(function(index, item) {
            var studentId = getStudentIdFromStudentOptionsList(item);
            $(item).closest('ul').prepend('<li class="checkbox">' +
                '<input type="checkbox" class="collectionCheckbox" data-student-id="'+studentId+'">' +
                '</li>');
        });
        /**
         * add check all and uncheck all
         */
        if($('#CollectionCheckAll').length == 0) {
            $('#searchPseudoTable li.head').prepend('<li class="checkbox">' +
                '<a href="#" id="CollectionCheckAll">Check All</a>' +
                '/' +
                '<a href="#" id="CollectionUncheckAll">Uncheck All</a>' +
                '</li>');
        }
        matchCheckedStateToList();
    } else {
        /**
         * remove checkboxes and the checkall/uncheckall links
         */
        $('.collectionCheckbox').parent().remove();
        if($('#CollectionCheckAll').length > 0) {
            $('#CollectionCheckAll').closest('.checkbox').remove();
        }
    }
}

/**
 * fetch/refresh the list from the server
 * @param id - of the list element
 */
function buildAdditionalHtml(item)
{
    /**
     * assign case manager
     */
    var aditionalHtml = '';
    if(undefined != item.name_case_mgr) {
        aditionalHtml += ' <span class="showon-assign-case-manager" style="display:none;">('+item.name_case_mgr+')</span>';
    } else {
        aditionalHtml += ' <span class="showon-assign-case-manager" style="display:none;"></span>';
    }
    /**
     * assign team
     */
    if(undefined != item.team_member_names && '' != item.team_member_names) {
        aditionalHtml += ' <span class="showon-assign-team-members" style="display:none;">('+item.team_member_names+')</span>';
    } else {
        aditionalHtml += ' <span class="showon-assign-team-members" style="display:none;"></span>';
    }

    return aditionalHtml;
}
function refreshCollectionList(id) {
    $('#'+id).empty();
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/student/group/collection/'+$('#collection').val(),
        success: function(json) {
            /**
             * build the list of links on the left of the page
             */
            if(json.items.length) {
                $(json.items).each(function (index, item){
                    var aditionalHtml = buildAdditionalHtml(item);
                    var addHtml = '<li>' +
//                    '<div><a href="" class="collectionLink" data-student-id="'+item.id+'">'+item.name+'</a>'+aditionalHtml+'</div>' +
                        '<div style="width:100%;">' +
                        '<span style="display:inline;"><a href="" class="collectionLink" data-student-id="'+item.id+'">'+item.name+'</a></span>' +
                        '<span style="float:right;display:inline;"><a href="#" data-student-id="'+item.id+'" class="groupCollectionEdit">Edit</a></span>' +
                        '<span style="float:left;display:inline;width:15px;"><a href="#" data-student-id="'+item.id+'" class="groupCollectionDelete">X</a></span>' +
                        '</div></li>'
                    $('#'+id).append(addHtml);
                });
            }
            matchCheckedStateToList();
            sortUnorderedList(id);
//            listConditionalDisplayLogic($('#linkAction').val());
        },
        error: function(json) {
            console.debug('error in refreshCollectionList', id);
        }
    });

}
function matchCheckedStateToList() {
    /**
     * remove all checkboxes
     */
    $('input.collectionCheckbox:checkbox').attr('checked', false);
    /**
     * check checkboxes that match ids in collectionList
     */
    $('.collectionLink').each(function(index, link) {
        $("input:checkbox[data-student-id='"+$(link).attr('data-student-id')+"']").attr('checked', true);
    });
}

/**
 * performing actions on the collection
 */
function linkAction(studentId, action) {
//    console.debug('studentId', studentId);
//    console.debug('linkAction', action, $('#linkAction').val());
//    if('edit'==action) {
//        window.location.href = '/student/edit/id_student/'+studentId;
//    } else
    if('forms'==action) {
        window.location.href = '/student/search-forms/id_student/'+studentId;
    }
//    else if('list-remove'==action) {
//        $.ajax({
//            type: 'POST',
//            dataType: 'json',
//            url: '/student/group-remove/collection/'+$('#collection').val()+'/id/'+studentId,
////            url: '/student/group-remove/id/'+studentId,
//            success: function(json) {
//                refreshCollectionList('groupContainer');
//            },
//            error: function(json) {
//                console.debug('error in linkAction', studentId, '/student/group-remove/collection/'+$('#collection').val()+'/id/'+studentId);
//            }
//        });
//    }
    return false;
}
function groupAction(groupAction) {
//    console.debug('groupAction', groupAction);
    if('edit'==groupAction) {
    } else if('print'==groupAction) {
        groupPrint(); // defined below
    } else if('transfer'==groupAction) {
        groupTransfer(); // defined below
    } else if('assign-team-member'==groupAction) {
        assignTeamMember();
    } else if('assign-case-manager'==groupAction) {
        assignCaseManager();
    }
}
function waitForJobCompletion(jobId, fileName) {
//    console.debug('waitForJobCompletion', jobId, fileName);
    $.ajax({
        type:'POST',
        dataType: 'json',
        url:'/student/get-job-status/id/'+jobId,
        success: function (json) {
            if(2==json.status) {
                setTimeout(function() {
                    waitForJobCompletion(jobId, fileName);
                }, 1000);
            } else if(3==json.status) {
                $('#printJobs').html('<li id="refresh_job_'+json.job+'" ><a id="download_job'+jobId+'" href="">Open Print File</a></li>');
//                $('#refresh_job_'+jobId).html('<a id="download_job'+jobId+'" href="">Ready for download.</a>');
                $('#download_job'+jobId).click(function(element) {
                    window.location = '/student/get-job-document/id/'+jobId+'/fileName/'+fileName;
                    return false;
                });
            }
        }
    });
}

/**
 * add/remove to/from collection checkbox
 * called when checkbox is clicked by the user
 * @param checkboxElement
 */
function clickCheckbox(checkboxElement) {
    console.debug('checkboxElement123', checkboxElement);
    if(checkboxElement.checked) {
        var studentId = getStudentIdFromStudentOptionsList($(checkboxElement).closest('ul').find('.studentOptions'));
        var url = '/student/group-add/id/'+studentId+'/collection/'+$('#collection').val();
    } else {
        var studentId = $.trim($(checkboxElement).closest('ul').find('.searchId').text());
        var url = '/student/group-remove/id/'+studentId+'/collection/'+$('#collection').val();
    }
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: url,
        success: function(json) {
            refreshCollectionList('groupContainer');
        }
    });
}

/**
 * add/remove to/from collection progrmaticallly
 * called from the check/uncheck all calls
 * @param checkboxElement
 */
function clickCheckboxNoCallback(checkboxElement) {
    if(checkboxElement.checked) {
        var studentId = getStudentIdFromStudentOptionsList($(checkboxElement).closest('ul').find('.studentOptions'));
        var url = '/student/group-add/id/'+studentId+'/collection/'+$('#collection').val();
    } else {
        var studentId = $.trim($(checkboxElement).closest('ul').find('.searchId').text());
        var url = '/student/group-remove/id/'+studentId+'/collection/'+$('#collection').val();
    }
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: url,
        success: function(json) {
            // nothing
        }
    });
}

function groupPrint() {
    $('#groupPrint').dialog({
        title:'Batch Printing Options',
        modal:true,
        width:400,
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function() {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Print",
                "class": 'jqueryUiButton',
                click: function() {
                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        url:'/student/do-group/collection/'+$('#collection').val()+'/run/print/printType/'+$('#printType').val()+'/formNum/'+$('#formNum').val(),
                        success: function(json) {
        
                        //	console.debug('json', json.success, json.errorMessage);
                            if(1==json.success && false!=json.job) {
                                $('#printJobs').html('<li id="refresh_job_'+json.job+'" >waiting for print jobs...</li>');
                                waitForJobCompletion(json.job, json.fileName);
                            } else{
                                console.debug('failed to add the job to the queue.');
                                alert('Error:'+json.errorMessage);
                            }

                        },
                        error: function(json) {
                            console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/'+$('#printType').val()+'/formNum/'+$('#formNum').val());
                        }
                    });
                    $(this).dialog("close");
                }
            }
        ]
    });
}
function groupTransfer() {
    $('#groupTransfer').dialog({
        title:'Batch Transfer Options',
        modal:true,
        width:400,
        open: function() {
            $('#id_county').trigger('change');
        },
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function() {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Transfer",
                "class": 'jqueryUiButton',
                click: function() {
                    /**
                     * be sure all elements are filled out
                     */

                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        url:'/student/do-group/collection/'+$('#collection').val()+'/run/transfer/county/'+
                            $('#id_county').val()+'/district/'+$('#id_district').val()+'/school/'+
                            $('#id_school').val()+'/autoMoveForAsmOrBetter/'+$('#autoMoveForAsmOrBetter').is(':checked'),
                        success: function(json) {
//                            console.debug('json', json.success, json.errorMessage);
                            if(1==json.success && false!=json.job) {
                                if(''!=json.message) {
                                    $.growlUI('Success', json.message, 10000);
                                } else {
                                    $.growlUI('Success', 'Student transfers initiated.', 10000);
                                }
                            } else{
                                console.debug('An error occured in the transfer.');
                                $.growlUI('Error', json.errorMessage, 2000);
                            }

                        },
                        error: function(json) {
                            console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/'+$('#printType').val()+'/formNum/'+$('#formNum').val());
                        }
                    });
                    $(this).dialog("close");
                }
            }
        ],
    });
}

function assignCaseManager() {
    var areaMain = $('#assignCaseManager>div.areaMain');
    var areaMessage = $('#assignCaseManager>div.areaFetchingMsg');
    var submitButton = $('#assignCaseManager').next('submitButton');

    $('#assignCaseManager').dialog({
        title: 'Assign Case Manager',
        modal: true,
        width: 400,
        open: function() {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/api-student/assign-case-manager/collection/'+$('#collection').val(),
                success: function (json) {
                    if(1 == json.success) {
                        /**
                         * hide message
                         */
                        areaMessage.hide();
                        var caseManagers = json.data.case_managers;

                        /**
                         * enable submit button
                         */
                        $('#assignCaseManager').parent().find('button.submitButton').removeAttr('disabled').removeClass( 'ui-state-disabled' );

                        /**
                         * update select
                         */
                        var options = '';
                        var optionsPrefix = '<option value="">Choose a case manager</option>';
                        var size = 0;
                        var selected = '';
                        var selectionMade = false;
                        $.each(caseManagers, function(optionValue, optionDisplay) {
                            options += '<option value="' + optionValue + '">' + optionDisplay + '</option>';
                            size += 1;
                        });
                        options = optionsPrefix+options;
                        areaMain.find('select.caseMgr').html(options);


                        $('#assignCaseManager>div.areaFetchingMsg').hide();
                        $('#areaMain>div.areaFetchingMsg').hide();
                    } else {
                        areaMessage.html(json.errorMessage);
                        areaMessage.show();
                    }
                },
                error: function (json) {
                    console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/' + $('#printType').val() + '/formNum/' + $('#formNum').val());
                    areaMessage.html(json.errorMessage);
                    areaMessage.show();
                }
            });

        },
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function () {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Assign Selected Case Manager",
                "class": 'jqueryUiButton submitButton',
                disabled:"disabled",
                click: function () {
                    var areaMain = $('#assignCaseManager>div.areaMain');

                    /**
                     * be sure all elements are filled out
                     */
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: '/api-student/assign-case-manager/collection/'+$('#collection').val() +
                            '/id_case_mgr/' + areaMain.find('select.caseMgr').val(),
                        success: function (json) {
                            if (1 == json.success) {
                                if ('' != json.message) {
                                    $.growlUI('Success', json.message, 10000);
                                } else {
                                    $.growlUI('Success', 'Student transfers initiated.', 10000);
                                }
                            } else {
                                console.debug('An error occured in the transfer.');
                                $.growlUI('Error', json.errorMessage, 2000);
                            }
                        },
                        error: function (json) {
                            console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/' + $('#printType').val() + '/formNum/' + $('#formNum').val());
                        }
                    });
                    $(this).dialog("close");
                }
            }
        ]
    });
}
function assignTeamMember(studentId) {

    // init
    var areaMain = $('#assignTeamMember>div.areaMain');
    var areaMessage = $('#assignTeamMember>div.areaFetchingMsg');
    var submitButton = $('#assignTeamMember').next('submitButton');
    areaMain.hide();
    areaMessage.show();

    $('#assignTeamMember').dialog({
        title: 'Assign Team Member',
        modal: true,
        width: 400,
        open: function() {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: '/api-student/assign-team-member/collection/'+$('#collection').val(),
                success: function (json) {
                    /**
                     * hide message
                     */
                    areaMessage.hide();

                    if(1 == json.success) {

                        var student = json.data.students;
                        var possibleTeamMembers = json.data.possibleStudentTeamMembers;

                        if(0 == possibleTeamMembers.length) {
                            // nothing to choose
                            areaMessage.html('There are no personnel with privileges to be a team member for this student.');
                            areaMessage.show();
                        } else {
                            /**
                             * update display
                             */
//                            areaMain.find('span.studentName').html('<b>'+student.name_first + ' ' + student.name_last+'</b>');

                            /**
                             * enable submit button
                             */
                            $('#assignTeamMember').parent().find('button.submitButton').removeAttr('disabled').removeClass( 'ui-state-disabled' );
                            /**
                             * update select
                             */
                            var options = '';
                            var optionsPrefix = '<option value="">Choose a team member</option>';
                            var size = 0;
                            var selected = '';
                            var selectionMade = false;
                            $.each(possibleTeamMembers, function(optionValue, optionDisplay) {
                                options += '<option value="' + optionValue + '"' + selected + '>' + optionDisplay + '</option>';
                                size += 1;
                            });
                            if(!selectionMade) {
                                options = optionsPrefix+options;
                            }

                            /**
                             * inject params into dialog and show
                             */
                            areaMain.find('select.teamMember').html(options);
                            areaMain.find('input.studentId').attr('value', studentId);
                            areaMessage.hide();
                            areaMain.show();

                        }

                    } else {
                        areaMessage.html(json.errorMessage);
                        areaMessage.show();
                    }
                },
                error: function (json) {
                    console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/' + $('#printType').val() + '/formNum/' + $('#formNum').val());
                }
            });

        },
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function () {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Assign as Team Member",
                disabled:"disabled",
                "class": 'jqueryUiButton submitButton',
                click: function () {
                    var areaMain = $('#assignTeamMember>div.areaMain');
                    if(''==areaMain.find('select.teamMember').val() || ''==areaMain.find('select.role').val()) {
                        $.growlUI('Error', 'You must select a team member and a role', 2000);
                        return false;
                    }
                    var confirm = areaMain.find('input.confirm').attr("checked") ? 1 : 0;
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: '/api-student/assign-team-member/collection/' +$('#collection').val() +
                            '/id_team_member/' + areaMain.find('select.teamMember').val() +
                            '/confirm/' + confirm +
                            '/role/' + areaMain.find('select.role').val(),
                        success: function (json) {
                            areaMain.find('div.areaConfirm').hide();
                            areaMain.find('input.confirm').attr("checked", false);
                            if (1 == json.success) {
                                $('#assignTeamMember').dialog("close");
                                if ('' != json.message) {
                                    $.growlUI('Success', json.message, 10000);
                                } else {
                                    $.growlUI('Success', 'Student transfers initiated.', 10000);
                                }

                            } else {
                                console.debug('An error occured in the transfer.');
                                if('This user already exists' == json.errorMessage.substr(0, 'This user already exists'.length)) {
                                    areaMessage.html("<b>"+json.errorMessage+"</b>");
                                    areaMain.find('div.areaConfirm').show();
                                    areaMain.find('input.confirm').val(1);
                                }
                                areaMessage.show();
                            }
                        },
                        error: function (json) {
                            console.debug('error in groupAction', groupAction, '/student/do-group/run/print/printType/' + $('#printType').val() + '/formNum/' + $('#formNum').val());
                        }
                    });

                }
            }
        ],
    });
}

function listConditionalDisplayLogic(selectValue) {
//    console.debug('listConditionalDisplayLogic', selectValue);
    /**
     * conditional list display elements
     */
    if('assign-case-manager' == selectValue) {
        $('.showon-assign-case-manager').show();
    } else {
        $('.showon-assign-case-manager').hide();
    }

    if('assign-team-member' == selectValue) {
        $('.showon-assign-team-members').show();
    } else {
        $('.showon-assign-team-members').hide();
    }

}

function getMyCollections(selectedCollectionName) {
    var selectedCollectionName = undefined == selectedCollectionName ? null : selectedCollectionName;
//    console.debug('getMyCollections', selectedCollectionName);
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '/student/get-my-collections',
        success: function (json) {
            if(1 == json.success) {
                var collections = json.data.collections;
//                console.debug(collections);
                /**
                 * update select
                 */
                var options = '';
                var optionsPrefix = '';
                var size = 0;

                if(null == selectedCollectionName && null != $('select#collection').val()) {
                    selectedCollectionName = $('select#collection').val();
                }

                /**
                 * clear list
                 */
//                $('ul#collectionListContainer').html('');

                var selectionMade = false;
                $.each(collections, function(index, row) {
                    /**
                     * update select list
                     */
                    if((0 == size && false == selectedCollectionName) || selectedCollectionName == row.name) {
                        var selectedTxt = ' selected="selected" ';
                    } else {
                        var selectedTxt = '';
                    }

                    options += '<option value="' + row.name + '" '+selectedTxt+'>' + row.name + '</option>';
                    size += 1;

                    /**
                     * update management list
                     */
//                    var addHtml = '<li>' +
//                        '<div style="width:100%;">' +
//                        '<span style="display:inline;">'+row.name+'</span>' +
//                        '<span style="float:right;display:inline;width:15px;"><a href="#" data-collection-id="'+row.id_collection+'" class="groupCollectionManageDelete">X</a></span>' +
//                        '</div></li>'
//                    $('ul#collectionListContainer').append(addHtml);
                });
                options = optionsPrefix+options;
                $('select#collection').html(options);

                if(0 == collections.length) {
                    $('.collectionOperations').hide();
                } else {
                    $('.collectionOperations').show();
                }
                refreshCollectionList('groupContainer');

            }
        },
        error: function (json) {
            console.debug('error', json.errorMsg);
        }
    });

}

function addCollection() {
    $('div.addCollectionDialog').find('.collection_overwrite').prop('checked', false);

    $('div.addCollectionDialog').dialog({
        title:'Create Collection',
        modal:true,
        width:400,
        buttons: [
            {
                text: "Cancel",
                "class": 'jqueryUiButton',
                click: function() {
                    // Cancel code here
                    $(this).dialog("close");
                }
            },
            {
                text: "Create",
                "class": 'jqueryUiButton',
                click: function() {
                    $('div.addCollectionDialog').find('.collectionMessage').hide();

                    var overwrite = $(this).find('.collection_overwrite').is(':checked') ? 1 : 0;
                    var collectionName = $(this).find('.collection_name').val();
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/student/add-collection/collection/'+collectionName+'/overwrite/'+overwrite,
                        success: function (json) {
                            if(1 == json.success) {
                                getMyCollections(collectionName);
//                                refreshCollectionList('groupContainer');
                                $('div.addCollectionDialog').dialog("close");
                            } else {
                                $('div.addCollectionDialog').find('.collectionMessage').html(json.errorMessage);
                                $('div.addCollectionDialog').find('.collectionMessage').show();
                            }
                        },
                        error: function (json) {
                            $('div.addCollectionDialog').find('.collectionMessage').html(json.errorMessage);
                            $('div.addCollectionDialog').find('.collectionMessage').show();
                        }
                    });
                }
            }
        ]
    });
    return false;
}