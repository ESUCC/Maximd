<?php
	
class Model_Table_StudentTeam extends Zend_db_Table_Abstract
{ 
	/**
	 * The default table name 
	 */
    protected $_name = 'iep_student_team';
    protected $_primary = 'id_student_team';
    protected $_sequence = 'iep_student_team_id_seq';
    
   
   
     
    public function getStudentsOfTeamMember($id_teamMember){
        
        $county_sv = $_SESSION["user"]["user"]->user["id_county"];
        $district_sv = $_SESSION["user"]["user"]->user["id_district"];
        
    //    writevar($id_teamMember,'this is the id of team mmember in getStudentsOfTeammember function');
        $studentAssociated = new Model_Table_StudentTeam();
        
        $dbConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
       $database = Zend_Db::factory($dbConfig->db2);
        
       $sql=('SELECT t.id_student,p.id_school from iep_student_team t,iep_privileges p where t.id_student=p.id_student 
              and p.id_county=\''.$cty.'\' and p.id_district=\''.$district_sv."\'");
           
           
        /*   
           
        $result=$studentAssociated->fetchall($studentAssociated->select()
            ->where('id_personnel =?',$id_teamMember)
            ->where('status=?',"Active"));
        */
           
           
   /* a guide
    *    $sql =('SELECT p.name_first,p.name_last,p.id_personnel, p.user_name,r.class,r.status,d.name_district,r.id_school from iep_personnel p,
               iep_privileges r, iep_district d where p.id_county=d.id_county and p.id_district=d.id_district and p.id_personnel=r.id_personnel 
               and r.status= \'Active\' and r.id_county=\''.$cty.'\' and r.id_district=\''.$district.'\'
               and r.id_school=\''.$school.'\'order  by p.name_last');
         
        $result=$database->fetchAll($sql);
        return $result;
    
     */  
       $result=$database->fetchAll($sql);
       return $result;
    }
    
    public function getStudentsOnTeam($id_personnel) {
        
        $studentStaffMember=new Model_Table_StudentTeam();
        $result=$studentStaffMember->fetchAll($studentStaffMember->select()
         
            
            ->where('id_personnel =?',$id_personnel));
        //  writevar($result,'these are the results for the student team'); die();
        return $result;
      
    }
    
    public function setRightsAStaff($oneStaff) {
       
        $studentsRightsTable= new Model_Table_StudentTeam();
        
         $total=$oneStaff['total_count'];
       // writevar($oneStaff.'this is the onestaff in the model'); 
     for($x=0;$x<$total;$x++){
         $y=$x;
         $y=(string) $y;
         $idPa=  $oneStaff[$y."_id_student"]; //writevar($idPa,'this is the id as it comes out in hte model');
         $e="E_".$idPa;
         $v="V_".$idPa;
         $c="C_".$idPa; 
         $exist = "X_".$idPa; 
     
          if(!isset($oneStaff[$e])|| $oneStaff[$e]!='1') $oneStaff[$e]='0';
          if(!isset($oneStaff[$v])|| $oneStaff[$v]!='1') $oneStaff[$v]='0';
          if(!isset($oneStaff[$c])|| $oneStaff[$c]!='1') $oneStaff[$c]='0';
          /*
          writevar($idPa,'this id of the student');
          writevar($oneStaff[$e], 'this is the e value');
          writevar($oneStaff[$v], 'this is the v value');
          writevar($oneStaff[$c], 'this is the c value');
          writevar($oneStaff[$exist],'this is exists or not'); */
          
          
         // Check for null values across the board and dont do anything if you find one
         if($oneStaff[$exist]=='no' and $oneStaff[$e]!='1' and $oneStaff[$v]!='1' and $oneStaff[$c]!='1') {
             //do nothing
            writevar($idPa,'we got into section 1'); 
         }
     
         if(  ( $oneStaff[$e]=='1' || $oneStaff[$v]=='1' || $oneStaff[$c]=='1') && ($oneStaff[$exist] == 'no'))
             { 
                 writevar($idPa,'we got into section 2'); 
                 $data=array(
                     'id_personnel' =>$oneStaff['id_personnel'],
                     'id_student'=>$idPa,
                     'flag_edit'=>$oneStaff[$e],
                     'flag_view'=>$oneStaff[$v],
                     'flag_create'=>$oneStaff[$c],
                     'status'=>'Active'
                 );
                 
                
                $this->insert($data);
             
             
             }
             
             //check for exist eq no and one of the fields to be a one if so then insert them into the db
         
   
         
         if( $oneStaff[$e]!='1' && $oneStaff[$v]!='1' &&  $oneStaff[$c]!= '1' && $oneStaff[$exist] == 'yes') { 
           //  writevar($idPa,'we got into section 3'); 
             $where = array(
                 'id_student =?'=>$idPa,
                 'id_personnel=?'=>$oneStaff['id_personnel']
             );
            
              $this->delete($where);
         }
         
       
         if( ($oneStaff[$e]=='1' ||$oneStaff[$v]=='1'  ||$oneStaff[$c]=='1' ) &&  ($oneStaff[$exist] == 'yes') ) {
                
          //   writevar($idPa,'we got into section 4'); 
              //  writevar($oneStaff[$c],'this is the value of c'); 
                
                $data1= array(
                    'flag_edit'=>$oneStaff[$e],
                    'flag_view'=>$oneStaff[$v],
                    'flag_create'=>$oneStaff[$c],
                    'id_student'=>$idPa,
                    'id_personnel'=>$oneStaff['id_personnel']
                      );
                 $where1 = array(
                     'id_student =?'=>$idPa,
                     'id_personnel =?'=>$oneStaff['id_personnel']
                 );
            //    writevar($data1,'this is the data');
            //   writevar($where1,'thisis the where'); 
               
                 
                $this->update($data1,$where1);  
                 
                
             }
         
         
         } // End the for loop
     
          
     
     
     
     
    }  
    
