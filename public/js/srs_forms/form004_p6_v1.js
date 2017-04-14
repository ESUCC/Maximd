function primaryServiceTpdUnitMinPerDayHelperInit() {
    var storedPrimaryServiceTpdUnitStandard = ["w", "m", "q", "s", "y"];
    var storedPrimaryServiceTpdUnitMinPerWeek = ["wm", "ws", "wy"];

    function extracted(primaryServuceTpdValue) {
        $.each($("#primary_service_days_unit option"), function (index, optionElement) {
            $(optionElement).removeAttr('disabled');
        });
        $.each($("#primary_service_days_unit option"), function (index, optionElement) {
            if('mw' == primaryServuceTpdValue) {
                if(-1 != $.inArray($(optionElement).val(), storedPrimaryServiceTpdUnitStandard)) {
                    $(optionElement).attr('disabled', true);
                    if($(optionElement).val() == $('#primary_service_days_unit').val()) {
                        $('#primary_service_days_unit').val($("#primary_service_days_unit option:not([disabled])").first().val());
                        $("#primary_service_days_unit option:not([disabled])").first().attr("selected", "selected");
                    }
                }
            } else {
                if(-1 != $.inArray($(optionElement).val(), storedPrimaryServiceTpdUnitMinPerWeek)) {
                    $(optionElement).attr('disabled', true);
                    if($(optionElement).val() == $('#primary_service_days_unit').val()) {
                        $('#primary_service_days_unit').val($("#primary_service_days_unit option:not([disabled])").first().val());
                        $("#primary_service_days_unit option:not([disabled])").first().attr("selected", "selected");

                    }
                }
            }
        });
    }

    $('#primary_service_tpd_unit').on('change', function(event) {
        var primaryServuceTpdValue = $(event.target).val();
        extracted(primaryServuceTpdValue);
    });
    extracted($('#primary_service_tpd_unit').val());
}
function relatedServiceTpdUnitMinPerDayHelperInit() {
    var storedRelatedServiceTpdUnitStandard = ["w", "m", "q", "s", "y"];
    var storedRelatedServiceTpdUnitMinPerWeek = ["wm", "ws", "wy"];

    function extracted(relatedServuceTpdValue, element) {
        $.each($(element).closest('tr').find('.related_service_days_unit option'), function (index, optionElement) {
            $(optionElement).removeAttr('disabled');
        });
        $.each($(element).closest('tr').find('.related_service_days_unit option'), function (index, optionElement) {
            if('mw' == relatedServuceTpdValue) {
                if(-1 != $.inArray($(optionElement).val(), storedRelatedServiceTpdUnitStandard)) {
                    $(optionElement).attr('disabled', true);
                    if($(optionElement).val() == $(element).closest('tr').find('.related_service_days_unit').val()) {
                        $(element).closest('tr').find('.related_service_days_unit').val($(element).closest('tr').find('.related_service_days_unit option:not([disabled])').first().val());
                        $(element).closest('tr').find('.related_service_days_unit option:not([disabled])').first().attr("selected", "selected");

                    }
                }
            } else {
                if(-1 != $.inArray($(optionElement).val(), storedRelatedServiceTpdUnitMinPerWeek)) {
                    $(optionElement).attr('disabled', true);
                    if($(optionElement).val() == $(element).closest('tr').find('.related_service_days_unit').val()) {
                        $(element).closest('tr').find('.related_service_days_unit').val($(element).closest('tr').find('.related_service_days_unit option:not([disabled])').first().val());
                        $(element).closest('tr').find('.related_service_days_unit option:not([disabled])').first().attr("selected", "selected");

                    }
                }
            }
        });
    }

    $('.related_service_tpd_unit').on('change', function(event) {
        var relatedServuceTpdValue = $(event.target).val();
        extracted(relatedServuceTpdValue, $(event.target));
    });
    $.each($('.related_service_tpd_unit'), function(index, element) {
        extracted($(element).val(), element);
    });
}
function enableDisableAccomodationsChecklist(value) {
    console.debug('enableDisableAccomodationsChecklist');
    if(value == 1) {
        showHideAnimation('accomodations_checklist_container', 'show');
        /**
         * enable chosen multi selects
         */
        $('.chosenMulti').chosen();
    } else {
        showHideAnimation('accomodations_checklist_container', 'hide');
    }
}

function otherHelper(menuValue, value) {
    try {
        currentVal = dijit.byId('accomodations_checklist_1-other').getValue();
        if('q' == menuValue) {
            if('' == currentVal || '<br />' == currentVal || '<br _moz_editor_bogus_node="TRUE" />' == currentVal) {
                dijit.byId('accomodations_checklist_1-other').setValue(value);
            } else {
                dijit.byId('accomodations_checklist_1-other').setValue(currentVal + "<BR />" + value);
            }
        }
    } catch (error) {
        console.debug('javscript error in otherHelper');
    }

}

