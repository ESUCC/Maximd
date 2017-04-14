<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jlavere
 * Date: 1/31/13
 * Time: 1:05 PM
 * To change this template use File | Settings | File Templates.
 */
class App_ListManager_StudentHistory extends App_ListManagerAbstract
{
    public $childFindFunctionName = 'findDetail';

    public function __construct() {
        $this->parentLabel = 'Student';
        $this->parentModelName = 'Model_Table_Student';
        $this->parentKeyName = 'id_student';

        $this->childLabel = 'Student History';
        $this->childModelName = 'Model_Table_StudentHistory';
        $this->formName = 'Form_StudentHistory';
        $this->childKeyName = 'id_student_history';
    }

    public function preRequest() {

        /**
         * build county, district, school selects
         */
        if (strlen($this->childRecord->id_county) < 2) {
            $this->form->id_county->addMultiOption('', 'Choose a county...');
        }
        if (strlen($this->childRecord->id_district) < 4) {
            $this->form->id_district->addMultiOption('', 'Choose a district...');
        }
        if (strlen($this->childRecord->id_school) < 3) {
            $this->form->id_school->addMultiOption('', 'Choose a school...');
        }
        $this->form->id_county->addMultiOptions(Model_Table_County::countyMultiOtions());
        if (strlen($this->childRecord->id_county) == 2) {
            $this->form->id_district->addMultiOptions(Model_Table_District::districtMultiOtions($this->childRecord->id_county));
            if (strlen($this->childRecord->id_district) == 4) {
                $this->form->id_school->addMultiOptions(
                    Model_Table_School::schoolMultiOtions($this->childRecord->id_county, $this->childRecord->id_district));
            }
        }
        parent::preRequest();
    }

    public function prePost() {
        /**
         * build county, district, school selects
         */
        if (strlen($this->data['id_county']) < 2) {
            $this->form->id_county->addMultiOption('', 'Choose a county...');
        }
        if (strlen($this->data['id_district']) < 4) {
            $this->form->id_district->addMultiOption('', 'Choose a district...');
        }
        if (strlen($this->data['id_school']) < 3) {
            $this->form->id_school->addMultiOption('', 'Choose a school...');
        }
        $this->form->id_county->addMultiOptions(Model_Table_County::countyMultiOtions());
        if (strlen($this->data['id_county']) == 2) {
            $this->form->id_district->addMultiOptions(Model_Table_District::districtMultiOtions($this->data['id_county']));
            if (strlen($this->data['id_district']) == 4) {
                $this->form->id_school->addMultiOptions(
                    Model_Table_School::schoolMultiOtions($this->data['id_county'], $this->data['id_district']));
            }
        }
        parent::prePost();
    }

    public function preAdd() {
        /**
         * build county, district, school defaults
         */
        $this->form->id_county->addMultiOption('', 'Choose a county...');
        $this->form->id_district->addMultiOption('', 'Choose a district...');
        $this->form->id_school->addMultiOption('', 'Choose a school...');
        $this->form->id_county->addMultiOptions(Model_Table_County::countyMultiOtions());

        if ($this->isPost) {
            $this->form->populate($this->data);
            if (isset($this->data['id_county']) && strlen($this->data['id_county']) == 2) {
                $this->form->id_district->addMultiOptions(Model_Table_District::districtMultiOtions($this->data['id_county']));
                if (isset($this->data['id_district']) && strlen($this->data['id_district']) == 4) {
                    $this->form->id_school->addMultiOptions(
                        Model_Table_School::schoolMultiOtions($this->data['id_county'], $this->data['id_district']));
                }
            }
        }

        if ('jessexan' == APPLICATION_ENV) {
            $this->form->id_county->setValue('99');
            $this->form->id_district->setValue('9999');
            $this->form->id_school->setValue('999');
            $this->form->id_district->addMultiOptions(Model_Table_District::districtMultiOtions('99'));
            $this->form->id_school->addMultiOptions(Model_Table_School::schoolMultiOtions('99', '9999'));
            $this->form->start_date->setValue('1/1/2001');
            $this->form->end_date->setValue('10/1/2010');
        }

        parent::preAdd();

    }


}