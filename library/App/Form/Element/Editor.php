<?php

class App_Form_Element_Editor extends Zend_Dojo_Form_Element_Editor
{
	var $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
	
	// custom helper used because apostrophies were not escaped 
	// and this was affecting the display of the hidden field 
	var $helper = 'EscapedEditor';
	
    public function init()
	{
		// add path for the custom view helper used above
		$this->getView()->addHelperPath('App/Dojo/View/Helper/', 'App_Dojo_View_Helper'); 
		
		$simpleDecorators = array(
            'DijitElement',
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'printEditor wordPasteStyle ') ),
        );
	
		$this->setDecorators ( $simpleDecorators);
		$this->addDecorator(array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        // ==================================================				
        $this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValue(this.id, arguments[0]);colorMeById(this.id);");
		
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());

//		$this->setAttrib('extraPlugins', "['dijit._editor.plugins.AlwaysShowToolbar', {name: 'prettyprint', indentBy: 3, lineLength: 80, entityMap: [['<', 'lt'],['>', 'gt']], xhtml: true}, 'NormalizeStyle', 'viewsource']");//, \'pastefromword\'
		$this->setAttrib('extraPlugins', "['dijit._editor.plugins.AlwaysShowToolbar', 'viewsource']");// 'normalizestyle', 'pastefromword', 'srspastefromword'
		$this->setAttrib('height', '');
        $this->setAttrib('minHeight', '10em');
		$this->setAttrib('AlwaysShowToolbar', 'true');
		$this->setDijitParam('renderAsHTML', 'true');
//		$this->setDijitParam('onEnter', "javascript:setFromHidden(this.id);dojo.byId('debugger').value +='Onclick.....' + dijit.byId(this.id).getValue();");
		$this->setAttrib('style', 'background-color:LightBlue');
		
//		$this->setAttrib('onKeyUp', 'countTextArea(this.id);');
//		$this->setAttrib('onKeyDown', 'countTextArea(this.id);');
		
		// get refresh code for externals
		// changing this code will cause clients 
		// to get fresh coppies of the external files
		$config = Zend_Registry::get('config');
		$refreshCode = '?refreshCode=' . $config->externals->refresh;
		$this->addStyleSheet('/css/dojo_editor_additional.css'.$refreshCode);
        	
        $this->addFilter(new My_Form_Filter_StringMatchHtmlBr());
        	
        // default validation - do not allow empty
        $this->setAllowEmpty(false);
        $this->setRequired(true);
        
	}
	function addJsPurify($revertLinkFlag) 
	{
		if($revertLinkFlag) {
			$this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValue(this.id, arguments[0], true, 1);colorMeById(this.id);");
		} else {
			$this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValue(this.id, arguments[0], true, 0);colorMeById(this.id);");
		}
		
	}
	
}