<?php
/**
 */
class Zend_View_Helper_CountyDistrictSchoolNoElements extends Zend_View_Helper_Abstract
{
    /**
     * 
     * @return string
     */
    public function countyDistrictSchoolNoElements()
    {
        /*
         * build the script call and button to be
        */
        $this->view->placeholder('countyDistrictSchool')->captureStart();
        ?>
    <script type="text/javascript">
        function buildDistricts() {
            if($('#id_county').val().length>0) {
                $('#id_district').add('#id_school').closest('.wrapperDiv').slideUp();
                $('#aap_body .message').first().html('Fetching data...');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '/county/get-district-multi-options'+'/id_county/' + $('#id_county').val()+'/limit2privs/true',
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
                        // triggerHandler causes onChange to fire on district
                        $("#id_district").html(options).triggerHandler("change");
                        $('#id_district').add('#id_school').closest('.wrapperDiv').slideDown();
                        $('#aap_body .message').first().html('');
                    }
                });	// end ajax call
            }
        }
        function buildSchools() {

            if($('#id_district').val().length>0) {
                $('#id_school').closest('.wrapperDiv').slideUp();
                $('#aap_body .message').first().html('Fetching data...');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '/county/get-school-multi-options'+'/id_county/'+$('#id_county').val()+'/id_district/'+$('#id_district').val()+'/limit2privs/true',
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
                        $('#id_school').closest('.wrapperDiv').slideDown();
                        $('#aap_body .message').first().html('');
                    }
                });	// end ajax call
            } else {
                var options = '<option value="">Choose a school</option>';
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
        $(document).ready(function(){
            console.debug('ready');
            $('#id_county').change(function(){
                buildDistricts();
            });
            $('#id_district').change(function(){
                buildSchools();
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
