<?php
/**
 */
class Zend_View_Helper_NonPublicCdsHelper extends Zend_View_Helper_Abstract
{
    /**
     *
     * @return string
     */
    public function nonPublicCdsHelper()
    {
        /*
         * build the script call and button to be
        */
        $this->view->placeholder('countyDistrictSchool')->captureStart();
        ?>
        <script type="text/javascript">
		function buildNonPublicDistricts() {
            console.debug('buildNonPublicDistricts');
			if($('#nonpubcounty').val().length>0) {
				$.ajax({
	        		type: 'POST',
	        		dataType: 'json',
	        		url: '/county/get-non-public-district-multi-options'+'/nonpubcounty/' + $('#nonpubcounty').val(),
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
                        $("#nonpubdistrict").html(options);
                        // triggerHandler causes onChange to fire on district
                        $("#nonpubdistrict").triggerHandler("change");
	        		}
	        	});	// end ajax call
			}
		}
		function buildNonPublicSchools() {
			if(null != $('#nonpubdistrict').val() && $('#nonpubdistrict').val().length>0) {
	        	$.ajax({
	        		type: 'POST',
	        		dataType: 'json',
	        		url: '/county/get-non-public-school-multi-options'+'/nonpubcounty/'+$('#nonpubcounty').val()+'/nonpubdistrict/'+$('#nonpubdistrict').val(),
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
			        //	$("#nonpubschool").html(options).triggerHandler("change");
	        		}
	        	});	// end ajax call
			} else {
				var options = '<option value="">Choose a district</option>';
				$("#nonpubschool").html(options);
			}
            if('00' == $('#nonpubcounty').val()) {
                $('#nonpubdistrict').closest('div').hide();
                $('#nonpubschool').closest('div').hide();
            }
		}
        $(document).ready(function() {
            $('#nonpubcounty').change(function(){
                console.debug('attach buildNonPublicDistricts');
                buildNonPublicDistricts();
            });
            $('#nonpubdistrict').change(function(){
                buildNonPublicSchools();
            });
        });
        </script>
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
