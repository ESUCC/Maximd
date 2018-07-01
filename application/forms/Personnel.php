<?php
class Form_Personnel extends Form_AbstractForm {
	

	public function init() {
        $this->setMethod ( 'post' );
        $this->setAttrib( 'class', $this->getAttrib( 'class' ) . ' zend_form' );

        /**
         * navigation buttons
         */
        $this->submit = new App_Form_Element_Submit('submit', array('label' => 'Save'));
        $this->submit->removeDecorator('label');
        $this->submit->setAllowEmpty(true);
        $this->submit->setRequired(false);

        $this->cancel = new App_Form_Element_Submit('cancel', array('label' => 'Cancel'));
        $this->cancel->removeDecorator('label');
        $this->cancel->setAllowEmpty(true);
        $this->cancel->setRequired(false);
        $this->addDisplayGroup(array(
                $this->cancel,
                $this->submit,
            ), 'navigation1');
        $this->getDisplayGroup('navigation1')->setAttrib('style', 'text-align:right;');
        $this->getDisplayGroup('navigation1')->setAttrib('class', 'navigation');


        $this->name_first = new App_Form_Element_Text('name_first', array('label' => 'First Name *:'));

        $this->name_middle = new App_Form_Element_Text('name_middle', array('label' => 'Middle Name:'));
        $this->name_middle->setAllowEmpty(true);
        $this->name_middle->setRequired(false);

        $this->name_last = new App_Form_Element_Text('name_last', array('label' => 'Name Last *:'));

        $this->id_personnel = new App_Form_Element_Text('id_personnel', array('label' => 'Personnel ID:'));
        $this->id_personnel->setAttrib('readonly', 'readonly');
        $this->id_personnel->setIgnore(true);
        $this->id_personnel->setAllowEmpty(true);
        $this->id_personnel->setRequired(false);

        $this->status = new App_Form_Element_Select('status', array('label' => 'Status:'));
        $this->status->setMultiOptions(array('Active'=>'Active', 'Inactive'=>'Inactive', 'Removed'=>'Removed'));
        $this->status->getDecorator('label')->setOption('class', 'srsLabel');

//        $this->address_street1 = new App_Form_Element_Text('address_street1', array('label' => 'Street 1 *:'));
//
//        $this->address_street2 = new App_Form_Element_Text('address_street2', array('label' => 'Street 2:'));
//        $this->address_street2->setAllowEmpty(true);
//        $this->address_street2->setRequired(false);
//
//        $this->address_city = new App_Form_Element_Text('address_city', array('label' => 'City *:'));
//
//        $this->address_state = new App_Form_Element_Select('address_state', array('label' => 'State *:'));
//        $this->address_state->setMultiOptions($this->states);
//        $this->address_state->getDecorator('label')->setOption('class', 'srsLabel');
//
//        $this->address_zip = new App_Form_Element_Text('address_zip', array('label' => 'Zip *:'));
//        $this->address_zip->setDescription('zip or zip+4, example: 55555 or 55555-5555');
//        $postalCode = new Zend_Validate_PostCode('en_US');
//        $this->address_zip->addValidator($postalCode);


        $this->phone_work = new App_Form_Element_Text('phone_work', array('label' => 'Work Phone *:'));
        $this->phone_work->setDescription('include area code, example: 123-222-3333');
        $this->phone_work->addErrorMessage('Please enter a 7 or 10 digit phone number.');
        $validator = new Zend_Validate_Regex(array('pattern' => '/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/'));
        $this->phone_work->addValidator($validator);

        $this->email_address = new App_Form_Element_Text('email_address', array('label' => 'Email *:'));
        $this->email_address->setDescription('example: user@host.com');
//        $this->email_address->setAttrib('readonly', 'readonly');
        $this->email_address->setAllowEmpty(true);
        $this->email_address->setRequired(false);
//        $this->email_address->setIgnore(true);

//        $this->update_email_address = new App_Form_Element_Text('update_email_address', array('label'=>'Update Email Address'));
//        $this->update_email_address->setDescription('Enter a new address here and a confirmation email will be sent to that address to complete the process.');
//        $this->update_email_address->addDecorator('errors');
//        $this->update_email_address->addValidator('EmailAddress');
//        $this->update_email_address->setAllowEmpty(true);
//        $this->update_email_address->setRequired(false);

        $this->agency = new App_Form_Element_Text('agency', array('label' => 'Agency:'));
        $this->agency->setAllowEmpty(true);
        $this->agency->setRequired(false);

        $this->pref_student_search_location = new App_Form_Element_Radio('pref_student_search_location', array('label' => 'Use student search:'));
        $this->pref_student_search_location->setAllowEmpty(true);
        $this->pref_student_search_location->setRequired(false);
        $this->pref_student_search_location->getDecorator('label')->setOption('placement', 'prepend');
        $this->pref_student_search_location->setMultiOptions(array(
                'srs' => 'Original',
                'srs-zf' => 'New'
            ));

//        $this->pref_student_form_center_location = new App_Form_Element_Radio('pref_student_form_center_location', array('label' => 'Use form center search:'));
//        $this->pref_student_form_center_location->setAllowEmpty(true);
//        $this->pref_student_form_center_location->setRequired(false);
//        $this->pref_student_form_center_location->getDecorator('label')->setOption('placement', 'prepend');
//        $this->pref_student_form_center_location->setMultiOptions(array(
//                'srs' => 'Original',
//                'srs-zf' => 'New'
//            ));

        $this->addDisplayGroup(array(
            $this->name_first,
            $this->name_middle,
            $this->name_last,
            $this->id_personnel,
            $this->status,
//            $this->address_street1,
//            $this->address_street2,
//            $this->address_city,
//            $this->address_state,
//            $this->address_zip,
            $this->phone_work,
            $this->email_address,
            $this->update_email_address,
            $this->agency,
            $this->pref_student_search_location,
//            $this->pref_student_form_center_location,
        ), 'main', array('legend'=>'Personnel Information'));


        $btn = new App_Form_Element_Button('send_login_info', array('label' => 'Send Login Info'));
        $this->user_name = new App_Form_Element_Text('user_name', array('label' => 'User Name:', 'description'=>$btn.' <span id="send-login-info-message"></span>'));
        $this->user_name->getDecorator('description')->setOption('escape', false);
        $this->user_name->setAttrib('readonly', 'readonly');
        $this->user_name->setAllowEmpty(true);
        $this->user_name->setRequired(false);
        $this->user_name->setIgnore(true);

        $access = array(
            ''=>'Choose...',
//            'Enabled'=>'Enabled',
//            'Disabled'=>'Disabled',
            'Reset'=>'Reset',
        );
        
        
        
        
        
        $this->online_access = new App_Form_Element_Select('online_access', array('label' => 'Reset Password:',));
        $this->online_access->setMultiOptions($access);
        $this->online_access->addDecorator('description', array('tag' => 'span'));
        $this->online_access->setDescription('Choose Reset, then Save to generate a new temporary password.');
        $this->online_access->getDecorator('label')->setOption('class', 'srsLabel');
        $this->online_access->setAllowEmpty(true);
        $this->online_access->setRequired(false);

        $this->temp_password = new App_Form_Element_Text('temp_password',array('label' => 'Temp Password:'));
        $this->temp_password->setAttrib('readonly', 'readonly');
        $this->temp_password->setAllowEmpty(true);
        $this->temp_password->setRequired(false);

        $this->date_expiration = new App_Form_Element_DatePicker('date_expiration', array('label' => 'Access Expires:'));
        $this->date_expiration->removeDecorator('description');
        $this->date_expiration->addDecorator('description', array('tag' => 'span'));
        $this->date_expiration->setDescription('Access expiration date, leave blank if account doesn’t expire.');
        $this->date_expiration->getDecorator('description')->setOption('placement', 'append');
        $this->date_expiration->getDecorator('label')->setOption('class', 'srsLabel');
        $this->date_expiration->setAllowEmpty(true);
        $this->date_expiration->setRequired(false);
        $this->date_expiration->addFilter(new Zend_Filter_Null());
        $this->date_expiration->addDecorator(array ('data' => 'HtmlTag' ), array ('tag' => 'span'));

        $this->date_last_pw_change = new App_Form_Element_DatePicker('date_last_pw_change', array('label' => 'Password Changed:'));
        $this->date_last_pw_change->setIgnore(true);
        $this->date_last_pw_change->getDecorator('label')->setOption('class', 'srsLabel');
        $this->date_last_pw_change->setAttrib('readonly', 'readonly');
        $this->date_last_pw_change->setAllowEmpty(true);
        $this->date_last_pw_change->setRequired(false);

        $this->addDisplayGroup(array(
            $this->user_name,
            $this->online_access,
            $this->temp_password,
            $this->send_login_info,
            $this->date_expiration,
            $this->date_last_pw_change,
        ), 'onlineaccess', array('legend'=>'Online Access'));


        /**
         * put each element on it's own line
         */
        $this->wrapWithDivs($this);


        $this->addDisplayGroup(array(
                $this->cancel,
                $this->submit,
            ), 'navigation2');

        $this->getDisplayGroup('navigation2')->setAttrib('style', 'text-align:right;');
        $this->getDisplayGroup('navigation2')->setAttrib('class', 'navigation');

        $this->removeSrsFormHelpers($this);
        $this->addErrorDecorator($this);
//        $this->addAstriskToRequired($this);
        return $this;

    }

}