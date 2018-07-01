<?php
/**
 * User: jlavere
 * Date: 2/05/13
 */
class App_ListManager_PersonnelPrivilege extends App_ListManagerAbstract
{
    public function __construct() {
        // parent
        $this->parentLabel = 'Personnel';
        $this->parentModelName = 'Model_Table_Personnel';
        $this->parentKeyName = 'id_personnel';

        // child table
        $this->childLabel = 'Personnel Privileges';
        $this->childModelName = 'Model_Table_IepPrivileges';
        $this->formName = 'Form_PersonnelPrivileges';
        $this->childKeyName = 'id_privileges';
        $this->childFindFunctionName = 'getPrivilege';


    }

//    public function preRequest() {
//        parent::preAdd();
//    }
//
//    public function prePost() {
//        parent::prePost();
//    }

    public function preAdd() {
        if ('jessexan' == APPLICATION_ENV) {
//            $this->form->name->setValue('jesse lavere');
//            $this->form->position->setValue('Manager');
//            $this->form->agency->setValue('FBI');
//            $this->form->phone_office->setValue('565-555-5454');
//            $this->form->phone_mobile->setValue('565-321-5454');
//            $this->form->phone_fax->setValue('565-432-5454');
//            $this->form->address_street->setValue('123 any st');
//            $this->form->address_city->setValue('paranormal');
//            $this->form->address_state->setValue('NE');
//            $this->form->address_zip->setValue('54354');
        }
        parent::preAdd();
    }


}