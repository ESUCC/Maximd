<?php

class Model_Table_Form013Services extends Model_Table_AbstractIepForm {
	
	protected $_name = 'ifsp_services'; // table name
    protected $_primary = 'id_ifsp_services';
    protected $_sequence = 'ifsp_services_id_ifsp_servi_seq';
    protected $_referenceMap    = array(
        'Model_Table_Form013' => array(
            'columns'           => array('id_form_013'),
            'refTableClass'     => 'Model_Table_Form013',
            'refColumns'        => array('id_form_013')
        )
    );
    
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
                   
    public function getIfspServicesState($id_form){
         
        /*
         * Speech-language therapy
         * Physical Therapy Services
         * Occupational Therapy Services
         */
        
        $result['serviceDescriptor_slt']='0';
        $result['serviceBeginDate_slt']=null;
        $result['serviceDescriptor_ot']='0';
        $result['serviceBeginDate_ot']=null;
        $result['serviceDescriptor_pt']='0';
        $result['serviceBeginDate_pt']=null;
        
        $sql="select * from ifsp_services where id_form_013='$id_form'  ";
        $forms=$this->db->fetchAll($sql);

        if (!empty($forms)) {
            foreach($forms as $form){
            
              if ($form['service_service']=='Speech-language therapy') {
                  $result['serviceDescriptor_slt']='3';
                  $result['serviceBeginDate_slt']=$form['service_start'];
                  
              }
              
              if ($form['service_service']=='Physical Therapy Services'){
                  $result['serviceDescriptor_pt']='2';
                  $result['serviceBeginDate_pt']=$form['service_start'];
                  
              }
              if ($form['service_service']=='Occupational Therapy Services'){
                  $result['serviceDescriptor_ot']='1';
                  $result['serviceBeginDate_ot']=$form['service_start'];
                  
              }
              
    
            
              
            
            return $result;
            } // end of the for looop
        }   
        else {
            return null;
        }
         
    }   // end of the function
    
    
    
}