<?php
/**
 * User: jlavere
 */
class App_ListManager_Student extends App_ListManagerAbstract
{
    public function __construct() {
        $this->parentLabel = 'Student';
        $this->parentModelName = 'Model_Table_Student';
        $this->parentKeyName = 'id_student';

//        $this->childLabel = 'Student Contact';
//        $this->childModelName = 'Model_Table_StudentContact';
//        $this->formName = 'Form_StudentContact';
//        $this->childKeyName = 'id_student_contact';
    }

//    public function preRequest() {
//        parent::preAdd();
//    }
//
//    public function prePost() {
//        parent::prePost();
//    }

    public function preAdd() {
        if($this->form->address_state->setValue('NE'));
        if ('jessexan' == APPLICATION_ENV) {
            $this->form->name->setValue('jesse lavere');
            $this->form->position->setValue('Manager');
            $this->form->agency->setValue('FBI');
            $this->form->phone_office->setValue('565-555-5454');
            $this->form->phone_mobile->setValue('565-321-5454');
            $this->form->phone_fax->setValue('565-432-5454');
            $this->form->address_street->setValue('123 any st');
            $this->form->address_city->setValue('paranormal');
            $this->form->address_state->setValue('NE');
            $this->form->address_zip->setValue('54354');
        }
        parent::preAdd();
    }


}