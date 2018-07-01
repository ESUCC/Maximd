<?php
/**
 * Helper for displaying a printed footer 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <jlavere@soliantconsulting.com> 
 * @version   $Id: $
 */
class Zend_View_Helper_Form010FillFromPrevious extends Zend_View_Helper_Abstract
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
    public function form010FillFromPrevious($db_form_data, $view, $goalNum = 1)
    {
//    	Zend_debug::dump($db_form_data);
    	$this->_retString = "";
//    	return $this->_retString;
        $s = new Zend_View_Helper_Placeholder_Container();
        
        $s->captureStart('SET', 'data');
        if('edit' == $view->mode && isset($view->db_form_data['prevFormData'])) { 
            
            // auto-fill code based on last finalized progress report 
            $order   = array("\r\n", "\n", "\r", "'");
            $replace   = array("<br/>", "<br/>", "<br/>", "\'");
            
            // links to auto-fill from previous report
            if(isset($view->db_form_data['progress_reports'])) {
                $currentPgRpt = $view->db_form_data['id_form_010'];
                $firstPg = $view->db_form_data['progress_reports_1'];
                if($firstPg['id_form_010'] != $currentPgRpt) {
                ?><a href="#" onclick="fillFromPreviousReport(
                        '<?php echo $view->db_form_data['prevFormData']['goal_progress_1']['progress_measurement']; ?>',
                        '<?php echo htmlentities(str_replace($order, $replace, trim($view->db_form_data['prevFormData']['goal_progress_'.$goalNum]['progress_measurement_explain']))); ?>',
                        '<?php echo $view->db_form_data['prevFormData']['goal_progress_1']['progress_sufficient']; ?>',
                        '<?php echo htmlentities(str_replace($order, $replace, trim($view->db_form_data['prevFormData']['goal_progress_'.$goalNum]['progress_comment'] ))); ?>',
                        '<?php echo $goalNum; ?>'
                    )">Fill From Previous Report</a><?php 
                }
            }
        }
        $s->captureEnd() ?>
         
        <?php return $s->data;



		    	
    	return $this->_retString;
    }


}
