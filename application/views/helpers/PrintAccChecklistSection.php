<?php
/**
 * Helper for displaying a subform 
 * 
 * @uses      Zend_View_Helper_Abstract
 * @package   SRS
 * @author    Jesse LaVere <mdanahy@esucc.org> 
 * @version   $Id: $
 */
class Zend_View_Helper_PrintAccChecklistSection extends Zend_View_Helper_Abstract
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
    public function printAccChecklistSection($form, $fieldListArray)
    {
    	$this->_retString = "";
    	$listData = "";
    	$output = false;
    	
    	$categoryArr = array();
    	
    	foreach($fieldListArray as $elementName)
    	{
            if('App_Form_Element_MultiSelect' == $form->element->$elementName->getType() && is_array($form->element->$elementName->getValue())) {
                // multi-select
                foreach($form->element->$elementName->getValue() as $value) {
                    $multiLabel = $form->element->$elementName->getMultiOption($value);
                    $categoryArr[$multiLabel][] = "<li>".$this->view->translate($form->element->$elementName->getDescription())." - " . $form->element->$elementName->getLabel() ."</li>";
                    $output = true;
                }
            } elseif('App_Form_Element_MultiSelect' == $form->element->$elementName->getType()) {
                if($form->element->$elementName->getValue() != '') {
                    $multiLabel = $form->element->$elementName->getMultiOption($form->element->$elementName->getValue());
                    $categoryArr[$multiLabel][] = "<li>".$this->view->translate($form->element->$elementName->getDescription())." - " . $form->element->$elementName->getLabel() ."</li>";
                    $output = true;
                }
            } else {
                // regular select
                if($form->element->$elementName->getValue() != '') {
                    $categoryArr[$form->element->$elementName->getValue()][] = "<li>".$this->view->translate($form->element->$elementName->getDescription())." - " . $form->element->$elementName->getLabel() ."</li>";
                    $output = true;
                }
            }
    	}

        /**
         * categories with ' ' are not selected
         * remove them from the print array
         */
        unset($categoryArr[' ']);

        if(count($categoryArr) > 0)
        {
        	foreach($categoryArr as $category => $items)
        	{
//        		Zend_Debug::dump($category, 'category');
                $this->_retString .= "<div style=\"page-break-inside: avoid;\">";
                $this->_retString .= "<b>".$this->view->translate($category)."</B><BR>";
                $this->_retString .= "<ul class=\"bullet\">";
                foreach($items as $catItem)
                {
                	$this->_retString .= $catItem;
                }
                $this->_retString .= "</ul></div>";
        	}
        }
    	
//		if($output)
//		{
//	    	$this->_retString .= "<div style=\"page-break-inside: avoid;\">";
//	        $this->_retString .= "<b>".$form->element->$elementName->getDescription()."</B><BR>";
//	        $this->_retString .= "<ul class=\"bullet\">";
//	        $this->_retString .= $listData;
//	        $this->_retString .= "</ul></div>";
//		}
		
    	return $this->_retString;
    }


}
