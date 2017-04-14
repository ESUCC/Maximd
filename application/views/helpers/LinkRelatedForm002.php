<?php
/**
 * Helper for linking to related forms
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   Paste
 * @author    Jesse LaVere 
 */
class Zend_View_Helper_LinkRelatedForm002 extends Zend_View_Helper_Abstract
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
    public function linkRelatedForm002($id_student, $linkText, $type = 'any')
    {
    	$table = new Model_Table_Form002();
    	
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
    		$this->_link = '<a href="/form002/view/document/'.$form['id_form_002'].'/page/1" target="_blank">'.$linkText.'</a>';
    	} else {
    		$config = Zend_Registry::get('config');
    		$this->_link = '<a href="'.$config->NONZEND_URL.'/srs.php?area=student&sub=form_002&document='.$form['id_form_002'].'&page=&option=view" target="_blank">'.$linkText.'</a>';
    	}
        return $this->_link;
    }
}
