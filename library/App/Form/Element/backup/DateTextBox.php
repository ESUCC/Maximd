<?php


class App_Form_Element_DateTextBox extends Zend_Dojo_Form_Element_DateTextBox
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
    public function init()
	{
		$decorators = array(
	    	array('Description', array('tag' => 'span')),
			'DijitElement',
			array('Label', array('tag' => 'span')),
	    	);
		
		$this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setFormatLength('short');
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('style', "width:120px;");
        $this->setAttrib('title', $this->getLabel());
        $this->setAttrib('dojoType', 'dijit.form.DateTextBox');
        $this->setDecorators($decorators);
		$this->setInvalidMessage('Invalid date specified');
		$this->addDecorator(array('colorme'=>'HtmlTag'), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme'));
		$this->setAllowEmpty(false);
		$this->setRequired(true);
	}

    public function boldLabelPrint()
    {
        $decorators = array(
            array('Description', array('tag' => 'span')),
            'DijitElement',
            array('Label', array('tag' => 'span', 'class' => 'printBoldLabel')),
            );
        
        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setFormatLength('short');
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('style', "width:120px;");
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators($decorators);
        $this->setInvalidMessage('Invalid date specified');
        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }
    
	public function boldLabel()
    {
        $decorators = array(
            array('Description', array('tag' => 'span')),
            'DijitElement',
            array('Label', array('tag' => 'span', 'class' => 'boldLabel')),
            );
        
        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setFormatLength('short');
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('style', "width:120px;");
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators($decorators);
        $this->setInvalidMessage('Invalid date specified');
        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        $this->setAllowEmpty(false);
        $this->setRequired(true);
    }

    public function noBoldLabelPrintIndent()
    {
        $decorators = array(
            array('Description', array('tag' => 'span')),
            'DijitElement',
            array('Label', array('tag' => 'span', 'class' => 'srsLabelNoBold')),
            );

        $this->setAttrib('onchange', $this->JSmodifiedCode . "colorMeById(this.id);");
        $this->setFormatLength('short');
        $this->setAttrib('alt', $this->getLabel());
        $this->setAttrib('style', "width:120px;");
        $this->setAttrib('title', $this->getLabel());
        $this->setDecorators($decorators);
        $this->setInvalidMessage('Invalid date specified');
        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
    }

	function noValidation()
	{
		parent::clearValidators();
		$this->setAllowEmpty(true);
		$this->setRequired(false);  
	}
	/*
	 * wade requested that dates be printed/viewed in mm/dd/YYYY format
	 * this should override all form dates created with this class
	 * bug - form is unaware of view/edit/print mode and this code
	 * causes an error in the dateTextBox in edit mode
	 */
//	function __toString() {
//        try {
//            $date = new Zend_Date($this->render());
//            return $date->toString(Zend_Date::MONTH . '/' . Zend_Date::DAY . '/' . Zend_Date::YEAR);
//        } catch (Exception $e) {
//            //trigger_error($e->getMessage(), E_USER_WARNING);
//            return '';
//        }
//	}

	static function humanReadableDate($inputDate) {
        try {
            $date = new Zend_Date($inputDate, Zend_Date::YEAR . '-' . Zend_Date::MONTH . '-' . Zend_Date::DAY);
            return $date->toString(Zend_Date::MONTH . '/' . Zend_Date::DAY . '/' . Zend_Date::YEAR);
        } catch (Exception $e) {
            //trigger_error($e->getMessage(), E_USER_WARNING);
            return '';
        }
	}
	
}

