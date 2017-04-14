<?php
/**
 * Helper for displaying a printed footer 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_StudentInfoHeader extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * build footer div
     * 
     * @return string
     */
    public function studentInfoHeader($db_form_data, $formNumber = null, $showDateOfNotice = false)
    {
        $funcName = "form".$formNumber;
        if(method_exists($this, $funcName)) {
            return $this->$funcName($db_form_data, $showDateOfNotice);
        } else {
        	return $this->form001($db_form_data);
        }
    }

    public function form001($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = isset($db_form_data['finalized_name_school']) ? $db_form_data['finalized_name_school'] : '';
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	
        $this->_retString = "";
//      return $this->_retString;
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Student').'</span>: '.$student.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Date of Notice').'</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['date_notice']).'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('School').'</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('School District').'</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td colspan="2"><span class="btsb">'.$this->view->translate('Parents').'</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';                
        return $this->_retString;
    }

    public function form002($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	
        $this->_retString = "";
//      return $this->_retString;
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb" colspan="2">Date of MDT</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['date_mdt']).'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Student</span>: '.$student.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Date Notice</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['date_notice']).'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';                
        return $this->_retString;
    }
    
    public function form003($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	
        $this->_retString = "";
//      return $this->_retString;
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Student</span>: '.$student.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Age</span>: '.$db_form_data['student_data']['age'].'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Date of Birth</span>: '.date('mm/dd/Y', strtotime($db_form_data['student_data']['dob'])).'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Gender</span>: '.$db_form_data['student_data']['gender'].'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Grade</span>: '.$db_form_data['student_data']['grade'].'&nbsp;</td>';
        $this->_retString .= '      <td colspan="2"><span class="btsb">Address</span>: '.$db_form_data['student_data']['address'].'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';                
        return $this->_retString;
    }
    
    public function form004($db_form_data)
    {
    
    	if('Final'==$db_form_data['status']) {
			$student = $db_form_data['finalized_student_name'];
//			$age = $db_form_data['finalized_age'];
			$schoolDistrict = $db_form_data['finalized_name_district'];
			$gender = $db_form_data['finalized_gender'];
            $nameSchool = isset($db_form_data['finalized_name_school']) ? $db_form_data['finalized_name_school'] : '';
			$grade = $db_form_data['finalized_grade'];
			$parents = $db_form_data['finalized_parents'];
// 			$address = $db_form_data['finalized_address'];
			$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
			$student = $db_form_data['student_data']['name_student'];
//			$age = $db_form_data['student_data']['dob'];
			$schoolDistrict = $db_form_data['name_district'];
			$gender = $db_form_data['student_data']['gender'];
			$nameSchool = $db_form_data['name_school'];
			$grade = $db_form_data['student_data']['grade'];
			$parents = $db_form_data['student_data']['parents'];
// 			$address = $db_form_data['finalized_address'];
			$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}    		
	
    	$this->_retString = "";
    	$this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
    	$this->_retString .= '  <tr class="noprint">';
    	$this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Access Level').'</span>: '.$db_form_data['form_config']['formAccess']['description'].'&nbsp;</td>';
    	$this->_retString .= '      <td>&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Student').'</span>: '.$student.'&nbsp;</td>';
    	$this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Date of Birth').'</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['student_data']['dob']).'&nbsp;</td>';
    	$this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Grade').'</span>: '.$grade.'&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">'.$this->view->translate('School').'</span>: '.$nameSchool.'&nbsp;</td>';
    	$this->_retString .= '      <td><span class="btsb">'.$this->view->translate('School District').'</span>: '.$schoolDistrict.'&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td colspan="1"><span class="btsb">'.$this->view->translate('Parents').'</span>: '.$parents.'&nbsp;</td>';
    	if ($this->view->mode == 'print')
    	{
    		$this->_retString .= '<td colspan="2">'.$this->view->translate('This IEP will be in effect from').' '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['effect_from_date']).' to '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['effect_to_date']).'</td>';
    	}
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '</table>';
    	
        return $this->_retString;
    }
    public function form005($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	
        $this->_retString = "";
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Student</span>: '.$db_form_data['student_data']['name_student'].'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';                
        return $this->_retString;
    }

    public function form006($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	
        $this->_retString = "";
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Student</span>: '.$db_form_data['student_data']['name_student'].'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';                
        return $this->_retString;
    }

    public function form007($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	
        $this->_retString = "";
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Student</span>: '.$db_form_data['student_data']['name_student'].'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';                
        return $this->_retString;
    }

    public function form008($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	
        $this->_retString = "";
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Student</span>: '.$db_form_data['student_data']['name_student'].'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';                
        return $this->_retString;
    }

    public function form011($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		// 			$address = $db_form_data['finalized_address'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	
        $this->_retString = "";
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Student</span>: '.$db_form_data['student_data']['name_student'].'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';                
        return $this->_retString;
    }
    
    public function form013($db_form_data)
    {    
        $this->_retString = "";
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td> &nbsp; </td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';
        return $this->_retString;
    }

    public function form017($db_form_data)
    {
        if('Final'==$db_form_data['status']) {
            $student = $db_form_data['finalized_student_name'];
            $age = $db_form_data['finalized_age'];
            $schoolDistrict = $db_form_data['finalized_name_district'];
            $gender = $db_form_data['finalized_gender'];
            $nameSchool = isset($db_form_data['finalized_name_school']) ? $db_form_data['finalized_name_school'] : '';
            $grade = $db_form_data['finalized_grade'];
            $parents = $db_form_data['finalized_parents'];
            $date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        } else {
            $student = $db_form_data['student_data']['name_student'];
            $age = $db_form_data['student_data']['dob'];
            $schoolDistrict = $db_form_data['name_district'];
            $gender = $db_form_data['student_data']['gender'];
            $nameSchool = $db_form_data['name_school'];
            $grade = $db_form_data['student_data']['grade'];
            $parents = $db_form_data['student_data']['parents'];
            $date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        }

        $this->_retString = "";
//      return $this->_retString;
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Student').'</span>: '.$student.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Date').'</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['date_notice']).'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('School').'</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('School District').'</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td colspan="2"><span class="btsb">'.$this->view->translate('Parents').'</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';
        return $this->_retString;
    }

    public function form025($db_form_data)
    {
        if('Final'==$db_form_data['status']) {
            $student = $db_form_data['finalized_student_name'];
            $age = $db_form_data['finalized_age'];
            $schoolDistrict = $db_form_data['finalized_name_district'];
            $gender = $db_form_data['finalized_gender'];
            $nameSchool = $db_form_data['finalized_name_school'];
            $grade = $db_form_data['finalized_grade'];
            $parents = $db_form_data['finalized_parents'];
            $date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        } else {
            $student = $db_form_data['student_data']['name_student'];
            $age = $db_form_data['student_data']['dob'];
            $schoolDistrict = $db_form_data['name_district'];
            $gender = $db_form_data['student_data']['gender'];
            $nameSchool = $db_form_data['name_school'];
            $grade = $db_form_data['student_data']['grade'];
            $parents = $db_form_data['student_data']['parents'];
            $date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        }

        $this->_retString = "";
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Student</span>: '.$student.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Age</span>: '.$db_form_data['student_data']['age'].'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">Date of Birth</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['student_data']['dob']).'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">School</span>: '.$nameSchool.'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';
        return $this->_retString;
    }
    
    public function form026($db_form_data, $showDateOfNotice)
    {
    	$page = Zend_Controller_Front::getInstance()->getRequest()->getParam('page');
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    		$date_of_notice = new Zend_Date( $db_form_data['date_of_notice_discontinuation'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    		$date_of_notice = new Zend_Date( $db_form_data['date_of_notice_discontinuation'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    
    	$this->_retString = "";
    	$this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">Student</span>: '.$student.'&nbsp;</td>';
    	$this->_retString .= '      <td><span class="btsb">Date of Birth</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['student_data']['dob']).'&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
    	$this->_retString .= '      <td><span class="btsb">School Building</span>: '.$nameSchool.'&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
    	if ($showDateOfNotice) {
    		$this->_retString .= '      <td><span class="btsb">Date of Notice/Discontinuation</span>: '.$date_of_notice.'</td>';
    	} else {
    		$this->_retString .= '<td> &nbsp; </td>';
    	}
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '</table>';
    	return $this->_retString;
    }
    
    public function form027($db_form_data)
    {
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    		$date_of_referral = new Zend_Date( $db_form_data['date_of_referral'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    		$date_of_referral = new Zend_Date( $db_form_data['date_of_referral'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}
    	$this->_retString = "";
    	$this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">Student</span>: '.$student.'&nbsp;</td>';
    	$this->_retString .= '      <td><span class="btsb">Date of Birth</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['student_data']['dob']).'&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">School District</span>: '.$schoolDistrict.'&nbsp;</td>';
    	$this->_retString .= '      <td><span class="btsb">School Building</span>: '.$nameSchool.'&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">Parents</span>: '.$parents.'&nbsp;</td>';
		$this->_retString .= '      <td><span class="btsb">Date of Referral</span>: '.$date_of_referral.'&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '</table>';
    	return $this->_retString;
    }
    
    public function form028($db_form_data)
    {
    	$studentModel = new Model_Table_StudentTable();
    	$studentInfo = $studentModel->studentInfo($db_form_data['id_student']);
    	
    	if('Final'==$db_form_data['status']) {
    		$student = $db_form_data['finalized_student_name'];
    		$age = $db_form_data['finalized_age'];
    		$schoolDistrict = $db_form_data['finalized_name_district'];
    		$gender = $db_form_data['finalized_gender'];
    		$nameSchool = $db_form_data['finalized_name_school'];
    		$grade = $db_form_data['finalized_grade'];
    		$parents = $db_form_data['finalized_parents'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    		$curr_date = new Zend_Date( date('Y-m-d'), Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    		$date_of_referral = new Zend_Date( $db_form_data['date_of_referral'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	} else {
    		$student = $db_form_data['student_data']['name_student'];
    		$age = $db_form_data['student_data']['dob'];
    		$schoolDistrict = $db_form_data['name_district'];
    		$gender = $db_form_data['student_data']['gender'];
    		$nameSchool = $db_form_data['name_school'];
    		$grade = $db_form_data['student_data']['grade'];
    		$parents = $db_form_data['student_data']['parents'];
    		$date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    		$curr_date = new Zend_Date( date('Y-m-d'), Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    		$date_of_referral = new Zend_Date( $db_form_data['date_of_referral'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
    	}

//    	$this->_retString .= '      <td><span class="btsb">Age</span>: '.$db_form_data['student_data']['age'].'&nbsp;</td>';
//    	$this->_retString .= '      <td><span class="btsb">Today\'s Date</span>: '.App_Form_Element_DatePicker::humanReadableDate(date('m/d/Y')).'&nbsp;</td>';
//    	$this->_retString .= '      <td><span class="btsb">MDT Re-evaluation Date:</span> '.(($db_form_data['mdt_re_evaluation']) ? App_Form_Element_DatePicker::humanReadableDate($db_form_data['mdt_re_evaluation']) : '').'</td>';
//	    	$this->_retString .= '      <td><span class="btsb">Work Phone</span>: '.$db_form_data['parents_'.$i]['work_phone'].'</td>';
//    	$this->_retString .= '      <td colspan="4"><span class="btsb">Public School District Providing Services</span>: '.$db_form_data['public_school_district_providing_services'].'</td>';

    	$this->_retString = "";
    	$this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">Student</span>: '.$student.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Date of Birth</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['student_data']['dob']).'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">Grade</span>: '.$grade.'&nbsp;</td>';
    	$this->_retString .= '  </tr>';
    	for ($i=1;$i<=$db_form_data['parents']['count'];$i++) {
	    	$this->_retString .= '  <tr>';
	    	$this->_retString .= '      <td><span class="btsb">Parent\'s Name</span>: '.$db_form_data['parents_'.$i]['parent_name'].'</td>';
	    	$this->_retString .= '      <td><span class="btsb">Home Phone</span>: '.$db_form_data['parents_'.$i]['home_phone'].'</td>';
	    	$this->_retString .= '      <td><span class="btsb">Email</span>: '.$db_form_data['parents_'.$i]['email_address'].'</td>';
	    	$this->_retString .= '  </tr>';
    	}
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '      <td><span class="btsb">Non-Public School</span>: '. $studentInfo[0]['nonpublicschool_name'] .'</td>';
    	$this->_retString .= '      <td colspan="2"><span class="btsb">Resident District</span>: '.$db_form_data['student_data']['name_district'].'</td>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '  <tr>';
    	$this->_retString .= '  </tr>';
    	$this->_retString .= '</table>';
    	return $this->_retString;
    }
    
    public function form031($db_form_data)
    {
        if('Final'==$db_form_data['status']) {
            $student = $db_form_data['finalized_student_name'];
            $age = $db_form_data['finalized_age'];
            $schoolDistrict = $db_form_data['finalized_name_district'];
            $gender = $db_form_data['finalized_gender'];
            $nameSchool = isset($db_form_data['finalized_name_school']) ? $db_form_data['finalized_name_school'] : '';
            $grade = $db_form_data['finalized_grade'];
            $parents = $db_form_data['finalized_parents'];
            $date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        } else {
            $student = $db_form_data['student_data']['name_student'];
            $age = $db_form_data['student_data']['dob'];
            $schoolDistrict = $db_form_data['name_district'];
            $gender = $db_form_data['student_data']['gender'];
            $nameSchool = $db_form_data['name_school'];
            $grade = $db_form_data['student_data']['grade'];
            $parents = $db_form_data['student_data']['parents'];
            $date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
        }
         
        $this->_retString = "";
        //      return $this->_retString;
        $this->_retString .= '<table class="formDesc" cellpadding="0" cellspacing="0">';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Student').'</span>: '.$student.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Date of Referral').'</span>: '.App_Form_Element_DatePicker::humanReadableDate($db_form_data['date_notice']).'&nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '  <tr>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('School District').'</span>: '.$schoolDistrict.'&nbsp;</td>';
        $this->_retString .= '      <td><span class="btsb">'.$this->view->translate('Parents').'</span>: '.$parents.' &nbsp;</td>';
        $this->_retString .= '  </tr>';
        $this->_retString .= '</table>';
        return $this->_retString;
    }

}
        
