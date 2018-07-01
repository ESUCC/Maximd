<?php
/**
 * Helper for displaying a subform 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <jlavere@soliantconsulting.com> 
 * @version   $Id: $
 */
class Zend_View_Helper_ViewAccChecklistSection extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    protected $_retString;

    /**
     * build bar of links to form options
     * 
     * @return string
     */
    public function viewAccChecklistSection($form, $fieldListArray)
    {
    	$this->_retString = "";
    	$listData = "";
    	$output = false;
    	
    	$categoryArr = array();
//    	Zend_Debug::dump($fieldListArray, 'fieldListArray');
    	foreach($fieldListArray as $elementName)
    	{
            if('App_Form_Element_MultiSelect' == $form->element->$elementName->getType() && is_array($form->element->$elementName->getValue())) {
                // multi-select
                foreach($form->element->$elementName->getValue() as $value) {
                    $multiLabel = $form->element->$elementName->getMultiOption($value);
                    $categoryArr[$multiLabel][] = "".$this->view->translate($form->element->$elementName->getDescription())." - " . $form->element->$elementName->getLabel() ."";
                    $output = true;
                }
            } else {
                // regular select
                if($form->element->$elementName->getValue() != '') {
                    $multiLabel = $form->element->$elementName->getMultiOption($form->element->$elementName->getValue());
                    $categoryArr[$multiLabel][] = "".$this->view->translate($form->element->$elementName->getDescription())." - " . $form->element->$elementName->getLabel() ."";
                    $output = true;
                }
            }
    	}
        if(count($categoryArr) > 0)
        {
        	foreach($categoryArr as $category => $items)
        	{
                $this->_retString .= "<div style=\"page-break-inside: avoid;\">";
                $this->_retString .= "<b>".$this->view->translate($category)."</B><BR>";
                $this->_retString .= "<ul class=\"\">";
                foreach($items as $catItem)
                {
                	$this->_retString .= $catItem . ', ';
                }
                $this->_retString .= "</ul></div>";
        	}
        }
		
    	return $this->_retString;
    }


}
