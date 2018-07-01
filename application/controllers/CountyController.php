<?php
class CountyController extends App_AbstractSrsController
{

    public function init()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    protected function getCountyMultiOptionsAction()
    {  
        // return
        $returnArray = array(
            array(
                'status' => 'Active',
                'options' => Model_Table_County::countyMultiOtions()
            )
        );
        $data = new Zend_Dojo_Data ('status', $returnArray);
        echo $data;
        die();
    }

    protected function getDistrictMultiOptionsAction()
    {
        if ($this->getRequest()->getParam('id_county')) {
            if($this->getRequest()->getParam('limit2privs')) {
                $returnArray = array(
                    array(
                        'id_county' => $this->getRequest()->getParam('id_county'),
                        'options' => Model_Table_District::districtMultiOtions($this->getRequest()->getParam('id_county'), true)
                    )
                );
            } else {
                $returnArray = array(
                    array(
                        'id_county' => $this->getRequest()->getParam('id_county'),
                        'options' => Model_Table_District::districtMultiOtions($this->getRequest()->getParam('id_county'))
                    )
                );
            }

        } else {
            $returnArray = array(
                array(
                    'id_county' => 0,
                    'options' => Model_Table_District::districtMultiOptionsAll()
                )
            );
        }
        $data = new Zend_Dojo_Data ('id_county', $returnArray);

        echo $data;
        die();
    }

    protected function getNonPublicDistrictMultiOptionsAction()
    {
        if ($this->getRequest()->getParam('nonpubcounty')) {
            $returnArray = array(
                array(
                    'nonpubcounty' => $this->getRequest()->getParam('nonpubcounty'),
                    'options' => Model_Table_District::getNonPublicDistricts($this->getRequest()->getParam('nonpubcounty'))
                )
            );

        } else {
            $returnArray = array();
        }
        $data = new Zend_Dojo_Data ('nonpubcounty', $returnArray);

        echo $data;
        die();
    }

    protected function getSchoolMultiOptionsAction()
    {
        if($this->getRequest()->getParam('limit2privs')) {
            $returnArray = array(
                array(
                    'id_county' => $this->getRequest()->getParam('id_county'),
                    'id_district' => $this->getRequest()->getParam('id_district'),
                    'options' => Model_Table_School::schoolMultiOtions(
                        $this->getRequest()->getParam('id_county'),
                        $this->getRequest()->getParam('id_district'),
                        true
                    )
                )
            );
        } else {
            $returnArray = array(
                array(
                    'id_county' => $this->getRequest()->getParam('id_county'),
                    'id_district' => $this->getRequest()->getParam('id_district'),
                    'options' => Model_Table_School::schoolMultiOtions(
                        $this->getRequest()->getParam('id_county'),
                        $this->getRequest()->getParam('id_district')
                    )
                )
            );
        }
        $data = new Zend_Dojo_Data ('id_district', $returnArray);

        echo $data;
        die();
    }
    protected function getNonPublicSchoolMultiOptionsAction()
    {
        $returnArray = array(
            array(
                'nonpubcounty' => $this->getRequest()->getParam('nonpubcounty'),
                'nonpubdistrict' => $this->getRequest()->getParam('nonpubdistrict'),
                'options' => Model_Table_School::getNonPublicSchools(
                    $this->getRequest()->getParam('nonpubcounty'),
                    $this->getRequest()->getParam('nonpubdistrict')
                )
            )
        );
        $data = new Zend_Dojo_Data ('nonpubdistrict', $returnArray);

        echo $data;
        die();
    }

    protected function getSchoolsAction()
    {
        $returnArray = array(
            array(
                'id_county' => $this->getRequest()->getParam('id_county'),
                'id_district' => $this->getRequest()->getParam('id_district'),
                'options' => Model_Table_School::schoolMultiOtions(
                    $this->getRequest()->getParam('id_county'),
                    $this->getRequest()->getParam('id_district')
                ),
                'schools' => Model_Table_School::getSchools(
                    $this->getRequest()->getParam('id_county'),
                    $this->getRequest()->getParam('id_district')
                )
            )
        );
        $data = new Zend_Dojo_Data ('id_district', $returnArray);

        echo $data;
        die();
    }

}