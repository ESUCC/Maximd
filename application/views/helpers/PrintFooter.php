<?php
/**
 * Helper for displaying a printed footer
 *
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <jlavere@soliantconsulting.com>
 * @version   $Id: $
 */
class Zend_View_Helper_PrintFooter extends Zend_View_Helper_Abstract
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
    public function printFooter($db_form_data, $formTitle, $formNum, $rev)
    {
        if ('55' == $db_form_data ['id_county'] && '0001' == $db_form_data['id_district']) {
            $lps = 1;
        } else {
            $lps = 0;
        }



        if('Final'==$db_form_data['status']) {
            $grade = $db_form_data['finalized_grade'];
            $name_school = isset($db_form_data['finalized_name_school']) ? $db_form_data['finalized_name_school'] : '';
            $name_district = isset($db_form_data['finalized_name_district']) ? $db_form_data['finalized_name_district'] : '';

//            $student = $db_form_data['finalized_student_name'];
//            $age = $db_form_data['finalized_age'];
//            $schoolDistrict = $db_form_data['finalized_name_district'];
//            $gender = $db_form_data['finalized_gender'];
//            $parents = $db_form_data['finalized_parents'];
//            $address = $db_form_data['finalized_address'];
//            if(substr_count($this->element->dob->getValue (), '/')) {
//                $date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::MONTH . '/' . Zend_Date::DAY . '/' . Zend_Date::YEAR);
//            } elseif(substr_count($this->element->dob->getValue (), '-')) {
//                $date = new Zend_Date( $db_form_data['finalized_dob'], Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
//            }
        } else {
            $grade = $db_form_data['student_data']['grade'];
            $name_school = $db_form_data['student_data']['name_school'];
            $name_district = $db_form_data['student_data']['name_district'];
        }


        $this->_retString = "";

        if ($formNum == 13)
        {
            $this->_retString .= '<div id="printFooter">';
            $this->_retString .= '<HR style="width: 100%; border: solid thin;" />';
            $this->_retString .= '<div style="float:right;">';
            $this->_retString .= '<table style="width: 100%;" border="0">';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine1">';
            $this->_retString .= $db_form_data['status'];
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine2">';
            $this->_retString .= 'EI-1 Rev 11/98 (57060)';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine2">';
            $this->_retString .= 'Page <span style="font-weight: bold; content: counter(page)"></span>';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '</table>';
            $this->_retString .= '</div>';
            $this->_retString .= '<div style="float:left;">';
            $this->_retString .= '<table style="width: 100%;" border="0">';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine1">Student: ';
            $this->_retString .= $db_form_data['student_data']['name_student'];
            $this->_retString .= '        | District: ';
            $this->_retString .= $name_district;
            $this->_retString .= '        | School: ';
            $this->_retString .= $name_school;
            $this->_retString .= ' | DOB: ';
            $this->_retString .= $db_form_data['student_data']['dob'];
            if($lps) {
                $this->_retString .= ' | Student ID #';
                $this->_retString .= $db_form_data['student_data']['id_student_local'];
            } else {
                $this->_retString .= ' | SRS Student ID #';
                $this->_retString .= $db_form_data['student_data']['id_student'];
            }
            $this->_retString .= '</td>';
            $this->_retString .= '<td class="printFooterStatus" style="text-align: center;" rowspan="2">';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine2" style="">NEBRASKA INDIVIDUAL FAMILY SERVICE PLAN (IFSP) ';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '</table>';
            $this->_retString .= '</div>';

            $this->_retString .= '</div>';

            // code a second footer to use on page 9 of the ifsp - status removed
            $this->_retString .= '<div id="printFooterNoStatus">';
            $this->_retString .= '<HR style="width: 100%; border: solid thin;" />';
            $this->_retString .= '<div style="float:right;">';
            $this->_retString .= '<table style="width: 100%;" border="0">';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine1">';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine2">';
            $this->_retString .= 'EI-1 Rev 11/98 (57060)';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine2">';
            $this->_retString .= 'Page <span style="font-weight: bold; content: counter(page)"></span>';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '</table>';
            $this->_retString .= '</div>';
            $this->_retString .= '<div style="float:left;">';
            $this->_retString .= '<table style="width: 100%;" border="0">';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine1">Student: ';
            $this->_retString .= $db_form_data['student_data']['name_student'];
            $this->_retString .= '        | School: ';
            $this->_retString .= $name_school;
            $this->_retString .= ' | DOB: ';
            $this->_retString .= $db_form_data['student_data']['dob'];
            if($lps) {
                $this->_retString .= ' | SRS Student ID #';
                $this->_retString .= $db_form_data['student_data']['id_student_local'];
            } else {
                $this->_retString .= ' | SRS Student ID #';
                $this->_retString .= $db_form_data['student_data']['id_student'];
            }
            $this->_retString .= '</td>';
            $this->_retString .= '<td class="printFooterStatus" style="text-align: center;" rowspan="2">';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine2" style="padding-left:110px;">NEBRASKA INDIVIDUAL FAMILY SERVICE PLAN (IFSP) ';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '</table>';
            $this->_retString .= '</div>';

            $this->_retString .= '</div>';
        }
        else
        {
            // all other forms (NOT IFSP)

            $this->_retString .= '<div id="printFooter">';
            $this->_retString .= '<HR style="width: 100%; border: solid thin;" />';
            $this->_retString .= '<table style="width: 100%;" border="0">';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine1">Student: ';
            $this->_retString .= $db_form_data['student_data']['name_student'];
            $this->_retString .= '        | School: ';
            $this->_retString .= $name_school;
            $this->_retString .= ' | Grade:';
            $this->_retString .= $grade;
            $this->_retString .= ' | DOB: ';
            $this->_retString .= $db_form_data['student_data']['dob'];;
            $this->_retString .= ' | SRS Student ID #';
            if($lps) {
                $this->_retString .= $db_form_data['student_data']['id_student_local'];
            } else {
                $this->_retString .= $db_form_data['student_data']['id_student'];
            }
            $this->_retString .= '</td>';
            $this->_retString .= '<td class="printFooterStatus" style="text-align: center;" rowspan="2">';
            $this->_retString .= $db_form_data['status'];
                $this->_retString .= '<br>Page <span style="content: counter(page)"></span>';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine2">Form '.$formNum.' (rev. '.$rev.') | ';
            $this->_retString .= $formTitle;
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '</table>';
            $this->_retString .= '</div>';

            // print footer no status
            // used on pages where parents sign
            $this->_retString .= '<div id="printFooterNoStatus">';
            $this->_retString .= '<HR style="width: 100%; border: solid thin;" />';
            $this->_retString .= '<table style="width: 100%;" border="0">';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine1">Student: ';
            $this->_retString .= $db_form_data['student_data']['name_student'];
            $this->_retString .= '        | School: ';
            $this->_retString .= $name_school;
            $this->_retString .= ' | Grade:';
            $this->_retString .= $grade;
            $this->_retString .= ' | DOB: ';
            $this->_retString .= $db_form_data['student_data']['dob'];;
            $this->_retString .= ' | SRS Student ID #';
            if($lps) {
                $this->_retString .= $db_form_data['student_data']['id_student_local'];
            } else {
                $this->_retString .= $db_form_data['student_data']['id_student'];
            }
            $this->_retString .= '</td>';
            $this->_retString .= '<td class="printFooterStatus" style="text-align: center;" rowspan="2">';
            $this->_retString .= '<br>Page <span style="content: counter(page)"></span>';
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '<tr>';
            $this->_retString .= '<td class="printFooterLine2">Form '.$formNum.' (rev. '.$rev.') | ';
            $this->_retString .= $formTitle;
            $this->_retString .= '</td>';
            $this->_retString .= '</tr>';
            $this->_retString .= '</table>';
            $this->_retString .= '</div>';

        }

        return $this->_retString;
    }


}
