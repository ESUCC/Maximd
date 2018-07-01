<?php
class Form_NewAccountRequestDetails extends Form_AbstractForm
{

    function init()
    {
        $this->name_first = new App_Form_Element_Text('name_first', array('label' => 'Name First'));

        $this->name_middle = new App_Form_Element_Text('name_middle', array('label' => 'Name Middle'));
        $this->name_middle->noValidation();

        $this->name_last = new App_Form_Element_Text('name_last', array('label' => 'Name Last'));

        $this->address_street1 = new App_Form_Element_Text('address_street1', array('label' => 'Address Line 1'));

        $this->address_street2 = new App_Form_Element_Text('address_street2', array('label' => 'Address Line 2'));
        $this->address_street2->noValidation();

        $this->address_city = new App_Form_Element_Text('address_city', array('label' => 'City'));

        $states = array(
            'AL'=>'ALABAMA',
            'AK'=>'ALASKA',
            'AZ'=>'ARIZONA',
            'AR'=>'ARKANSAS',
            'CA'=>'CALIFORNIA',
            'CO'=>'COLORADO',
            'CT'=>'CONNECTICUT',
            'DE'=>'DELAWARE',
            'DC'=>'DISTRICT OF COLUMBIA',
            'FL'=>'FLORIDA',
            'GA'=>'GEORGIA',
            'HA'=>'HAWAII',
            'ID'=>'IDAHO',
            'IL'=>'ILLINOIS',
            'IN'=>'INDIANA',
            'IA'=>'IOWA',
            'KS'=>'KANSAS',
            'KY'=>'KENTUCKY',
            'LA'=>'LOUISIANA',
            'ME'=>'MAINE',
            'MD'=>'MARYLAND',
            'MA'=>'MASSACHUSETTS',
            'MI'=>'MICHIGAN',
            'MN'=>'MINNESOTA',
            'MS'=>'MISSISSIPPI',
            'MO'=>'MISSOURI',
            'MT'=>'MONTANA',
            'NE'=>'NEBRASKA',
            'NV'=>'NEVADA',
            'NH'=>'NEW HAMPSHIRE',
            'NJ'=>'NEW JERSEY',
            'NM'=>'NEW MEXICO',
            'NY'=>'NEW YORK',
            'NC'=>'NORTH CAROLINA',
            'ND'=>'NORTH DAKOTA',
            'OH'=>'OHIO',
            'OK'=>'OKLAHOMA',
            'OR'=>'OREGON',
            'PA'=>'PENNSYLVANIA',
            'RI'=>'RHODE ISLAND',
            'SC'=>'SOUTH CAROLINA',
            'SD'=>'SOUTH DAKOTA',
            'TN'=>'TENNESSEE',
            'TX'=>'TEXAS',
            'UT'=>'UTAH',
            'VT'=>'VERMONT',
            'VA'=>'VIRGINIA',
            'WA'=>'WASHINGTON',
            'WV'=>'WEST VIRGINIA',
            'WI'=>'WISCONSIN',
            'WY'=>'WYOMING');
        $this->address_state = new App_Form_Element_Select('address_state', array('label' => 'State'));
        $this->address_state->setMultiOptions($states);


        $this->address_zip = new App_Form_Element_Text('address_zip', array('label' => 'Zip'));
        $this->address_zip->addFilter('StringTrim');
        $this->address_zip->addValidator('regex', false, '/^([0-9]{5})(-[0-9]{4})?$/i');
        $this->address_zip->addErrorMessage('Zip code must be in the format: zip or zip+4, example: 55555 or 55555-5555');
        $this->address_zip->setDescription('zip or zip+4, example: 55555 or 55555-5555');


        $this->phone_work = new App_Form_Element_Text('phone_work', array('label' => 'Work Phone'));
        $this->phone_work->addValidator('regex', false, '/\(?\d{3}\)?[-\s.]?\d{3}[-\s.]\d{4}/x');
        $this->phone_work->addErrorMessage('Phone must be in the format: 123-222-3333');
        $this->phone_work->setDescription('include area code, example: 123-222-3333');

        $this->email_address = new App_Form_Element_Text('email_address', array('label' => 'Email'));
        $this->email_address->addValidator('EmailAddress', true);
        $this->email_address->setDescription('example: user@host.com');

        $this->email_address_confirm = new App_Form_Element_Text('email_address_confirm', array('label' => 'Confirm Email'));
        $this->email_address_confirm->addValidator('identical', true, 'email_address');
        $this->email_address_confirm->addErrorMessage('Email and Confirm Email must match. ');
        $this->email_address_confirm->setDescription('must match exactly');
        $this->email_address_confirm->setIgnore(true);

        $this->password = new App_Form_Element_Text('password', array('label' => 'Password'));
        $this->password->addValidator('stringLength', true, array(7, 99));
        $this->password->addValidator('regex', true, '/^(?=.*\d)(?=.*[a-zA-Z]).{7,99}$/');
        $this->password->setDescription('Must contain at least 7 characters with both text & numbers');
        $this->password->addErrorMessage('Must contain at least 7 characters with both text & numbers');

        $this->password_confirm = new App_Form_Element_Text('password_confirm', array('label' => 'Confirm Password'));
        $this->password_confirm->addValidator('identical', true, 'password');
        $this->password_confirm->addErrorMessage('Password and Confirm Password must match. ');
        $this->password_confirm->setIgnore(true);

        return $this;
    }

}
