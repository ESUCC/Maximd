<?php
/**
 * Helper for getting form name from number
 * 
 * @uses      Zend_View_Helper_Abstract
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_FormName extends Zend_View_Helper_Abstract
{

    /**
     * Return script/button to initialize javaspell checker
     * 
     * @return string
     */
    public function formName($formNum)
    {
    	$FORM_NAMES = array( 
    			"form_001" => array( "fullName" => "Notice and Consent For Initial Evaluation", "shortName" => "Notice/Consent Initial Eval", 'date' => "date_notice" ),
    			"form_002" => array( "fullName" => "Multi-disciplinary Team Report", "shortName" => "MDT Report", 'date' => "date_notice" ),
    			"form_003" => array( "fullName" => "Notification of Individualized Education Program Meeting", "shortName" => "Notice of IEP Mtg", 'date' => "date_notice" ),
    			"form_004" => array( "fullName" => "Individualized Education Plan", "shortName" => "IEP", 'date' => "date_conference" ),
    			"form_005" => array( "fullName" => "Notice and Consent for Initial Placement", "shortName" => "Notice/Consent Initial Placement", 'date' => "date_notice" ),
    			"form_006" => array( "fullName" => "Notice of School District&rsquo;s Decision Regarding Requested Special Education Services", "shortName" => "Notice of Decision", 'date' => "date_notice" ),
    			"form_007" => array( "fullName" => "Notice and Consent for Reevaluation", "shortName" => "Notice/Consent Reeval", 'date' => "date_notice" ),
    			"form_008" => array( "fullName" => "Notice of Change of Placement", "shortName" => "Notice of Change of Placement", 'date' => "date_notice" ),
    			"form_009" => array( "fullName" => "Notice of Discontinuation of Special Education Services", "shortName" => "Notice of Discontinuation", 'date' => "date_notice" ),
    			"form_010" => array( "fullName" => "Progress Report", "shortName" => "Progress Report", 'date' => "date_notice" ),
    			"form_011" => array( "fullName" => "Notification of Multidisciplinary Team (MDT) Conference", "shortName" => "Notice of MDT Conference", 'date' => "mdt_conf_date" ),
    			"form_012" => array( "fullName" => "Notice That No Additional Information Is Needed To Determine Continued Eligibility", "shortName" => "Determination Notice", 'date' => "date_notice" ),
    			"form_013" => array( "fullName" => "Early Intervention Program", "shortName" => "IFSP", 'date' => "date_notice" ),
    			"form_014" => array( "fullName" => "Notification of Individualized Family Service Plan", "shortName" => "Notice of IFSP Mtg", 'date' => "date_notice" ),
    			"form_015" => array( "fullName" => "Notice and Consent For Initial Evaluation (IFSP)", "shortName" => "Notice/Consent Initial Eval (IFSP)", 'date' => "date_notice" ),
    			"form_016" => array( "fullName" => "Notice and Consent for Initial Placement (IFSP)", "shortName" => "Notice/Consent Initial Placement (IFSP)", 'date' => "date_notice" ),
    			"form_017" => array( "fullName" => "Note Page", "shortName" => "Notes", 'date' => "date_notice" ),
    			"form_018" => array( "fullName" => "Summary of Performance", "shortName" => "SOP", 'date' => "date_notice" ),
    			"form_019" => array( "fullName" => "Functional Assessment", "shortName" => "Functional Assessment", 'date' => "date_notice" ),
    			"form_020" => array( "fullName" => "Specialized Transportation", "shortName" => "Specialized Transportation", 'date' => "date_notice" ),
    			"form_021" => array( "fullName" => "Assistive Technology Considerations", "shortName" => "Assistive Technology Considerations", 'date' => "date_notice" ),
    			"form_022" => array( "fullName" => "MDT Card", "shortName" => "MDT Card", 'date' => "date_notice" ),
    			"form_023" => array( "fullName" => "IEP/IFSP Card", "shortName" => "IEP/IFSP Card", 'date' => "date_notice" ),
    			"form_024" => array( "fullName" => "Agency Consent Invitation", "shortName" => "Agency Consent Invitation", 'date' => "date_notice" ),
    			"form_025" => array( "fullName" => "Notification Of Multidisciplinary Team Planning Meeting", "shortName" => "Notification Of Multidisciplinary Team Planning Meeting", 'date' => "date_notice" ),
    			"form_026" => array( "fullName" => "Revocation of Consent for Special Education and Related Services", "shortName" => "Revocation of Consent Form", 'date' => "date_notice" ),
    			"form_027" => array( "fullName" => "Notice and Consent for Early Intervention Initial Screening", "shortName" => "Notice and Consent for Early Intervention Initial Screening", 'date' => "date_notice" ),
    			"form_028" => array( "fullName" => "Equitable Service Plan", "shortName" => "Equitable Service Plan", 'date' => "date_notice" ),
    			"form_029" => array( "fullName" => "Notice of Meeting", "shortName" => "Notice of Meeting", 'date' => "date_notice" ),
    			"form_030" => array( "fullName" => "Notice of Equitable Service Meeting", "shortName" => "Notice of Equitable Service Meeting", 'date' => "date_notice" ),
    	);
    	if(isset($FORM_NAMES["form_".$formNum])) {
    		return $FORM_NAMES["form_".$formNum]['fullName'];
    	} else {
    		return false;
    	}
// 		switch($formNum) {
// 			case '001':
// 				return '';
// 			case '002':
// 				return '';
// 			case '003':
// 				return '';
// 			case '004':
// 				return '';
// 			case '005':
// 				return '';
// 			case '006':
// 				return '';
// 			case '007':
// 				return '';
// 			case '008':
// 				return '';
// 			case '009':
// 				return '';
// 			case '010':
// 				return '';
// 			case '011':
// 				return '';
// 			case '012':
// 				return '';
// 			case '013':
// 				return '';
// 			case '014':
// 				return '';
// 			case '015':
// 				return '';
// 			case '016':
// 				return '';
// 			case '017':
// 				return '';
// 			case '018':
// 				return '';
// 			case '019':
// 				return '';
// 			case '020':
// 				return '';
// 			case '021':
// 				return '';
// 			case '022':
// 				return '';
// 			case '023':
// 				return '';
// 		}
    	return false;
    }
    
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
