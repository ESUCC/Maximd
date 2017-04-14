<?php
/**
 * App_Form_ValueListHelper
 *
 * @category   Zend
 * @package    App_Form
 */
class App_Form_ValueListHelper 
{

    static public function goalSchedule()
    {
        $options = array(
            ''=>'...Choose',
			'A'=>'A - 6 Weeks', 
			'B'=>'B - 9 Weeks', 
			'C'=>'C - Semester', 
			'D'=>'D - Other',
        );
        return $options;
    }

    static public function goalEvalProcedure()
    {
        $options = array(
            'A' => 'Teacher Observation', 
            'B' => 'Written Performance', 
            'C' => 'Oral Performance', 
            'D' => 'Criterion Reference Test', 
            'E' => 'Parent Report', 
            'F' => 'Time Sample', 
            'G' => 'Report Cards', 
            'H' => 'Other', 
        );
        return $options;
    }

    static public function attendResponse()
    {
        $options = array(
            'yes'       =>'I/We will attend the meeting scheduled for:', 
            'contact'   =>'I/We cannot attend the meeting as arranged. Please contact me/us.', 
            'phone'     =>'I/We cannot attend, but would like to participate by telephone or written communication.', 
            'no'        =>'I/We do not wish to attend or participate in this meeting.',
        );
        return $options;
    }
    static public function agreeResponse()
    {
        $options = array(
            'yes'       =>'I/We agree that no further assessment is necessary.', 
            'no'        =>'Please conduct further assessment.',
        );
        return $options;
    }
    static public function giveConsent()
    {
    	$options = array(
            1   => "I have received a copy of the Notice of this proposed evaluation and my 
				        parental rights, understand the content of the Notice and give consent 
				        for the multidisciplinary evaluation specified in this Notice. I 
				        understand this consent is voluntary and may be revoked at any time.", 
            0   => "I have received a copy of the Notice of this proposed evaluation and my 
				        parental rights, understand the content of the Notice and do not give 
				        consent for the multidisciplinary evaluation specified in this Notice.",
        );
        return $options;
    }

    static public function form015Consent()
    {
        $options = array(
            1   => "I/we have received a copy of the Notice of this proposed evaluation, understand 
            the content of the Notice and give consent  for the multidisciplinary evaluation specified 
            in this Notice. I/we understand this consent is voluntary and may be revoked at any time.", 
            0   => "I/we have received a copy of the Notice of this proposed evaluation, understand 
            the content of the Notice and do not give consent for the multidisciplinary evaluation 
            specified in this Notice. The reason for not giving consent to the evaluation is:",
        );
        return $options;
    }

    static public function form031Consent()
    {
        $options = array(
            1   => "I/We have received a copy of the Notice for Early Intervention Initial Multidisciplinary " .
                "Evaluation and Child Assessment; understand the content of the Notice and GIVE CONSENT for the " .
                "Multidisciplinary Evaluation and Child Assessment (if eligible) specified in the Notice. I/We " .
                "understand that this consent is voluntary and I/We may withdraw consent at any time. If I/We " .
                "withdraw consent, I/We understand it is not retroactive.",
            0   => "I/We have received a copy of the Notice for Early Intervention Initial Multidisciplinary and " .
                "Child Assessment, understand the content of the Notice, and DO NOT GIVE CONSENT for the " .
                "Multidisciplinary Evaluation/Child Assessment specified in the notice.",
        );
        return $options;
    }

    static public function form014Attend()
    {
        $options = array(
            1   => "I plan to attend the meeting as scheduled.",
            0   => "I will need to reschedule the meeting for the following date, time and place:",
        );
        return $options;
    }

    static public function form030Attend()
    {
    	$options = array(
    			1   => "I plan to attend the meeting as scheduled.",
    			0   => "I will need to reschedule the meeting for the following date, time and place:",
    	);
    	return $options;
    }

    static public function referralStatus()
    {
        $options = array(
            "Not Completed",
            "Completed",
            "On Waiting List",
            "Denied",
            "Accepted",
            "Not Applicable",
            "Other",
        );
        return array_combine($options, $options);
    }

    static public function yesNo()
    {
        $options = array(
            'yes'       =>'Yes',
            'no'        =>'No',
        );
        return $options;
    }

    static public function amPm()
    {
        $options = array(
            'am'       =>'AM',
            'pm'        =>'PM',
        );
        return $options;
    }

