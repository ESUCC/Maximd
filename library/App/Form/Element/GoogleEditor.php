<?php

class App_Form_Element_GoogleEditor extends Zend_Dojo_Form_Element_Editor
{
	// custom helper used because apostrophies were not escaped 
	// and this was affecting the display of the hidden field 
	var $helper = 'EscapedEditor';
	
	public function insideTabContainer()
	{
		$this->setAttrib('height', '10em');
		$this->setAttrib('extraPlugins', "[]");
		$this->setAttrib('AlwaysShowToolbar', 'false');
		$this->setAttrib('style', 'background-color:#CC99EE'); // purple
	}
	
    public function appendAttrib($name, $value)
	{	
		$existingAttrib = $this->getAttrib($name);
		$this->setAttrib($name, $existingAttrib . $value);
	}
	
    public function init()
	{
		$this->getView()->addHelperPath('App/Dojo/View/Helper/', 'App_Dojo_View_Helper'); 
		
		$simpleDecorators = array(
            'DijitElement',
            array (array ('maindiv' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'printEditor wordPasteStyle googleEditor') ),
        );
        
		$this->setDecorators ( $simpleDecorators);
		$this->addDecorator(array ('colormediv' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-colorme') );
        // ==================================================				
		$this->setAttrib('onkeypress', "javascript:googlePasteCapture(this.id, arguments);");
		$this->setAttrib('onfocus', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);return true;");
		$this->setAttrib('onchange', "javascript:processThroughGoogleAndSave(this.id, arguments[0]);");		
		
		// we want to modify immediately but it stops the editor from expanding immediately  
//		$this->appendAttrib('onkeypressed', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);");
		
		$this->setAttrib('alt', $this->getLabel());
		$this->setAttrib('title', $this->getLabel());
		
		$this->setAttrib('plugins', "['bold', 'italic', 'underline', 'strikethrough', '|', 'insertOrderedList', 'indent', 'outdent', 'justifyLeft', 'justifyRight', 'justifyCenter', 'justifyFull']");
//		$this->setAttrib('extraPlugins', "['dijit._editor.plugins.AlwaysShowToolbar', {name: 'SpellCheck', url: '/js/dojo_development/dojo_source/dojox/editor/tests/spellCheck.php', interactive: true, timeout: 20, bufferLength: 100, lang: 'en'}]");//, 'viewsource' 'normalizestyle', 'pastefromword', 'srspastefromword'
		$sessUser = new Zend_Session_Namespace ( 'user' );
		if (1000254 == $sessUser->sessIdUser || 1010818 == $sessUser->sessIdUser) {
			$this->setAttrib('extraPlugins', "['dijit._editor.plugins.AlwaysShowToolbar', 'viewsource']");// 'normalizestyle', 'pastefromword', 'srspastefromword'
		} else {
			$this->setAttrib('extraPlugins', "['dijit._editor.plugins.AlwaysShowToolbar']");//, 'viewsource' 'normalizestyle', 'pastefromword', 'srspastefromword'
		}
		$this->setAttrib('height', '');
		$this->setAttrib('dojoType', "dijit.Editor"); // added 20110705 
        $this->setAttrib('minHeight', '5em');
		$this->setAttrib('AlwaysShowToolbar', 'true');
		$this->setDijitParam('renderAsHTML', 'true');
		$this->setDijitParam('onKeyUp', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);return true;");
//		$this->setDijitParam('onchange', "javascript:modified('', '', '', '', '', '');colorMeById(this.id);return true;");
		$this->setAttrib('style', 'background-color:#dddddd;width:800px;'); // grey
		$this->removeDijitParam('renderAsHTML', 'true');
		
		// get refresh code for externals
		// changing this code will cause clients 
		// to get fresh coppies of the external files
		$config = Zend_Registry::get('config');
		$refreshCode = '?refreshCode=' . $config->externals->refresh . strtotime('now');
		
		// add stylesheet for all browsers
		//$this->addStyleSheet('/css/dojo_editor_additional_test.css'.$refreshCode);
		
		// add stylesheet for IE browsers
// 		require_once APPLICATION_PATH.'/../library/App/Classes/Browser.php';
// 		$browser = new Browser();
// 		if( $browser->getBrowser() == Browser::BROWSER_IE ) {
// 			if( $browser->getVersion() >= 9 ) {
// 				$this->addStyleSheet('/css/dojo_editor_additional_IE9.css'.$refreshCode);
// 			} elseif( $browser->getVersion() >= 8 ) {
// 				$this->addStyleSheet('/css/dojo_editor_additional_IE8.css'.$refreshCode);
// 			}
// 		}
        	
        // default validation - do not allow empty
        $this->setAllowEmpty(false);
        $this->setRequired(true);
        
        $this->addValidator(new My_Validate_EditorEmpty($this->getName()));
        
// 		$sessUser = new Zend_Session_Namespace ( 'user' );
// 		if (1000254 == $sessUser->sessIdUser || 1010818 == $sessUser->sessIdUser) {
			// add editor history link
			$this->setDijitParam('history_link', true);		
// 		}
        
	}
	public function removeEditorEmptyValidator() {
		$this->removeValidator('My_Validate_EditorEmpty');
	}
	
	public function insideTabDecorators() {
		$simpleDecorators = array(
            'DijitElement',
            array (array ('maindiv' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'printEditor wordPasteStyle ') ),
        );
		$this->setDecorators ( $simpleDecorators);
	}
	
	function addJsPurify($revertLinkFlag) 
	{
		if($revertLinkFlag) {
			$this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValueTest(this.id, arguments[0], true, 1);colorMeById(this.id);");
		} else {
			$this->setAttrib('onchange', "javascript:modified('', '', '', '', '', '');updateInlineValueTest(this.id, arguments[0], true, 0);colorMeById(this.id);");
		}
		
	}
}