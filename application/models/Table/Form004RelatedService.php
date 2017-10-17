<?php

class Model_Table_Form004RelatedService extends Model_Table_AbstractIepForm {
	
	protected $_name = 'form_004_related_service';
    protected $_primary = 'id_form_004_related_service';
	
    protected $_referenceMap    = array(
        'Model_Table_Form004' => array(
            'columns'           => array('id_form_004'),
            'refTableClass'     => 'Model_Table_Form004',
            'refColumns'        => array('id_form_004')
        )
    );
  
    // Mike added these functions in order to get the csv to work 10-17-2017
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    function getRelatedServicesStateForCsv($id_form004){
        $sql="select * from form_004_related_service where id_form_004='$id_form004' and status='Active'";
        $forms=$this->db->fetchAll($sql);
        // $result=null;
         
        if($forms!=null){
            return $forms;
        } // end of ifset
        else {
            return null;
        }
    }
    
    
    
    function getRelatedServicesState($id_form004){
        $sql="select * from form_004_related_service where id_form_004='$id_form004' and status='Active'";
        $forms=$this->db->fetchAll($sql);
        // $result=null;
        $result['serviceDescriptor_ot']='0';
        $result['serviceBeginDate_ot']=null;
        $result['serviceDescriptor_pt']='0';
        $result['serviceBeginDate_pt']=null;
        $result['serviceDescriptor_slt']='0';
        $result['serviceBeginDate_slt']=null;
    
         
        if(!empty($forms)) {
            foreach ($forms as $fm){
                //   $this->writevar1($fm['related_service_drop'],'related service drop value');
                if ($fm['related_service_drop']=='Occupational Therapy Services'){
                    $result['serviceDescriptor_ot']='1';
                    $result['serviceBeginDate_ot']=$fm['related_service_from_date'];
                }
                if ($fm['related_service_drop']=='Physical Therapy'){
                    $result['serviceDescriptor_pt']='2';
                    $result['serviceBeginDate_pt']=$fm['related_service_from_date'];
                }
                if ($fm['related_service_drop']=='Speech-language therapy'){
                    $result['serviceDescriptor_slt']='3';
                    $result['serviceBeginDate_slt']=$fm['related_service_from_date'];
                }
    
            } // end of looking at the table for that id_form_004
            //return $result;
            return $result;
        } // end of ifset
        else {
            return null;
        }
    }
    
    
}