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
    public function formMenu($id_student, $form)
    {
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
            $options[] = '<option href="/form'.$form->form_no.'/view/document/'.$form->id.'/page/1">View</option >';
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
                $options[] = '<option href="/form'.$form->form_no.'/view/document/'.$form->id.'/page/1">View</option >';
            }
            if (isset($formAccessArr['Draft']['delete']) && $formAccessArr['Draft']['delete']) {
                $options[] = '<option href="/form'.$form->form_no.'/delete/document/'.$form->id.'">Delete</option >';
            }
            if (isset($formAccessArr['Draft']['edit']) && $formAccessArr['Draft']['edit']) {
                $options[] = '<option href="/form'.$form->form_no.'/resume/document/'.$form->id.'">Resume Draft Status</option >';
            }
        }
        
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
        if (isset($formAccessArr[$form->status]['print']) && $formAccessArr[$form->status]['print']) {
            $options[] = '<option href="/form'.$form->form_no.'/print/document/'.$form->id.'">Print</option >';
        }
        
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