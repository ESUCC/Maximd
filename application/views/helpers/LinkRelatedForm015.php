<?php
/**
 * Helper for linking to related forms
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   Paste
 * @author    Jesse LaVere 
 */
class Zend_View_Helper_LinkRelatedForm015 extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_link;

    /**
     * Return base URL of application
     * 
     * @return string
     */
    public function linkRelatedForm015($id_student, $linkText, $type = 'any')
    {
    	$table = new Model_Table_Form015();
    	
    	if('draft' == $type) {
    		$form = $table->mostRecentDraftForm($id_student);
    	} elseif('final' == $type) {
    		$form = $table->mostRecentFinalForm($id_student);
    	} elseif('any' == $type) {
    		$form = $table->mostRecentForm($id_student);
    	} else {
    		return $linkText;
    	}
    	
    	if(false == $form) return $linkText;
    	
    	// build link
    	if(9 <= $form['version_number']) {
    		$this->_link = '<a href="/form015/view/document/'.$form['id_form_015'].'/page/1" target="_blank">'.$linkText.'</a>';
    	} else {
    		$config = Zend_Registry::get('config');
    		$this->_link = '<a href="'.$config->NONZEND_URL.'/srs.php?area=student&sub=form_015&document='.$form['id_form_015'].'&page=&option=view" target="_blank">'.$linkText.'</a>';
    	}
        return $this->_link;
    }
}