    public function setRights($Id) { 
        
       $studentRightsTable = new Model_Table_StudentTeam();
       // writevar($Id,'this is the Id variable for set righton on list of teachers');
        $cnt=$Id['count'];
        writevar($cnt,'this is the count of items in the array');
        $x=1;
      //  writevar($Id['count'], 'this is the number of staff'); die(); // the number of staff as an integer is returned here.
        for ($x =1;$x < $Id['count'];$x++) {
           
            $y=$x;
            $y=(string) $y;
            $idPa=  $Id[$y."_id_personnel"];
            
            $e="E_".$idPa;
            $v="V_".$idPa;
            $c="C_".$idPa;
            $exist = "X_".$idPa;
           
           
            
            
           if(!isset($Id[$e]) || $Id[$e]!='1') $Id[$e]='0';
           if(!isset($Id[$v]) || $Id[$v]!='1') $Id[$v]='0';
           if(!isset($Id[$c]) || $Id[$c]!='1') $Id[$c]='0';
           
          
     
           $sec='sections';
          
         //  writevar($Id,'this is the id after having it set');
         
           $data=array(
               'id_personnel' =>$idPa,
               'id_student'=>$Id['id_student'],
               'flag_edit'=>$Id[$e],
               'flag_view'=>$Id[$v],
               'flag_create'=>$Id[$c] );
           
           
            
           $where = array(
               'id_student =?'=>$Id['id_student'],
               'id_personnel =?'=>$idPa);
           
        //  writevar($Id[$exist],'this is the valaue of exist ');
          
            if($Id[$exist]=='no'){
                if($Id[$e]=='0' and $Id[$v]=='0' and $Id[$c]=='0'){
                   writevar($sec,'section 1');
                 
                }
                
                if (($Id[$e]=='1' || $Id[$v]=='1'|| $Id[$c]=='1')) {
                  writevar($sec,'section 2');
                 // $this->insert($data); 
                  }
              }
              
              if($Id[$exist]=='yes') {
                  if($Id[$e]=='0' and $Id[$v]=='0' and $Id[$c]=='0'){
                    writevar($sec,'section 3');
                    //  $this->delete($where);
                   }
                 
                  if(($Id[$e]=='1' || $Id[$v]=='1'|| $Id[$v]=='1')) {
                   //   $this->update($data,$where);
                      writevar($sec,'section 4');
                  }
                      
                 //     writevar($idPa,'section 4');writevar($data,'this is the data var');writevar($where,'this is the where clause');
                   //   $this->update($data,$where);
                  }
                  
              }  // end of the for
   
          writevar($Id,'this is what is available after everything');
          
         
           }  // end function
    } // end of class
 // end of class

