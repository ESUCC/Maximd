var originalFormatContainerHTML = '';
$(document).ready(function() {

	originalFormatContainerHTML = $('#format-container').html();
	
	$('#search').click(function() { 
		runSearchCall('/student/search-student/page/1/');
	});
	
	$('#showAll').click(function() { 
		runSearchCall('/student/search-student/page/1/showAll/1');
	});
	
	var counter = 0;
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
            addSubmitOnEnter();
            addGradeSelectOptions('#searchField'+(counter-1), '#searchValue'+(counter-1), (counter-1), isGrade);
            addEthnicGroupOptions('#searchField'+(counter-1), '#searchValue'+(counter-1), (counter-1), 'ethnic_group' == $(this).val());
            addPrimaryLanguageOptions('#searchField'+(counter-1), '#searchValue'+(counter-1), (counter-1), 'primary_language' == $(this).val());
            addPrimaryServiceOptions('#searchField'+(counter-1), '#searchValue'+(counter-1), (counter-1), 'primaryOrRelatedService' == $(this).val());
        });
    }

	$('#format').change(function() {
		changeFormat(this);
	});
	
	for (var i=0;i<6;i++) {
		$('#formatColumn'+i).change(function() {
			if (!isNaN($('#format').val())) {
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: '/student/update-column-for-format/format/'+$('#format').val()+'/column/'+$(this).attr('id')+'/columnValue/'+$(this).val(),
				});
			}
		});
	}
	
	// This adds an additional search field on page load.
	$('#searchFieldTemplate-searchFieldTemplate0').trigger('change');
	$('#searchField0').val('name_last');
	
	// This submits the form on enter
	addSubmitOnEnter();
});

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

function changeFormat(currObj) {
	if ($('#format').val() == 'custom') {
		$('#format-container').html($('#add-new-format').html());
		$('#format-container #add-format').click(function() { 
			addNewUserSearchFormat();
			});
		$('#format-container #cancel-add-new-format').click(function() {
			$('#format-container').html(originalFormatContainerHTML);
			$('#format').change(function() {
				changeFormat(this);
			});
			$('#format').trigger('change');
		});
    } else if ($('#format').val() == 'delete') {
        console.debug('delete formats');
        deleteUserSearchFormat();
        return false;
    } else {
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: '/student/get-format-columns/format/'+$(currObj).val(),
			success: function(json) {
				/*
				for (var i=0;i<6;i++) {
					if (json['customFormat'])
						$('#formatColumn'+i).removeAttr('disabled');
					else
						$('#formatColumn'+i).attr('disabled', 'disabled');
				}*/

				for (var i=0;i<6;i++)	
					$('#formatColumn'+i).val(json['formatColumn'+i]);
			}
		});
    }
}

function addNewSearchFormat() {
	$('#format').val('custom');
	$('#format').trigger('change');
}

function addNewUserSearchFormat()
{
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: '/student/add-new-user-search-format/format/'+$('#custom-format').val(),
		success: function(json) {
			$('#column-container').show();
			$('#format-container').html(originalFormatContainerHTML);
			$('#format-container select').append('<option value="'+json['id_format_columns']+'">'+json['format_name']+'</option>');
			$('#format').val(json['id_format_columns']);
			$('#format').trigger('change');
			for (var i=0;i<6;i++) {
					$('#formatColumn'+i).removeAttr('disabled');
					$('#formatColumn'+i).val('');
			}
			$('#update-custom-format-container').show();
			originalFormatContainerHTML = $('#format-container').html();
			$('#format').change(function() {
				changeFormat(this);
			});
		}
	});
}
function removeSearchFormatOption(optionValue)
{
    $('#format-container select>option').each(function (index, item) {
        if('custom' == $(item).attr('value') ||
            'delete' == $(item).attr('value') ||
            'School List' == $(item).attr('value') ||
            'Phonebook' == $(item).attr('value') ||
            'MDT/IEP Report' == $(item).attr('value')
            ) {
        } else if(optionValue == $(item).attr('value')) {
            $(item).remove();
        }
    });
}
function deleteUserSearchFormat()
{
    var content = '<table>';
    $('#format-container select>option').each(function (index, item) {
        if('custom' == $(item).attr('value') ||
            'delete' == $(item).attr('value') ||
            'School List' == $(item).attr('value') ||
            'Phonebook' == $(item).attr('value') ||
            'MDT/IEP Report' == $(item).attr('value')
            ) {
        } else {
            content += '<tr><td style="padding: 5px;"><input class="searchFormatCheckbox" type="checkbox" value="' + $(item).attr('value')  + '" /></td><td>' + $(item).html()  + '</td></tr>';
        }

    });
    content += "</table>"
    $(content).dialog({
//        height:300,
//        width: 500,
        modal: true,
        title: 'Selete Search Formats',
        buttons: {
            Cancel: function(){
                $(this).dialog('close');
            },
            'Delete Selected': function(){
                console.debug('delete selected');
                $(this).dialog('close');
                $(this).find('.searchFormatCheckbox:checkbox:checked').each(function(index, element) {
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data:{id:$(element).val(), submit:1},
                        url: '/student/delete-search-format',
                        success: function(json) {
                            if(json.success) {
                                console.debug('success', $(element).val());
                                removeSearchFormatOption($(element).val())
                            } else {
                                console.debug('fail');
                            }
                        }
                    });
                })
            }
        }

    });

}

function exportToCSV(id) {
	$('#studentSearchForm').attr('action', '/student/export-result-list-to-csv/id/'+id);
    $('#studentSearchForm').submit();
}

function printResults(id) {
    var searchForm = $('#studentSearchForm').serialize();
    window.open('/student/print-results/?id='+id+'&'+searchForm, 'Print_Results', 'width=925,height=600,scrollbars=1');
}

function runSearchCall(url)
{
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: url,
		data: $('#studentSearchForm').serialize(),
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
					if ($(this).val() != '') {
						if ($(this).val().match(/IEP/) == 'IEP' ||
                    				    $(this).val().match(/MDT/) == 'MDT' ||
                    				    $(this).val().match(/IFSP/) == 'IFSP' ||
                    				    $(this).val().match(/Progress Report/) == 'Progress Report') {

							var url = '/student/get-most-recent/'+$(this).val();
							//console.log(url)
							$.ajax({
								type: 'POST',
								dataType: 'json',
								url: url,
								success: function(json) {
									if (json['success'] == '1')
										window.location.href = json['url'];
									else
										alert('The system was unable to locate the most recent form.');
								}
							});
                        } else {
                        	window.location.href = $(this).val();
                        }

					}
				});
			});
			$('.loading').fadeOut();
			
			$('.paginator li a').click(function() {
				runSearchCall($(this).attr('href'), null);
			});

            /**
             * code to update collection checkboxes
             */
            App.collectionManager.showHideStudentListCheckboxes();
            App.matchCheckedStateToList();

        }
	});


}

function changeFormatColumns() {
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: '/student/get-format-columns/format/'+$('#format').val(),
			success: function(json) {
				for (var i=0;i<6;i++)
					$('#formatColumn'+i).val(json['formatColumn'+i]);
			}
		});
}

