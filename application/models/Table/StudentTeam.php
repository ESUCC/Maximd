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
    //    writevar($id_teamMember,'this is the id of team mmember in getStudentsOfTeammember function');
        $studentAssociated = new Model_Table_StudentTeam();
        $result=$studentAssociated->fetchall($studentAssociated->select()
            ->where('id_personnel =?',$id_teamMember)
            ->where('status=?',"Active"));
        
     //  writevar($result,'this is the result');
       
   
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
     // writevar($oneStaff,'this is the onestaff in the model'); 
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
          
         // writevar($idPa,'this id of the student');
        //  writevar($oneStaff[$e], 'this is the e value');
        // writevar($oneStaff[$v], 'this is the v value');
       //   writevar($oneStaff[$c], 'this is the c value');
        //  writevar($oneStaff[$exist],'this is exists or not'); 
          
          
          
         // Check for null values across the board and dont do anything if you find one
         if($oneStaff[$exist]=='no' and $oneStaff[$e]!='1' and $oneStaff[$v]!='1' and $oneStaff[$c]!='1') {
             //do nothing
       //   writevar($idPa,'we got into section 1 with nothing checked and no'); 
         }
     
         if(  ( $oneStaff[$e]=='1' || $oneStaff[$v]=='1' || $oneStaff[$c]=='1') && ($oneStaff[$exist] == 'no'))
             { 
              //   writevar($idPa,'we got into section 2'); 
                 $data=array(
                     'id_personnel' =>$oneStaff['id_personnel'],
                     'id_student'=>$idPa,
                     'flag_edit'=>$oneStaff[$e],
                     'flag_view'=>$oneStaff[$v],
                     'flag_create'=>$oneStaff[$c],
                     'status'=>'Active'
                 );
                 
             //  writevar($data,'this is the data no with one of them');
            //   $this->insert($data);
         //    die();
             
             }
             
             //check for exist eq no and one of the fields to be a one if so then insert them into the db
         
   
         
         if( $oneStaff[$e]!='1' && $oneStaff[$v]!='1' &&  $oneStaff[$c]!= '1' && $oneStaff[$exist] == 'yes') { 
           //  writevar($idPa,'we got into section 3'); 
             
             $data=array(
                 'id_personnel' =>$oneStaff['id_personnel'],
                 'id_student'=>$idPa,
                 'flag_edit'=>$oneStaff[$e],
                 'flag_view'=>$oneStaff[$v],
                 'flag_create'=>$oneStaff[$c],
                 'status'=>'Removed'
             );
             
             $where = array(
                 'id_student =?'=>$idPa,
                 'id_personnel=?'=>$oneStaff['id_personnel']
             );
            
       //    writevar($data,'this is the data zeroed out and yes');
             
         $this->update($data,$where);
         }
         
       
         if( ($oneStaff[$e]=='1' ||$oneStaff[$v]=='1'  ||$oneStaff[$c]=='1' ) &&  ($oneStaff[$exist] == 'yes') ) {
                
          //   writevar($idPa,'we got into section 4'); 
              //  writevar($oneStaff[$c],'this is the value of c'); 
                
                $data1= array(
                    'flag_edit'=>$oneStaff[$e],
                    'flag_view'=>$oneStaff[$v],
                    'flag_create'=>$oneStaff[$c],
                    'id_student'=>$idPa,
                    'id_personnel'=>$oneStaff['id_personnel'],
                    'status'=>'Active'
                      );
                 $where1 = array(
                     'id_student =?'=>$idPa,
                     'id_personnel =?'=>$oneStaff['id_personnel']
                 );
           //   writevar($data1,'this is the data yes with one of them checked');
               
               
                 
                $this->update($data1,$where1);  
              //  
                 
                
             }
         
         
         } // End the for loop
     
          
     
     
     
     
    }  
    
    public function setRights($Id) { 
       // writevar($Id,'this is the value of the whole thing');
       $studentRightsTable = new Model_Table_StudentTeam();
       // writevar($Id,'this is the Id variable for set righton on list of teachers');
        $cnt=$Id['count'];
       // writevar($cnt,'this is the count of items in the array');
        $x=1;
        $y=1;
  //      writevar($Id, 'this is the staff');  // the number of staff as an integer is returned here.
   //   writevar($Id,'this is the whole thing');
        for ($x =1;$x < $cnt+1;$x++) {
           
            $y=$x;
          //  $y=(string) $y;
            $idPa=  $Id[$y."_id_personnel"];
            $e="E_".$idPa;
            $v="V_".$idPa;
            $c="C_".$idPa;
            $exist = "X_".$idPa;
         //   writevar($idPa,'this is the id');
           //   writevar($y,'this is the value of y');
            //  writevar($idPa,'this is the idpa');
           //   writevar($exist,'this is exist');
          //    writevar($Id[$exist],'this is id exist value');
          //  writevar($e,'this is the edit one');
           
            
            
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
               'flag_create'=>$Id[$c],
               'status'=>'Active');
           
           $dataStatusRemoved=array(
               'id_personnel' =>$idPa,
               'id_student'=>$Id['id_student'],
               'flag_edit'=>$Id[$e],
               'flag_view'=>$Id[$v],
               'flag_create'=>$Id[$c],
               'status'=>'Removed');
           
           
      //    writevar($data,'this is the data');
      //    writevar($exist,'this is the value of exists');
           $where = array(
               'id_student =?'=>$Id['id_student'],
               'id_personnel =?'=>$idPa);
           
 
           
            if($Id[$exist]=='no'){
                if($Id[$e]=='0' and $Id[$v]=='0' and $Id[$c]=='0'){
                    
                  //  $this->update($data,$dataStatusRemoved);
                //   writevar($idPa,'section 1');
                 
                }
                
                if (($Id[$e]=='1' || $Id[$v]=='1'|| $Id[$c]=='1')) {
               //  writevar($idPa,'section 2');
               
                  $this->insert($data); 
                  }
              }
              
              if($Id[$exist]=='yes') {
                  if($Id[$e]=='0' and $Id[$v]=='0' and $Id[$c]=='0'){
                  
                    $this->update($dataStatusRemoved,$where);
              //      writevar($dataStatusRemoved,'this is the array datastatusremoved');
                  //  die();
                   }
                 
                  if(($Id[$e]=='1' || $Id[$v]=='1'|| $Id[$v]=='1')) {
                     $this->update($data,$where);
                 //    writevar($idPa,'section 4');
                  }
                      
                 //     writevar($idPa,'section 4');writevar($data,'this is the data var');writevar($where,'this is the where clause');
                   //   $this->update($data,$where);
                  }
                  
              }  // end of the for
   
         // writevar($Id,'this is what is available after everything');
          
          
           }  // end function
    } // end of class
 // end of class

