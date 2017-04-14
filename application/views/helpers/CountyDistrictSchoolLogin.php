<?php
/**
 */
class Zend_View_Helper_CountyDistrictSchoolLogin extends Zend_View_Helper_Abstract
{

    /**
     *
     * @return string
     */
    public function countyDistrictSchoolLogin($idCounty = null, $idDistrict = null, $idSchool = null)
    {
        /*
         * build county select element
		*/
        $county = new App_Form_Element_Select('id_county', array('label' => 'County:'));
        $county->setAttrib('onchange', '');
        if (strlen($idCounty) < 2) {
            $county->addMultiOption('', 'Choose a county...');
            $county->addMultiOptions(Model_Table_County::countyMultiOtions());
        } else {
            $county->setValue($idCounty);
            $county->setMultiOptions(Model_Table_County::countyMultiOtions());
        }

        /*
         * build district select element
		*/
        $district = new App_Form_Element_Select('id_district', array('label' => 'District:'));
        $district->setAttrib('onchange', '');
        $district->addMultiOption('', 'Waiting for county...');
        $district->setValue($idDistrict);
        if (is_null($idCounty)) {
//            $district->setMultiOptions(Model_Table_District::districtMultiOptionsAll());
        } else {
            $district->setMultiOptions(Model_Table_District::districtMultiOtions($idCounty));
        }


        /*
         * build school select element
		*/
        $school = new App_Form_Element_Select('id_school', array('label' => 'School:'));
        $school->setAttrib('onchange', '');
        $school->addMultiOption('', 'Waiting for district...');
        if (strlen($idCounty) < 2 || strlen($idDistrict) < 2) {
            $school->addMultiOption('', 'Choose a district...');
        } else {
            $school->setValue($idSchool);
            $school->setMultiOptions(Model_Table_School::schoolMultiOtions($idCounty, $idDistrict));
        }


        /*
         * build the script call and button to be
        */
        $this->view->placeholder('countyDistrictSchool')->captureStart();
        ?>
    <script type="text/javascript">
        /**
         * array of district info indexed by id_county-id_district
         */
        var countyDistrictArr = <?= Zend_Json::encode(Model_Table_District::allCountyDistrictArray())?>;

        function buildDistricts(districtId) {
            if ($('#id_county').val().length > 0) {
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url:'/county/get-district-multi-options' + '/id_county/' + $('#id_county').val(),
                    success:function (json) {
                        var options = '';
                        var optionsPrefix = '<option value="">Choose a district</option>';
                        var size = 0;
                        $.each(json['items'][0]['options'], function (optionValue, optionDisplay) {
                            options += '<option value="' + optionValue + '"';
                            if (optionValue == districtId) {
                                options += ' selected="selected"';
                            }
                            options += '>' + optionDisplay + '</option>';
                            size += 1;
                        });
                        if (size > 1) {
                            options = optionsPrefix + options;
                        }
                        // triggerHandler causes onChange to fire on district
                        $("#id_district").html(options).triggerHandler("change");
                    }
                });	// end ajax call
            } else {
                /**
                 * county is nulll
                 * build a list of all districts
                 */
                var options = '';
                var optionsPrefix = '<option value="">Choose a district</option>';
                var size = 0;
                $.each(countyDistrictArr, function (key, array) {
                    options += '<option value="' + key + '">' + array.name_district + '</option>';
                    size += 1;
                });
                if (size > 1) {
                    options = optionsPrefix + options;
                }
                // triggerHandler causes onChange to fire on district
                $("#id_district").html(options).triggerHandler("change");
                ;

            }
        }
        function buildSchools(selectedSchoolsArr, disableRemove) {
            /**
             * odd functionality
             * id_district might also contain the county id
             */
            if ($('#id_district').val().length == 7) {
                // county and district
                var newIdCounty = $('#id_district').val().substring(0, 2);
                var newIdDistrict = $('#id_district').val().substring(3, 7);
                $('#id_district').val('');
                $('#id_county').val(newIdCounty);
                buildDistricts(newIdDistrict);
                return;
            }

            if($('#user_type').val()<=3 && $('#id_district').val()!='') {
                console.debug('buildSchools');
                showNextIfCheckboxesChecked(true);
                return false;
            }

            if ($('#id_district').val().length > 0) {
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    url:'/county/get-schools' + '/id_county/' + $('#id_county').val() + '/id_district/' + $('#id_district').val(),
                    success:function (json) {
                        var rowData = '';
                        $.each(json['items'][0]['schools'], function (index, schoolData) {
                            var cds = schoolData.id_county + '-' + schoolData.id_district + '-' + schoolData.id_school;
                            rowData += '<tr>';
                            rowData += '<td>';
                            rowData += '<input type="checkbox" ';
                            if (undefined != selectedSchoolsArr && -1 != selectedSchoolsArr.indexOf(cds)) {
                                rowData += 'checked="checked" ';
                            }
                            rowData += 'value="' + cds + '" name="schools[]" id="' + cds + '" />';
                            rowData += '</td>';
                            rowData += '<td>' + schoolData.name_school + '</td>';
                            rowData += '<td>' + schoolData.name_first + ' ' + schoolData.name_last + '</td>';
                            rowData += '<td>' + schoolData.phone_work + '</td>';
                            rowData += '</tr>\n';
                        });
//	        			// triggerHandler causes onChange to fire on school
                        $("#schools_display").html('<tr><td></td><td>School Name</td><td>School Contact</td><td>Phone Number</td></tr>' + rowData);
                        console.debug('ajax');
                        showNextIfCheckboxesChecked();

                        if (true == disableRemove) {
                            /**
                             * disable checked schools
                             */
                            $('input[type=checkbox]').attr('disabled', true);
                            $('input[type=checkbox]:not(:checked)').closest('tr').remove();
                        }
                    }
                });	// end ajax call
            } else {
                var options = '<option value="">Choose a district</option>';
                $("#id_school").html(options);
            }
        }
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
        function showNextIfCheckboxesChecked(forceShow) {
            console.debug('showNextIfCheckboxesChecked', forceShow);
            if (true != forceShow && 0 == $("#schools_display tbody tr input[type=checkbox]").filter(':checked').length) {
                $('#submit').hide();
            } else {
                $('#submit').show();
            }
        }
        $(document).ready(function () {

            $('#id_county').change(function () {
                buildDistricts();
            });
            $('#id_district').change(function () {
                buildSchools();
            });
            if ('' == $('#id_county').val()) {
                buildDistricts();
            }

            /**
             * attach next button to checkbox count
             * user_type 4+
             */
            $('#schools_display').on("click", "tbody tr input[type=checkbox]", function () {
                showNextIfCheckboxesChecked();
            });
            console.debug('init');

            if($('#id_district').val()!='') {
                showNextIfCheckboxesChecked($('#accountRequestDetails').length == 1);
            }
        });
    </script>
    <div>
        <?php echo $county; ?>
    </div>
    <div>
        <?php echo $district; ?>
    </div>
    <?php
        $this->view->placeholder('countyDistrictSchool')->captureEnd();

        return $this->view->placeholder('countyDistrictSchool');
    }

    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

}