    static public function specializedTransportation()
    {
        $options = array(
            'Adult will meet the child at the bus'       =>'Adult will meet the child at the bus',
            'Child can enter the residence without adult supervision.'        =>'Child can enter the residence without adult supervision.',
        );
        return $options;
    }
    static public function disabilityBasedOn()
    {
        $options = array(
            'Disability'                    =>'Disability',
            'Non-home school placement'     =>'Non-home school placement',
            'Age (ECSE)'                    => 'Age (ECSE)',
            'Section 504'                   => 'Section 504',
        );
        return $options;
    }
    static public function ifspLanguages($noneLabel = 'Choose...')
    {
        $options = array(
			'' => $noneLabel,
			'Sign Language' => 'Sign Language',
			'English' => 'English',
			'Afrikaans' => 'Afrikaans',
			'Albanian' => 'Albanian',
			'Amharic' => 'Amharic',
			'Arabic' => 'Arabic',
			'Bangle' => 'Bangle',
			'Bhutanese' => 'Bhutanese',
			'Bosnian' => 'Bosnian',
			'Chinese' => 'Chinese',
			'Croatian' => 'Croatian',
			'Czech' => 'Czech',
			'Danish' => 'Danish',
			'Dari' => 'Dari',
			'Dinka' => 'Dinka',
			'Dutch' => 'Dutch',
			'Farsi' => 'Farsi',
			'Finnish' => 'Finnish',
			'French' => 'French',
			'German' => 'German',
			'Gujarati' => 'Gujarati',
			'Hindi' => 'Hindi',
			'Hungarian' => 'Hungarian',
			'Indonesian' => 'Indonesian',
			'Italian' => 'Italian',
			'Japanese' => 'Japanese',
			'Khana' => 'Khana',
			'Khmer' => 'Khmer',
			'Korean' => 'Korean',
			'Kurdish' => 'Kurdish',
			'Latvian' => 'Latvian',
			'Luganda' => 'Luganda',
			'Lumasaba' => 'Lumasaba',
			'Mandarin' => 'Mandarin',
			'Nepalis' => 'Nepalis',
			'Nuer' => 'Nuer',
			'Nyanja' => 'Nyanja',
			'Ogoni' => 'Ogoni',
			'Oriya' => 'Oriya',
			'Pashtu' => 'Pashtu',
			'Persian' => 'Persian',
			'Pilipino' => 'Pilipino',
			'Polish' => 'Polish',
			'Portuguese' => 'Portuguese',
			'Punjabi' => 'Punjabi',
			'Romanian' => 'Romanian',
			'Russian' => 'Russian',
			'Serbo-Croat' => 'Serbo-Croat',
			'Sinhala' => 'Sinhala',
			'Somali' => 'Somali',
			'Spanish' => 'Spanish',
			'Swahili' => 'Swahili',
			'Tagalog' => 'Tagalog',
			'Tajik' => 'Tajik',
			'Tamil' => 'Tamil',
			'Telegu' => 'Telegu',
			'Thai' => 'Thai',
			'Tigrbea' => 'Tigrbea',
			'Tigrigna' => 'Tigrigna',
			'Tonga' => 'Tonga',
			'Tswana' => 'Tswana',
			'Turkish' => 'Turkish',
			'Ukrainian' => 'Ukrainian',
			'Urdu' => 'Urdu',
			'Vietnamese' => 'Vietnamese',
            'Other' => 'Other',
        );
        return $options;
    }

    static public function parentGuardian()
    {
        $options = array(
            ''       =>'Choose...',
            'Parent'       =>'Parent',
            'Guardian'        =>'Guardian',
        );
        return $options;
    }


    static public function ifspService()
    {
        $options = array(
            '' => 'Choose Service',
            'Assistive technology services/devices' => 'Assistive technology services/devices',
            'Audiology' => 'Audiology',
        	'Child Care' => 'Child Care',
            'ECSE' => 'ECSE',
            'Family training, counseling, home visits and other supports' => 'Family training, counseling, home visits and other supports',
            'Health services' => 'Health services',
            'Medical services (for diagnostic or evaluation purposes)' => 'Medical services (for diagnostic or evaluation purposes)',
            'Nursing services' => 'Nursing services',
            'Nutrition services' => 'Nutrition services',
            'Occupational Therapy Services' => 'Occupational Therapy Services',
            'Physical Therapy' => 'Physical Therapy',
            'Psychological services' => 'Psychological services',
            'Respite care' => 'Respite care',
            'Services coordination' => 'Services coordination',
            'Sign Language Interpreter' => 'Sign Language Interpreter',
            'Social work services' => 'Social work services',
            'Speech-language therapy' => 'Speech-language therapy',
            'Transportation' => 'Transportation',
            'Vision Services' => 'Vision Services',
            'Other' => 'Other',
        );
        return $options;
    }
    static public function ifspWhere()
    {
        $options = array(
			'' => 'Choose Setting',
            'Home' => 'Home',
            'Community' => 'Community',
            'Other' => 'Other',
//			'Hospital' => 'Hospital',
//			'Other Settings (Outpatient facility, clinic)' => 'Other Settings (Outpatient facility, clinic)',
//			'Program Designed for Children with Developmental Delays or Disabilities' => 'Program Designed for Children with Developmental Delays or Disabilities',
//			'Program Designed for Typically Developing Children' => 'Program Designed for Typically Developing Children',
//			'Residential Facility' => 'Residential Facility',
//			'Service Provider Location (Outpatient facility, clinic)' => 'Service Provider Location (Outpatient facility, clinic)',
        );
        return $options;
    }

