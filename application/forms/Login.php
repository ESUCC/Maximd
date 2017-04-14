<?php

class Form_Login extends Form_AbstractForm {

	function choose() {
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'login/chooseForm.phtml' ) ) ) );
		
		$options = array(
			"I'm a registered User and would like to log on.",
			"I'm a registered User and would like a New Role/Privilege.",
			"I'm a registered Parent and would like to log on.",
			"I'm just getting started and would like to submit a New Account Request.",
		);
		$this->login_type = new App_Form_Element_Radio('login_type',array('label'=>"Please choose one of the following options:"));
		$this->login_type->setMultiOptions(array_combine(array_values($options), array_values($options)));
		$this->login_type->addErrorMessage('You must select one of the above options before you can continue.');
		$this->login_type->setSeparator("<BR />");
		$this->login_type->removeDecorator('Label');
		$this->login_type->setAttrib('onchange', '');
		$this->login_type->setAttrib('onFocus', '');
		$this->login_type->addDecorator('Errors');
		
		$this->submit = new App_Form_Element_Submit('submit',array('label'=>"Continue"));
		$this->submit->removeDecorator('Label');
		
		return $this;
	}

	function userLogin() {
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'loginForm.phtml' ) ) ) );
		
		$this->email = new App_Form_Element_Text('email',array('label'=>"Username:"));
		$this->email->addDecorator('Errors');
		$this->email->setAttrib('onchange', null);
		$this->email->setAttrib('onfocus', null);
//		$this->email->addValidator('stringLength', false, array(3, 40));
		
		$this->password = new App_Form_Element_Password('password',array('label'=>"Password:"));
		$this->password->addDecorator('Errors');
		$this->password->setAttrib('onchange', null);
		$this->password->setAttrib('onfocus', null);
//		$this->password->addValidator('stringLength', false, array(6));
		
		$this->submit = new App_Form_Element_Submit('submit',array('label'=>"Continue"));
		$this->submit->setAttrib('onchange', null);
		$this->submit->setAttrib('onfocus', null);
		$this->submit->removeDecorator('Label');
		
		$this->agree = new App_Form_Element_Checkbox('agree',array('label'=>"I have read and agree to abide by the FERPA statement."));
		$this->agree->addValidator(new My_Validate_BooleanTrue());  
		$this->agree->setAllowEmpty(false);  
		$this->agree->addDecorator('Errors');
		$this->agree->setAttrib('onchange', null);
		$this->agree->setAttrib('onfocus', null);
		
		
		return $this;
	}

	function parentLogin() {
		
		$this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'login/parentloginForm.phtml' ) ) ) );
		
		$this->email = new App_Form_Element_Text('email',array('label'=>"Username:"));
		$this->email->addDecorator('Errors');
		$this->email->addValidator('stringLength', false, array(5, 40));
		
		$this->password = new App_Form_Element_Password('password',array('label'=>"Password:"));
		$this->password->addDecorator('Errors');
		$this->password->addValidator('stringLength', false, array(6));
		
		$this->submit = new App_Form_Element_Submit('submit',array('label'=>"Continue"));
		$this->submit->removeDecorator('Label');
		
		$this->agree = new App_Form_Element_Checkbox('agree',array('label'=>"I have read and agree to abide by the FERPA statement."));
		$this->agree->addValidator(new My_Validate_BooleanTrue());  
		$this->agree->setAllowEmpty(false);  
		$this->agree->addDecorator('Errors');
		
		
		return $this;
	}
}
