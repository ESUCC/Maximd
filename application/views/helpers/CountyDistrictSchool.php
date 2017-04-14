<?php
/**
 */
class Zend_View_Helper_CountyDistrictSchool extends Zend_View_Helper_Abstract
{

    /**
     * 
     * @return string
     */
    public function countyDistrictSchool($idCounty=null, $idDistrict=null, $idSchool=null, $allDistrictsWhenCountyEmpty=false)
    {
        /*
         * build county select element
		*/
    	$county = new App_Form_Element_Select('id_county', array('label' => 'County:'));
        $county->setAttrib('onchange',  '');
        if(strlen($idCounty)<2) {
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
        $district->setAttrib('onchange',  '');
        $district->addMultiOption('', 'Waiting for county...');
        if(strlen($idCounty)<2) {
        	$district->addMultiOption('', 'Choose a county...');
        } else {
        	$district->setValue($idDistrict);
        	$district->setMultiOptions(Model_Table_District::districtMultiOtions($idCounty));
        }
        
        /*
         * build school select element
		*/
        $school = new App_Form_Element_Select('id_school', array('label' => 'School:'));
        $school->setAttrib('onchange',  '');
        $school->addMultiOption('', 'Waiting for district...');
 		if(strlen($idCounty)<2 || strlen($idDistrict)<2 ) {
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
		function buildDistricts() {
			if($('#id_county').val().length>0) {
				$.ajax({
	        		type: 'POST',
	        		dataType: 'json',
	        		url: '/county/get-district-multi-options'+'/id_county/' + $('#id_county').val(),
	        		success: function(json) {
	        			var options = '';
	        			var optionsPrefix = '<option value="">Choose a district</option>';
		        		var size = 0;
	        			$.each(json['items'][0]['options'], function(optionValue, optionDisplay) {
	        				options += '<option value="' + optionValue + '">' + optionDisplay + '</option>';
	        				size += 1;
	        			});
	        			if(size>1) {
	        				options = optionsPrefix+options;
	        			}
                        $("#id_district").html(options);
                        <? if($allDistrictsWhenCountyEmpty) { ?>
                        if(undefined !== afterQueryDistrict) {
                            if('' !== afterQueryDistrict) {
                                $('#id_district').val(afterQueryDistrict);
                            }
                        }
                        <? } // end $allDistrictsWhenCountyEmpty ?>
                        // triggerHandler causes onChange to fire on district
                        $("#id_district").triggerHandler("change");
	        		}
	        	});	// end ajax call
			}
		}	
		function buildSchools() {

			if($('#id_district').val().length>0) {
	        	$.ajax({
	        		type: 'POST',
	        		dataType: 'json',
	        		url: '/county/get-school-multi-options'+'/id_county/'+$('#id_county').val()+'/id_district/'+$('#id_district').val(),
	        		success: function(json) {
	        			var options = '';
	        			var optionsPrefix = '<option value="">Choose a school</option>';
		        		var size = 0;
	        			$.each(json['items'][0]['options'], function(optionValue, optionDisplay) {
	        				options += '<option value="' + optionValue + '">' + optionDisplay + '</option>';
	        				size += 1;
	        			});
	        			if(size>1) {
	        				options = optionsPrefix+options;
	        			}
	        			// triggerHandler causes onChange to fire on school
			        	$("#id_school").html(options).triggerHandler("change");
	        		}
	        	});	// end ajax call
			} else {
				var options = '<option value="">Choose a district</option>';
				$("#id_school").html(options);
			}
			
		}
		/* Get the rows which are currently selected */
		function fnGetSelected( oTableLocal )
		{
			var aReturn = new Array();
			var aTrs = oTableLocal.fnGetNodes();
			for ( var i=0 ; i<aTrs.length ; i++ )
			{
				if ( $(aTrs[i]).hasClass('row_selected') )
				{
					aReturn.push( aTrs[i] );
				}
			}
			return aReturn;
		}
        <? if($allDistrictsWhenCountyEmpty) { ?>
        /**
         * array of district info indexed by id_county-id_district
         */
        var countyDistrictArr = <?= Zend_Json::encode(Model_Table_District::allCountyDistrictArray())?>;
        var afterQueryDistrict = '';
        function updateDistrictSelectWithAllOptions (){
            var options = '';
            var optionsPrefix = '<option value="">Choose a distrct</option>';
            var size = 0;
            $.each(countyDistrictArr, function(countyDistrictKey, countyDistrictValues) {
                options += '<option value="' + countyDistrictKey + '">' + countyDistrictValues.name_district + '</option>';
                size += 1;

            });
            if(size>1) {
                options = optionsPrefix+options;
            }
            // triggerHandler causes onChange to fire on district
            $("#allDistrictsWhenCountyEmptySelect").html(options);//.triggerHandler("change");;
        }
        $(document).ready(function(){
            updateDistrictSelectWithAllOptions();
            $('#allDistrictsWhenCountyEmpty').hide();
            $("#allDistrictsWhenCountyEmptySelect").change(function() {
                $('#allDistrictsWhenCountyEmpty').hide();
                $('#id_district').closest('div').show();

                var keys=this.value.split("-");
                afterQueryDistrict = keys[1]; // set district to be used by buildDistricts
                if(undefined !== keys[0]) {
                    // set county
                    $("#id_county").val(keys[0]);
                }
                $("#id_county").triggerHandler("change");

            });
            /**
             * add change functionality to id_county
             */
            $('#id_county').change(function(){
                if(''==this.value) {
                    /**
                     * show all districts
                     * hide main district selector
                     */
                    $('#allDistrictsWhenCountyEmpty').show();
                    $('#id_district').closest('div').hide();
                } else {
                    /**
                     * restore standard functionality
                     */
                    buildDistricts();
                    $('#allDistrictsWhenCountyEmpty').hide();
                    $('#id_district').closest('div').show();
                }
            });

        });

        <? } // end $allDistrictsWhenCountyEmpty ?>
            $('#id_county').change(function(){
                buildDistricts();
            });        
            $('#id_district').change(function(){
            	buildSchools();
            });
        </script>
        <div>
        	<?php echo $county; ?>
        </div>
        <? if($allDistrictsWhenCountyEmpty) { ?>
        <div id="allDistrictsWhenCountyEmpty">
        	<label>District: </label><select id="allDistrictsWhenCountyEmptySelect"></select>
        </div>
        <? } // end $allDistrictsWhenCountyEmpty ?>
        <div>
        	<?php echo $district; ?>
        </div>
        <div>
        	<?php echo $school; ?>
        </div>
    	<?php
    	$this->view->placeholder('countyDistrictSchool')->captureEnd();
        
    	return $this->view->placeholder('countyDistrictSchool');
    }
    /**
     * Sets the view field
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
    	$this->view = $view;
    }
    
}
