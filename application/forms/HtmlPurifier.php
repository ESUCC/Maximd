<?php

class Form_HtmlPurifier extends Form_AbstractForm {
	
	public function example() {
		
//		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'Htmlpurifier/textworkshopForm.phtml' ) ) ) );
		//
        
        $this->paste_in = new App_Form_Element_TextareaEditor('paste_in', array('label'=>'Paste In'));
        $this->paste_in->setAttrib('height', '350px');
        
        $this->processed = new App_Form_Element_TextareaEditor('processed', array('label'=>'Processed'));
        $this->processed->setAttrib('height', '350px');

        $this->paste_html = new Zend_Form_Element_Textarea('paste_html', array('label'=>'Paste Html'));
        $this->paste_html->setAttrib('height', '250px');
        $this->paste_html->setAttrib('valign', 'top');
        $this->paste_html->setAttrib('cols', '45');
        
        $this->processed_html = new Zend_Form_Element_Textarea('processed_html', array('label'=>'Processed Html'));
        $this->processed_html->setAttrib('height', '250px');
        $this->processed_html->setAttrib('valign', 'top');
        $this->processed_html->setAttrib('cols', '45');
        $this->processed_html->setAttrib('onchange', "javascript:dojo.byId('finaldisplay').innerHTML=this.value;");
        
        
        $this->option_purifier = new App_Form_Element_Radio('option_purifier', array('label'=>'Use htmlPurifier', 'multiOptions'=>array('off'=>'(Allow styles - htmlPurifier NOT used)', 'on'=>'(Do NOT allow stlyes - html purifier IS used)')));
        
        
        return $this;			
	}
	
}
