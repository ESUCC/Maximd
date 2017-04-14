<?php

class App_Form_Element_TextareaEditor extends Zend_Form_Element_Textarea
{
	public $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function init()
	{
        $simpleDecorators = array(
	    	'ViewHelper',
	        array (array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'printEditor ') ),
	    );

        $this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValueTextArea(this.id, arguments[0]);colorMeById(this.id);");
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());
		$this->setAttrib('plugins', "['bold','italic','|','cut', 'copy', 'paste', '|', 'bold', 'italic', 'underline', 'strikethrough', '|', 'insertOrderedList', 'indent', 'outdent', 'justifyLeft', 'justifyRight', 'justifyCenter', 'justifyFull']");
		$this->setAttrib('extraPlugins', '[\'dijit._editor.plugins.AlwaysShowToolbar\']');
		
		$config = Zend_Registry::get('config');
		$refreshCode = '?refreshCode=' . $config->externals->refresh;
		$this->setAttrib('stylesheets', '/css/dojo_editor_additional_test.css'.$refreshCode);
		
		$this->setAttrib('height', '');
        $this->setAttrib('minHeight', '5em');
		$this->setAttrib('dojoType', "dijit.Editor");
		$this->setAttrib('AlwaysShowToolbar', 'true');

	    $this->addFilter(new My_Form_Filter_StringMatchHtmlBr());
        $this->addFilter(new My_Form_Filter_StringReplaceWordFormatting());
        
        // default validation - do not allow empty
        $this->setAllowEmpty(false);
        $this->setRequired(true);

		$this->setDecorators ( $simpleDecorators);
		$this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
	}
	public function insideTabDecorators() {
        $simpleDecorators = array(
	    	'ViewHelper',
	        array (array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'printEditor ') ),
	    );
		$this->setDecorators ( $simpleDecorators);
	}

    public function reduceTextareaSize()
    {
        $simpleDecorators = array(
            'ViewHelper',
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'printEditor smallEditor') ),
        );

        $this->setAttrib('minHeight', '5em');
        $this->setDecorators($simpleDecorators);
        $this->addDecorator('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
    }
//	function addJsPurify() 
//	{
//		$this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValueTextArea(this.id, arguments[0], true);colorMeById(this.id);");
//	}
}
