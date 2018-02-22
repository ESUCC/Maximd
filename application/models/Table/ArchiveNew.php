<?php
class Model_Table_ArchiveNew extends Model_Table_AbstractIepForm {

    protected $_name = 'iep_archive_meta_data';
    protected $_primary = 'id_student';


    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    function addTo($Data){

        $table='iep_archive_meta_data';

        $config = Zend_Registry::get('config');
        $db2= $this->seetupDb($config);

       // Find out if the form exists:

        $formId=$Data['form_id'];
        $formNum=$Data['form_type'];
        $stuid=$Data['id_student'];

        $sql1='select form_id,form_type,id_student from iep_archive_meta_data where form_type=\''.$formNum.'\' and id_student=\''.$stuid.'\' and
               form_id=\''.$formId.'\' ';

        $formExists=$db2->fetchRow($sql1);


        if($formExists==null ) {

       $MetaDbData=array (
           'path_location'=>$Data['path_location'],
           'file_name'=>$Data['file_name'],
            'id_student'=>$Data['id_student'],
            'form_type'=>$Data['form_type'],
            'form_id'=>$Data['form_id'],
            'id_county'=>$Data['id_county'],
            'id_district'=>$Data['id_district'],
            'id_school'=>$Data['id_school']
        );

          $sql2='select name_first,name_last,dob,sesis_exit_date,id_student_local,unique_id_state from iep_student where id_student=\''.$Data['id_student'].'\'';

          $student=$db2->fetchRow($sql2);


          $MetaDbData=array (
              'path_location'=>$Data['path_location'],
              'file_name'=>$Data['file_name'],
              'id_student'=>$Data['id_student'],
              'form_type'=>$Data['form_type'],
              'form_id'=>$Data['form_id'],
              'id_county'=>$Data['id_county'],
              'id_district'=>$Data['id_district'],
              'id_school'=>$Data['id_school'],
              'name_first'=>$student['name_first'],
              'name_last'=>$student['name_last'],
              'dob'=>$student['dob'],
              'sesis_exit_date'=>$student['sesis_exit_date'],
              'id_student_local'=>$student['id_student_local'],
              'unique_id_state'=>$student['unique_id_state']

          );




            $db2->insert($table,$MetaDbData);

        }


           return true;
    }


    public function getFormMetaData($id_student,$id_form,$form_num){

        $table='iep_archive_meta_data';

        $config = Zend_Registry::get('config');
        $db2= $this->seetupDb($config);

        $sql='select * from iep_archive_meta_data where id_student=\''.$id_student.'\' and form_id=\''.$id_form.'\' and form_type=\''.$form_num.'\'';

      //  {
       //     $sql='select * from iep_archive_meta_data where id_iep_archive_meta_data=\''.$iep_form_id.'\'';
      //  }

    //    $this->writevar1($sql,'this is the sql');
        $result=$db2->fetchRow($sql);
      //  $this->writevar1($result,'this is the result');
        return $result;
    }

    public function getFormMetaDataTableId($tableId){

        $table='iep_archive_meta_data';

        $config = Zend_Registry::get('config');
        $db2= $this->seetupDb($config);

        $sql='select * from iep_archive_meta_data where id_iep_archive_meta_data=\''.$tableId.'\'';

        //  {
        //     $sql='select * from iep_archive_meta_data where id_iep_archive_meta_data=\''.$iep_form_id.'\'';
        //  }

  //      $this->writevar1($sql,'this is the sql');
        $result=$db2->fetchRow($sql);
     //   $this->writevar1($result,'this is the result');
        return $result;
    }

    public static function seetupDb($config)
    {
        //writevar1($config,'this is the config');
        $db = Zend_Db::factory($config->db2);
        //   writevar1($db,'this is the db');
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);
        return $db;
     }


}




?>