function toggleShowHideMips() {
    try {
        showMips = false;
        mipsId = "showHideMips";

        // primary disability
        disability = dojo.byId('primary_disability_drop');
        if(disability.value == 'Occupational Therapy Services' ||
            disability.value == 'Physical Therapy' ||
            disability.value == 'Speech-language therapy') {
            showMips = true;
        }

        // related disability
        dojo.query("#related_service_drop-colorme select").forEach(
            function(selectTag) {
                if(getNodeIdEndsWith(selectTag, 'related_service_drop'))
                {
                    if(selectTag.value == 'Occupational Therapy Services' ||
                        selectTag.value == 'Physical Therapy' ||
                        selectTag.value == 'Speech-language therapy')
                    {
                        showMips = true;
                    }
                }
            }
        );
        if(showMips) {
            showHideAnimation(mipsId, 'show');
        } else {
            showHideAnimation(mipsId, 'hide');
        }
    } catch (error) {
        console.debug('javscript error in toggleShowHideMips');
    }

}

function override(id, checkedValue)
{
    if(checkedValue)
    {
        var answer = confirm("Checking Not Required will permanently clear out all data in that section. Do you wish to proceed?")
        if (answer){
            //console.debug('override', id, checkedValue);
            dash = id.indexOf('-');
            subformName = id.substring(0, dash);
            dojo.query('input[id^="'+subformName+'"][type=checkbox][id$="remove_row"]').forEach(
                function(selectTag) {
                    console.debug('found', selectTag);
                    selectTag.checked = true;
                }
            );
            dojo.byId(subformName+'_parent').style.display = 'none';
        }
    }
}
function calculateAndSetQualifingMinutesPrimary()
{
    // primary_service_tpd
    // primary_service_tpd_unit

    // primary_service_days_value
    // primary_service_days_unit
    console.debug($('#primary_service_tpd').val(), $('#primary_service_tpd_unit').val());

    var x = $('#primary_service_tpd').val();
    var y = $('#primary_service_days_value').val();

    var minPerDay = 0;
    if('m' == $('#primary_service_tpd_unit').val()) {
        minPerDay =  x * y;
    } else if('h' == $('#primary_service_tpd_unit').val()) {
        minPerDay =  (x/60) * y;
    } else if('mw' == $('#primary_service_tpd_unit').val()) {
        minPerDay = (x/5) * y;
    }

    var daysValue = $('#primary_service_days_unit').val();
    var total = 0;
    if('w' == daysValue) {
        // days per week
        total = minPerDay;
    } else if('m' == daysValue) {
        // days per month
        total = minPerDay/4;
    } else if('q' == daysValue) {
        // days per quarter
        total = minPerDay/9;
    } else if('s' == daysValue) {
        // days per semester
        total = minPerDay/18;
    } else if('y' == daysValue) {
        // days per year
        total = minPerDay/36;
    } else if('wm' == daysValue) {
        // weeks per month
        total = minPerDay/4;
    } else if('ws' == daysValue) {
        // weeks per semester
        total = minPerDay/18;
    } else if('wy' == daysValue) {
        // weeks per year
        total = minPerDay/36;
    }
    console.debug(x, y, minPerDay, daysValue, total);
    $('#fte_qualifying_minutes').val(total);
}
function fteHelperInit()
{
    console.debug('fteHelperInit', dojo.byId('primary_service_tpd'));
    $('#primary_service_tpd').on('change', function(event) {
        console.debug(event, 'jhere');
//        $('#fte_qualifying_minutes').val(calculateQualifingMinutes());
    });
    $( "#primary_service_days_value" ).on( "change", function() {
        console.debug('asfd');
//        $('#fte_qualifying_minutes').val(calculateQualifingMinutes());
    });
}

function fteHelper(returnedData) {
    try {
        $('#fte_minutes_per_week').text(returnedData.fte_minutes_per_week);
        $('#fte_total_qualifying_min_re').text(returnedData.fte_total_qualifying_min_re);
        $('#fte_total_qualifying_min_se').text(returnedData.fte_total_qualifying_min_se);
        $('#gen_ed').text(returnedData.fte_minutes_per_week - (returnedData.fte_total_qualifying_min_re + returnedData.fte_total_qualifying_min_se));
        var ftePercentBase = (returnedData.fte_total_qualifying_min_re + returnedData.fte_total_qualifying_min_se) / returnedData.fte_minutes_per_week;
        var ftePercent = Math.round(ftePercentBase * 100)/100;
        $('#fte_percent').text(ftePercent);
    } catch (error) {
        console.debug('javscript error in fteHelper');
    }
}

function pageReload(returneditems)
{
    fteHelper(returneditems[0]);
}

function toggleShowExpandedOptions() {

	showDA = false;
	showHideId = "showExpanedOptions";
    showHideDefaultId = "showPrimaryDisabilityText";

    try {
		// primary disability
        if($('#expanded_options').is(":checked")) {
            showDA = true;
        }
		if(showDA) {
	        showHideAnimation(showHideDefaultId, 'hide');
			showHideAnimation(showHideId, 'show');
		} else {
	        showHideAnimation(showHideDefaultId, 'show');
			showHideAnimation(showHideId, 'hide');
		}
    } catch (error) {
    	//execute this block if error
    	console.debug('Error in toggleExpandedOptions.');
    }

}

/*
 * on load
 */
$().ready(function() {
    toggleShowHideMips();
    setDate2WhenDate1ChangesByClass('.from_date', '.to_date', $('#effect_to_date').val());

    primaryServiceTpdUnitMinPerDayHelperInit();
    relatedServiceTpdUnitMinPerDayHelperInit();
    fteHelperInit();

    /**
     * enable chosen multi selects
     */
    $('.chosenMulti:visible').chosen();
    
    toggleShowExpandedOptions()

});