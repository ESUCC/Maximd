<?php

class Form_Form013Parents extends Form_AbstractForm
{

    private $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";

    public function ifsp_parents_edit_version10()
    {
        return $this->ifsp_parents_edit_version1();
    }
    
    public function ifsp_parents_edit_version9()
    {
        return $this->ifsp_parents_edit_version1();
    }

    public function ifsp_parents_edit_version1()
    {

        $this->setDecorators(
            array(array('ViewScript', array('viewScript' => 'form013/ifsp_parents_edit_version1.phtml')))
        );
        //
        // these fields are currenly being used to 
        // help build other optional parts of the form
        // they exist so that we can access data that is populated into the form
        //
        $this->rownumber = new App_Form_Element_Hidden('rownumber');
        $this->rownumber->ignore = true;

        $this->rownumber = new App_Form_Element_Hidden('dob');
        $this->rownumber->ignore = true;


        //
        // named displayed in validation output
        //
        $this->subform_label = new App_Form_Element_Hidden('subform_label ');
        $this->subform_label->ignore = true;
        $this->subform_label->setLabel("Parent/Guardian Row");

        $this->remove_row = new App_Form_Element_Checkbox('remove_row', array('label' => 'Check to remove'));
        $this->remove_row->setDecorators(My_Classes_Decorators::$labelDecorators);
        $this->remove_row->ignore = true;

        $this->id_ifsp_parents = new App_Form_Element_Hidden('id_ifsp_parents', array('label' => ''));

        // visible fields
        $this->pg_name = new App_Form_Element_Text('pg_name', array('label' => 'Name'));
        $this->pg_name->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->pg_name->setAttrib('size', '15');
        $this->pg_name->addErrorMessage("Parent/Guardian Name must be entered.");

        $multiOptions = App_Form_ValueListHelper::parentGuardian();
        $this->pg_role = new App_Form_Element_Select('pg_role', array(
            'label' => 'Role',
            'multiOptions' => $multiOptions
        ));
        $this->pg_role->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->pg_role->addErrorMessage("Parent/Guardian Role must be entered.");

        $this->pg_home_phone = new App_Form_Element_Text('pg_home_phone', array('label' => 'Home Phone'));
        $this->pg_home_phone->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->pg_home_phone->addValidator('regex', false, array('/^[2-9][0-9]{2}-[1-9][0-9]{2}-[0-9]{4}$/'));
        $this->pg_home_phone->setAttrib('size', '10');
        $this->pg_home_phone->setRequired(false);
        $this->pg_home_phone->setAllowEmpty(true);
        $this->pg_home_phone->addErrorMessage("You must enter a valid Home Phone, example: 111-222-3333.");

        $this->pg_work_phone = new App_Form_Element_Text('pg_work_phone', array('label' => 'Work Phone'));
        $this->pg_work_phone->setDecorators(App_Form_DecoratorHelper::inlineElement());
        $this->pg_work_phone->addValidator('regex', false, array('/^[2-9][0-9]{2}-[1-9][0-9]{2}-[0-9]{4}$/'));
        $this->pg_work_phone->setAttrib('size', '10');
        $this->pg_work_phone->addErrorMessage("You must enter a valid Work Phone, example: 111-222-3333.");
        $this->pg_work_phone->setRequired(false);
        $this->pg_work_phone->setAllowEmpty(true);

        $this->pg_address = new App_Form_Element_TextareaPlain('pg_address', array('label' => 'Address'));
        $this->pg_address->setAttrib('rows', 3);
        $this->pg_address->setAttrib('cols', 40);
        $this->pg_address->addErrorMessage("Parent/Guardian Address must be entered.");

        return $this;
    }


}

