<?php

class My_Form_IepDistrictView extends Zend_Form
{

    public $formDecorators = array(
        array(
            'FormElement'
        ),
        array(
            'Form'
        )
    );
   
    public $elementDecoratorsChk = array( 
     
        'ViewHelper',
        'Errors',
        array(
            'HtmlTag',
            array(
                'tag' => 'br'
            )
        ),
        array(
            'Label',
            array(
                'tag' => 'b'
            )
        )
    );
    
    public $elementDecorators = array(
        array(
            'ViewHelper'
        ),
        array(
            'Label'
        ),
        array(
            'Errors'
        )
    );

    public $buttondecorators = array(
        array(
            'ViewHelper'
        ),
        array(
            'HtmlTab',
            array(
                'tag' => 'p'
            )
        )
    );

    public function init()
    {
        $this->setName('name_district');
        
        $name_district = new Zend_Form_Element_Text('name_district');
        $name_district->setLabel('District Name')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->
        setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $id_district = new Zend_Form_Element_Text('id_district');
        $id_district->setLabel('District Id')
            ->
        // ->$id_district->addFilter('Int')
        addValidator('NotEmpty')
            ->addValidator('Digits')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
                
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $id_county = new Zend_Form_Element_Hidden('id_county');
        
        $phone_main = new Zend_Form_Element_Text('phone_main');
        $phone_main->setLabel('Phone')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
                'ViewHelper',
                'Errors',
                 
                array(
                    'Label',
                    array(
                        'tag' => 'b'
                    )
                )
            ));
        
        $address_street1 = new Zend_Form_Element_Text('address_street1');
        $address_street1->setLabel('Address')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
                'ViewHelper',
                'Errors',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'br'
                    )
                ),
                array(
                    'Label',
                    array(
                        'tag' => 'b'
                    )
                )
            ));
            
        
       
        
        $status = new Zend_Form_Element_Text('status');
        $status->setLabel('Status')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
       $id_district_mgr = new Zend_Form_Element_Hidden('id_district_mgr');
       $id_district_mgr_name = new Zend_Form_Element_Text('id_district_mgr_name');
        $id_district_mgr_name->setLabel('District Manager')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array('Label',array('tag' => 'b'))));
      
        
           
        $id_account_sprv = new Zend_Form_Element_Hidden('id_account_sprv');
        $id_account_sprv_name = new Zend_Form_Element_Text('id_account_sprv_name');
        $id_account_sprv_name->setLabel('Account Supervisors')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
      
        $address_street2 = new Zend_Form_Element_Text('address_street2');
        $address_street2->setLabel('Address 2')
        ->setRequired(false)
        ->addFilter('StripTags')
        ->addFilter('StringTrim')
        ->addValidator('NotEmpty')
        ->setDecorators($this->elementDecorators)
        ->addDecorators(array(
            'ViewHelper',
            'Errors',
            
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
        $address_city = new Zend_Form_Element_Text('address_city');
        $address_city->setLabel('City')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
                'ViewHelper',
                'Errors',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'br'
                    )
                ),
                array(
                    'Label',
                    array(
                        'tag' => 'b'
                    )
                )
            ));
        
        $address_state = new Zend_Form_Element_Text('address_state');
        $address_state->setLabel('State')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
                'ViewHelper',
                'Errors',
                
                array(
                    'Label',
                    array(
                        'tag' => 'b'
                    )
                )
            ));
        
        $address_zip = new Zend_Form_Element_Text('address_zip');
        $address_zip->setLabel('Zip Code')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
                'ViewHelper',
                'Errors',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'br'
                    )
                ),
                array(
                    'Label',
                    array(
                        'tag' => 'b'
                    )
                )
            ));
        
        $logo_flag = new Zend_Form_Element_Hidden('logo_flag');
        
        
       
        
        $fedrep_email = new Zend_Form_Element_Text('fedrep_email');
        $fedrep_email->setLabel('Fed Rep Email')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            array(
                'HtmlTag',
                array(
                    'tag' => 'br'
                )
            ),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
       
     
        
    
        
      
        
        
        $email_nssrs = new Zend_Form_Element_Text('email_nssrs');
        $email_nssrs->setLabel('NSSRS Email')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(false)
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
       
      
        
        $email_student_transfers_to = new Zend_Form_Element_Hidden('email_student_transfers_to');
        $email_student_transfers_to_name = new Zend_Form_Element_Text('email_student_transfers_to_name');
        $email_student_transfers_to_name->setLabel('Email Transfers To:')
            ->setRequired(false)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setDecorators($this->elementDecorators)
            ->addDecorators(array(
            'ViewHelper',
            'Errors',
            // array('HtmlTag',array('tag'=>'br')),
            array(
                'Label',
                array(
                    'tag' => 'b'
                )
            )
        ));
        
       
      
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('name_district', 'submitbutton');
        
        $this->addElements(array(
            $name_district,
            $id_district,
            $id_county,
            $phone_main,
            $address_street1,
            $status,
            $id_district_mgr,
            $id_account_sprv,
            $id_account_sprv_name,
           
            $email_student_transfers_to,
            $address_street2,
            $address_city,
            $address_state,
            $address_zip,
            $logo_flag,
            $fedrep_email,
            
            $id_district_mgr_name,
            $email_student_transfers_to_name,
           
            $submit
        ));
        
        $districtDemo = "DIST";
        
        $this->addDisplayGroup(array(
            'name_district',
            'id_district',
            'status',
            'id_district_mgr',
            'id_district_mgr_name',
            'id_account_sprv',
            'id_account_sprv_name',
            'email_student_transfers_to',
            'email_student_transfers_to_name',
            'address_street1',
            'address_street2',
            'address_city',
            'address_state',
            'address_zip',
            'phone_main',
            'id_county',
            'fedrep_email',
            'submit'
        ), $districtDemo);
        $this->getDisplayGroup($districtDemo)
            ->setLegend('District Demographics To Change')
            ->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(
                'HtmlTag',
                array(
                    'tag' => 'div',
                    'openOnly' => false,
                    'style' => 'width:4%;;float:left'
                )
            )
        ));
 
    }
}

