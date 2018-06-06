<?php

class Form_Form023 extends Form_AbstractForm {

	private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

	protected function initialize() {
		parent::initialize();

		$this->id_form_023 = new App_Form_Element_Hidden('id_form_023');
      	$this->id_form_023->ignore = true;

      	$multiOptions = array('testEditor'=>'Existing Editor', 'tinyMce'=>'TinyMce');
      	$this->form_editor_type = new App_Form_Element_Radio('form_editor_type', array('label' => 'Editor Type', 'multiOptions'=>$multiOptions));
      	$this->form_editor_type->setRequired(false);
      	$this->form_editor_type->setAllowEmpty(true);
      	$this->form_editor_type->setAttrib('onchange', $this->form_editor_type->getAttrib('onchange').'setToRefresh();');
      	$this->form_editor_type->getDecorator('label')->setOption('placement', 'prepend');

	}

    public function editMostRecent() {
            return $this->edit_p1_v9();
    }

	public function view_p1_v1() {
		$this->initialize();
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'form023/form023_view_page1_version1.phtml' ) ) ) );
		return $this;
	}

    public function edit_p1_v2() { return $this->edit_p1_v1();}
    public function edit_p1_v3() { return $this->edit_p1_v1();}
    public function edit_p1_v4() { return $this->edit_p1_v1();}
    public function edit_p1_v5() { return $this->edit_p1_v1();}
    public function edit_p1_v6() { return $this->edit_p1_v1();}
    public function edit_p1_v7() { return $this->edit_p1_v1();}
    public function edit_p1_v8() { return $this->edit_p1_v1();}
    public function edit_p1_v9() { return $this->edit_p1_v1();}
	public function edit_p1_v1() {

		$this->initialize();

		//Setting the decorator for the element to a single, ViewScript, decorator,
		//specifying the viewScript as an option, and some extra options:
		$this->setDecorators ( array (array ('ViewScript', array (
										'viewScript' => 'form023/form023_edit_page1_version1.phtml' ) ) ) );

		$this->date_notice = new App_Form_Element_DatePicker('date_notice', array('label' => 'Date of Notice'));
        $this->date_notice->boldLabelPrint();
        $this->date_notice->addErrorMessage("Date of Notice is empty.");

        $this->date_conference = new App_Form_Element_DatePicker('date_conference', array('label' => 'Date of Conference'));
        $this->date_conference->addErrorMessage("Date of Conference is empty.");
		$this->date_conference->setRequired(false);
        $this->date_conference->setAllowEmpty(true);

		$this->service_where = new App_Form_Element_Select('service_where', array('label'=>'Service Where'));
        $this->service_where->removeDecorator('label');
		$this->service_where->setRequired(false);
        $this->service_where->setAllowEmpty(true);
//        $this->service_where->setMultiOptions(App_Form_ValueListHelper::getLocation_version1());

        $this->service_ot = new App_Form_Element_Checkbox('service_ot', array('label'=>'Occupational Therapy'));
		$this->service_ot->getDecorator('label')->setOption('placement', 'append');
        $this->service_ot->addErrorMessage("'None of the above' is checked.");
		$this->service_ot->addValidator(new My_Validate_EmptyIf('service_none', 't'));

        $this->service_pt = new App_Form_Element_Checkbox('service_pt', array('label'=>'Physical Therapy'));
        $this->service_pt->getDecorator('label')->setOption('placement', 'append');
        $this->service_pt->addErrorMessage("'None of the above' is checked.");
		$this->service_pt->addValidator(new My_Validate_EmptyIf('service_none', 't'));

		$this->service_slt = new App_Form_Element_Checkbox('service_slt', array('label'=>'Speech-Language Therapy'));
		$this->service_slt->getDecorator('label')->setOption('placement', 'append');
        $this->service_slt->addErrorMessage("'None of the above' is checked.");
		$this->service_slt->addValidator(new My_Validate_EmptyIf('service_none', 't'));

		$this->service_none = new App_Form_Element_Checkbox('service_none', array('label'=>'None of the above'));
		$this->service_none->getDecorator('label')->setOption('placement', 'append');
		$this->service_none->setRequired(true);
        $this->service_none->setAllowEmpty(false);

        //print("<pre>");var_dump($this->service_slt);die();

		$this->service_ot->addTableFix();
		$this->service_pt->addTableFix();
		$this->service_slt->addTableFix();
		$this->service_none->addTableFix();

        $this->special_ed_non_peer_percent = new App_Form_Element_NumberTextBox('special_ed_non_peer_percent', array('label'=>'Not with regular education peers'));
	    $this->special_ed_non_peer_percent->setAttrib('style', 'width:40px;');
        $this->special_ed_non_peer_percent->removeDecorator('label');
		$this->special_ed_non_peer_percent->setRequired(false);
        $this->special_ed_non_peer_percent->setAllowEmpty(true);

        $this->mode = new App_Form_Element_Hidden('mode');
        $this->mode->ignore = true;

		return $this;
	}

	function getLocation_version1($options)
	{
  /*
   * SRS-242 on June 6 by Mike 
   */
       $diffdate=strtotime(date("Y-m-d"))-strtotime($options['dob']);

       $this->writevar1($diffdate,'this is the diff date');

       $diffdate=(($diffdate/60)/60)/24;
// End add top part SRS-242
		if(!isset($options['dob'])) {
			return false;
		}  else {
			$dob = $options['dob'];
			$lps = isset($options['lps'])?$options['lps']:false;
		}
        if(isset($options['finalized_date']) && isset($options['status']) && 'Final'==$options['status']) {
            $finalized_date = new Zend_Date($options['finalized_date'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        }
		/**
         * Check to see if student is under three years minus one day
         */
        $dob = new Zend_Date($dob, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        $dob = new Zend_Date($dob, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);

        $cutoffDate = new Zend_Date($dob.'+3 years -2 day', Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        $this->writevar1($dob,'the cutoff date');
        if(isset($finalized_date)) { // form is final
            $today = $finalized_date;
        } else {
            $today = new Zend_Date(null, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        }


        // student is under 3 years old
       
       /*This is what the if statement was.  SRS-242 Mike changed 6-6-2018
        * if(-1==$today->compareTimestamp($cutoffDate))
        * 
        */
       
       
       $res=$today->compareTimestamp($cutoffDate);

        if($diffdate<=365 ) $res=-1;

        if (-1 == $res ) {
        	// cutoffDate is earlier than today
			$arrLabel = array(
					'' => 'Choose Location',
					'1' => 'Home',
					'2' => 'Community Based',
					'3' => 'Other',
			);
			Zend_Debug::dump('here');

			return $arrLabel;
        }


        $times = Model_Table_IepStudent::dateDiff ( $dob->toString(), $today->toString() );

        if(!isset($times['years']) && isset($times['year'])) {
	        $times['years'] = $times['year'];
        }
        if(!isset($times['years'])) {
	        return array();
        }
		$years = $times['years'];

		switch($years)
		{
            case 0:
            case 1:
            case 2:
               // $arrLabel = array("Home", "Hospital", "Other Settings (Outpatient facility, clinic)","Program Designed for Children with Developmental Delays or Disabilities", "Program Designed for Typically Developing Children", "Residential Facility", "Service Provider Location (Outpatient facility, clinic)", );
               // $arrValue = array("10", "09", "21","04", "08", "22", "19", );
               $arrLabel = array(  "Choose Location",
                                    "Home",
                                    "Community Based",
                                    "Other",
                                    );
                $arrValue = array(  "",
                                    "1",
                                    "2",
                                    "3",
                                    );
                break;
			case 3:
			case 4:
			case 5:
				if($lps) {
					$arrLabel = array(
						    '' => "Choose Location",
							'00' => 'Public School',
							'5' => 'Separate School',
							'6' => 'Separate Class',
							'7' => 'Residential Facility',
							'8' => 'Home',
							'9' => 'Service Provider Location',
//							'16' => 'Regular Early Childhood Program, 10+ h/wk; Services at EC Program',
//							'17' => 'Regular Early Childhood Program, 10+ h/wk; Services outside EC Program',
//							'18' => 'Regular Early Childhood Program, <10 h/wk; Services at EC Program',
//							'19' => 'Regular Early Childhood Program, <10 h/wk; Services outside EC Program',
					);
				} else {
					$arrLabel = array(
						    '' => "Choose Location",
							'5' => 'Separate School',
							'6' => 'Separate Class',
							'7' => 'Residential Facility',
							'8' => 'Home',
							'9' => 'Service Provider Location',
							'16' => 'Regular Early Childhood Program, 10+ h/wk; Services at EC Program',
							'17' => 'Regular Early Childhood Program, 10+ h/wk; Services outside EC Program',
							'18' => 'Regular Early Childhood Program, <10 h/wk; Services at EC Program',
							'19' => 'Regular Early Childhood Program, <10 h/wk; Services outside EC Program',
					);
				}
                break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
			case 17:
			case 18:
			case 19:
			case 20:
			case 21:
			    $arrLabel = array(
			    			"Choose Location",
			    			"Separate School",
			    			"Residential Facility",
			    			"Public School",
			    			"Homebound/Hospital",
			    			"Private School or Exempt (Home) School",
			    			"Correction/Detention Facility"
			    );
				$arrValue = array(
							"",
							"05",
							"07",
							"10",
							"13",
							"14",
							"15"
				);
			break;
			default:
			    $arrLabel = array(
			    			"Choose Location",
			    			"Public School",
			    			"Public Separate School",
			    			"Public Residential Facility",
			    			"NonPublic School",
			    			"NonPublic Separate School",
			    			"NonPublic Residential Facility",
			    			"Hospital",
			    			"Home"
			    );
			    $arrValue = array(
			    			"",
			    			"01",
			    			"02",
			    			"03",
			    			"05",
			    			"06",
			    			"07",
			    			"09",
			    			"10"
			    );
		}
		if(!isset($arrValue)) return $arrLabel;
		return array_combine($arrValue, $arrLabel);
	}

    function getLocation_version0($age)
    {
        switch($age)
        {
            case 0:
            case 1:
            case 2:
               // $arrLabel = array("Home", "Hospital", "Other Settings (Outpatient facility, clinic)","Program Designed for Children with Developmental Delays or Disabilities", "Program Designed for Typically Developing Children", "Residential Facility", "Service Provider Location (Outpatient facility, clinic)", );
               // $arrValue = array("10", "09", "21","04", "08", "22", "19", );
               $arrLabel = array(  "Choose Location",
                                    "Home",
                                    "Community Based",
                                    "Other",
                                    );
                $arrValue = array(  "",
                                    "1",
                                    "2",
                                    "3",
                                    );
                break;
            case 3:
            case 4:
            case 5:
//                $arrLabel = array("Early Childhood Setting. (e.g., Head Start, child care center, family childcare home)", "Early Childhood Special Education Setting (separate classroom for children with disabilities)", "Part-time childhood/part-time early childhood special education setting", "Home", "Hospital", "Residential Facility", "Separate School", "Public School (Kindergarten, Etc.)");
//                $arrValue = array("11", "12", "13", "10", "09", "22", "23", "15");
/*
                $arrLabel = array(  "Choose Location",
                                    "Regular Early Childhood Program, 10+ h/week; services at EC program",
                                    "Regular Early Childhood Program, 10+ h/week; services outside EC program",
                                    "Regular Early Childhood Program, <10 h/week; services at EC program",
                                    "Regular Early Childhood Program, <10 h/week; services outside EC program",
                                    "Early Childhood Special Education Setting (separate classroom for children with disabilities)",
                                    "Part-time childhood/part-time early childhood special education setting",
                                    "Home",
                                    "Hospital",
                                    "Residential Facility",
                                    "Separate School",
                                    "Public School (Kindergarten, Etc.)"
                                    );
                $arrValue = array(  "",
                                    "16",
                                    "17",
                                    "18",
                                    "19",
                                    "12",
                                    "13",
                                    "10",
                                    "09",
                                    "22",
                                    "23",
                                    "15");
 */
            	$arrLabel = array(  "Choose Location",
            						"Separate School",
                                    "Separate Class",
                                    "Residential Facility",
                                    "Home",
                                    "Service Provider Location",
                                    "Regular Early Childhood Program, 10+ h/week; services at EC program",
                                    "Regular Early Childhood Program, 10+ h/week; services outside EC program",
                                    "Regular Early Childhood Program, <10 h/week; services at EC program",
                                    "Regular Early Childhood Program, <10 h/week; services outside EC program",
                                    );
                $arrValue = array(  "",
                                    "5",
                                    "6",
                                    "7",
                                    "8",
                                    "9",
                                    "16",
                                    "17",
                                    "18",
                                    "19",
                					);
                break;
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
               // $arrLabel = array("Public School", "Public Separate Facility", "Public Residential Facility", "Private Separate Facility", "Private Residential Facility", "Home", "Hospital", "Correctional or Detention Facility", );
               // $arrValue = array("01", "02", "03", "06", "07", "10", "09", "14", );
               $arrLabel = array(  "Choose Location",
            						"Separate School",
                                    "Residential Facility",
                                    "Public School",
                                    "Home / Hospital",
                                    "Private School",
                                    "Correction / Detention Facility",
                                    );
                $arrValue = array(  "",
                                    "5",
                                    "7",
                                    "10",
                                    "13",
                                    "14",
                                    "15",
                					);
                break;
            default:
                $arrLabel = array("Public School", "Public Separate School", "Public Residential Facility", "NonPublic School", "NonPublic Separate School", "NonPublic Residential Facility", "Hospital", "Home");
                $arrValue = array("01", "02", "03", "05", "06", "07", "09", "10");
        }
        return array_combine($arrValue, $arrLabel);
    }

}