    static public function ifspHowOften()
    {
        $options = array(
            'w'       =>'days/week',
            'm'        =>'days/month',
            '1'        =>'days/3 month period',
            's'        =>'days/6 month period',
            'y'        =>'days/year',
        );
        return $options;
    }

    static public function ifspHowMuch()
    {
        $options = array(
            'm'        =>'min/day',
            'h'        =>'hrs/day',
            'mo'        =>'hrs/month',
            'hw'        =>'hrs/week',
        );
        return $options;
    }

    static public function ifspGroupIndividual()
    {
        $options = array(
            ''                      =>'Choose',
            'Group'                 =>'Group',
            'Individual'            =>'Individual',
            'Group and Individual'  =>'Group and Individual',
        );
        return $options;
    }

    static public function ifspWhoPays()
    {
        $options = array(
            "" => "Choose Who Pays",
            "CHIP (Cmp Health Ins Pool)" => "CHIP (Cmp Health Ins Pool)",
            "DCP - Disabled Children's Program" => "DCP - Disabled Children's Program",
            "DPFS - Disabled Person's Family Support" => "DPFS - Disabled Person's Family Support",
            "DHHS" => "DHHS",
            "Early Development Network" => "Early Development Network",
            "Family" => "Family",
            "Health Insurance" => "Health Insurance",
            "Medicaid Waivers" => "Medicaid Waivers",
            "Medicaid" => "Medicaid",
            "MHCP - Medically Handicapped Children's Program" => "MHCP - Medically Handicapped Children's Program",
            "Respite Subsidy" => "Respite Subsidy",
            "School district" => "School district",
            "WIC (Women's/Infants &amp; Children)" => "WIC (Women's/Infants &amp; Children)",
            "Other" => "Other",
        );
        return $options;
    }

    static public function ifspWhoResponsible($version = 9)
    {
        $options = array(
            "" => "Choose Who's Responsible",
            "Audiologist" => "Audiologist",
            "Counselor" => "Counselor",
            "Deaf Educator" => "Deaf Educator",
            "Family" => "Family",
            "Heath Care Provider" => "Heath Care Provider",
            "Nurse" => "Nurse",
            "Nutritionist" => "Nutritionist",
            "Occupational therapist" => "Occupational therapist",
            "Other" => "Other",
            "Physical therapist" => "Physical therapist",
            "Primary Service Provider" => "Primary Service Provider",
            "Psychologist" => "Psychologist",
            "Respite Care Provider" => "Respite Care Provider",
            "Services Coordinator" => "Services Coordinator",
            "Social worker" => "Social worker",
            "Speech pathologist" => "Speech pathologist",
            "Teacher" => "Teacher",
            "Vision Specialist" => "Vision Specialist",
        );
        
        if ($version == 10) {
            unset($options['Primary Service Provider']);
        }
        return $options;
    }


    static public function ifspRole()
    {
        $options = array(
            "" => "Choose...",
            "Parent" => "Parent",
            "Other Family Member" => "Other Family Member",
            "Advocate" => "Advocate",
            "Early Childhood Special Educator" => "Early Childhood Special Educator",
            "Service Coordinator" => "Service Coordinator",
            "School District Rep" => "School District Rep",
            "Service Provider" => "Service Provider",
            "Person Conducting Eval" => "Person Conducting Eval",
            "Other" => "Other",
            "Child Care Provider" => "Child Care Provider",
        );
        return $options;
    }
        
    static public function ifspType()
    {
        $options = array(
            "" => "Choose...",
            "Annual" => "Annual",
            "Initial" => "Initial",
            "Interim" => "Interim",
            "Periodic" => "Periodic",
        );
        return $options;
    }
        
     
 
 
 
 
    
}


