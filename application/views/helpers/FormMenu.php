<?php

class Zend_View_Helper_FormMenu extends Zend_View_Helper_Abstract
{

    /**
     * Returns the Form options menu for search
     *
     * @return string
     *
    array(4) {
    ["view"] => bool(true)
    ["new"] => bool(true)
    ["Draft"] => array(6) {
    ["view"] => bool(true)
    ["edit"] => bool(true)
    ["delete"] => bool(true)
    ["finalize"] => bool(true)
    ["log"] => bool(true)
    ["print"] => bool(true)
    }
    ["Final"] => array(5) {
    ["view"] => bool(true)
    ["edit"] => bool(false)
    ["delete"] => bool(false)
    ["log"] => bool(true)
    ["print"] => bool(true)
    }
    }
     *
     *
     * Coppied from formOptions
     * then altered to build a select
     */
    public function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }


    public function formMenu($id_student, $form)
    {
        $preVersion=new Model_Table_ArchiveNew();
        $formInfo=$preVersion->getFormMetaData($form->id_student,$form->id,$form->form_no);
        $OldVersion=false;
        if($formInfo['version_number']<9) $OldVersion=true;



        $options = array('<select class="formMenuSelect" style="width: 150px;">');
        $options[] = '<option value="">Form Options...</option>';
        $student_auth = new App_Auth_StudentAuthenticator();
        $session = new Zend_Session_Namespace('user');
        $access = $student_auth->validateStudentAccess($id_student, $session);

        if ('Team Member' == $access->description) {
            if ('viewaccess' == $access->access_level) {
                $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description ) . 'View';
                $accessArrayObj = new $accessArrayClassName ();
            } else {
                $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description ) . 'Edit';
                $accessArrayObj = new $accessArrayClassName ();
            }
        } else {
            $accessArrayClassName = 'App_Auth_Role_' . str_replace ( ' ', '', $access->description );
            $accessArrayObj = new $accessArrayClassName ();
        }
   //     echo '<pre>' . print_r($accessArrayClassName, true) . '</pre>';


      /* Mike changed this 4-24-2017 to the uncommented code per jira SRS-57
       * if(!empty($form->form_no)) {
            $formKey = 'form_' . $form->form_no;
            $formAccessArr = $accessArrayObj->$formKey;
        }*/

        if(!empty($form->form_no)) {
            $formKey = 'form_' . $form->form_no;
            $formAccessArr = $accessArrayObj->$formKey;

            if(isset($formAccessArr['Final']['dupe_form_004'])) {
                $form004DupMenu= new Model_Table_Form004();
                $continue=$form004DupMenu->removeDupeMenu($id_student);
                $formAccessArr['Final']['dupe_form_004'] = $continue;
                // $this->writevar1($formAccessArr,'this is the form access array');
                //$this->writevar1($formAccessArr['Final']['dupe_form_004'],'this is the form access array that was built');
            }

        }
        // Mike changed the above to this 4-24-2017 srs-17
        else {
            $formAccessArr = array();
        }

        if (isset($formAccessArr[$form->status]['view']) && $formAccessArr[$form->status]['view']) {
        // Mike put the if in 2-23-2018 SRS-190
        /*    if($formInfo==false or $OldVersion==false)*/ $options[] = '<option href="/form'.$form->form_no.'/view/document/'.$form->id.'/page/1">View</option >';
        }

    // Mike changed this 4-18-2017 as per jira SRS-42
    //    if('Draft'==$form->status) {
           if('Draft'==$form->status||$form->form_no=='023'|| $form->form_no=='022') {

                if($form->form_no=='023' || $form->form_no=='022'){
                    $formAccessArr[$form->status]['delete']=true;
                }

                // end of Mike change



            if (isset($formAccessArr[$form->status]['edit']) && $formAccessArr[$form->status]['edit']) {
                $options[] = '<option href="/form'.$form->form_no.'/edit/document/'.$form->id.'/page/1">Edit</option >';
            }
            if (isset($formAccessArr[$form->status]['delete']) && $formAccessArr[$form->status]['delete']) {
                $options[] = '<option href="/form'.$form->form_no.'/delete/document/'.$form->id.'">Delete</option >';
            }
            if (isset($formAccessArr[$form->status]['finalize']) && $formAccessArr[$form->status]['finalize']) {
                $options[] = '<option href="/form'.$form->form_no.'/finalize/document/'.$form->id.'">Finalize</option >';
            }



        }
        if('Suspended'==$form->status) {
            if (isset($formAccessArr['Draft']['view']) && $formAccessArr['Draft']['view']) {
   // Mike put this in 2-23-2018 SRS-190
                if($formInfo==false or $OldVersion==false)                $options[] = '<option href="/form'.$form->form_no.'/view/document/'.$form->id.'/page/1">View</option >';
            }
            if (isset($formAccessArr['Draft']['delete']) && $formAccessArr['Draft']['delete']) {
                $options[] = '<option href="/form'.$form->form_no.'/delete/document/'.$form->id.'">Delete</option >';
            }
            if (isset($formAccessArr['Draft']['edit']) && $formAccessArr['Draft']['edit']) {
                $options[] = '<option href="/form'.$form->form_no.'/resume/document/'.$form->id.'">Resume Draft Status</option >';
            }

        }

        // Mike added this 4-14-2018 SRS-222 so that users can unfinalize with the correct privileges
      //  $this->writevar1($form,'this is the form located in line 146 of FormMenu.php'.$form->id_district);
        $Unfinalize=false;

        $stuInfo=new Model_Table_IepStudent();
        $studentInfor=$stuInfo->getUserById($form->id_student);
        $cty=$studentInfor['id_county'];
        $dst=$studentInfor['id_district'];

       $distInfo=new Model_Table_IepDistrict();
       $districtInfo=$distInfo->getIepDistrictByID($cty, $dst);

       $finalizeForm=$districtInfo['allow_unfinalize_adm'];

    //   $this->writevar1($finalizeForm,'this should be true or false');
       if($finalizeForm==true){
           $class=3;
       }
       else {
           $class=2;
       }

       //  $this->writevar1($cty,'this is the county coming from the student');
       //  $this->writevar1($dst,'this is the district coming from the student');

        $listPrivs=$_SESSION['user']['user']->privs;
        foreach($listPrivs as $privs){


            if($privs['class']<=$class and $privs['status']=='Active'
               and $privs['id_district']==$dst and $privs['id_county']==$cty) $Unfinalize=true;
        }

        if($form->status=='Final' && $Unfinalize==true){
            $options[] = '<option href="/form'.$form->form_no.'/unfinalize/document/'.$form->id.'">Unfinalize</option >';
        }

    // End of Mike SRS-222
        if('Final'==$form->status && '004' == $form->form_no) {
            if (isset($formAccessArr['Final']['delete']) && $formAccessArr['Final']['delete']) {
                $options[] = '<option href="/form'.$form->form_no.'/delete/document/'.$form->id.'">Delete</option >';
            }
            if (isset($formAccessArr['Final']['dupe_form_004']) && $formAccessArr['Final']['dupe_form_004']) {
                $options[] = '<option href="/form004/dupe/document/'.$form->id.'/page//option/dupe_form_004">Dupe</option >';
            }
            if (isset($formAccessArr['Final']['createpr']) && $formAccessArr['Final']['createpr']) {
                $options[] = '<option href="/form010/create/student/'.$id_student.'/parent_key/id_form_004/parent_id/'.$form->id.'">Create PR</option >';
            }
            if (isset($formAccessArr['Final']['summary']) && $formAccessArr['Final']['summary']) {
                $options[] = '<option href="/form004/print/summary/1/document/'.$form->id.'">Summary</option >';
            }
            if (isset($formAccessArr['Final']['dupe_form_004_update']) && $formAccessArr['Final']['dupe_form_004_update']) {
                $options[] = '<option href="/form004/dupe/document/'.$form->id.'/option/dupe_form_004/dupe_type/full">Update</option >';
            }
        }
        if('Final'==$form->status && '002' == $form->form_no) {
            if (isset($formAccessArr['Final']['dupe']) && $formAccessArr['Final']['dupe']) {
                $options[] = '<option href="/form002/dupe/document/'.$form->id.'/page//option/dupe">Dupe</option >';
            }
        }
        if (isset($formAccessArr[$form->status]['log']) && $formAccessArr[$form->status]['log']) {
            $options[] = '<option href="/form'.$form->form_no.'/log/document/'.$form->id.'" target="_blank">Log</option >';
        }

      //  $preVersion=new Model_Table_ArchiveNew();
      //  $formInfo=$preVersion->getFormMetaData($form->id_student,$form->id,$form->form_no);

        if($formInfo!=false){

         //   $this->writevar1($formInfo['form_id'],'this is the form info4');

            if (isset($formAccessArr[$form->status]['print']) && $formAccessArr[$form->status]['print']) {
               $options[] = '<option href="/district/testprint/id/'.$formInfo['id_iep_archive_meta_data'].'">Print From Archive</option >';
            }

          //  $this->writevar1($formInfo,'this is the form info');
       }

       if (isset($formAccessArr[$form->status]['print']) && $formAccessArr[$form->status]['print']) {
 /*if($formInfo==false or $OldVersion==false)  */       $options[] = '<option href="/form'.$form->form_no.'/print/document/'.$form->id.'">Print</option >';
        }



    //    $this->writevar1($form->id." ".$form->id_student." ".$form->form_no." ".$form->version_number,'this is the form');
        if($form->filePath) {
            $splitPath = preg_split('/\//', $form->filePath);
            $folder1 = $splitPath[1];
            $folder2 = $splitPath[2];
            $file = $splitPath[3];
            if(0 && 'Admin' == $access->description) { // 20140707 jlavere prepping restore feature
                $options[] = '<option href="/archive/restore/form/'.$form->form_no.'/id/'.$form->id.'">Restore</option >';
            }
            $options[] = '<option href="/file-download/pdf/folder1/'. $folder1 .'/folder2/' . $folder2 . '/file/' . $file . '">Download</option >';
        }
        if('Admin' == $access->description) {
            $options[] = '<option href="/form'.$form->form_no.'/delete/document/'.$form->id.'">Delete</option >';

        }
        $options[] = '</select>';
        return implode(' | ', $options);
    }
}