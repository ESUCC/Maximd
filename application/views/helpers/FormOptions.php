<?php

class Zend_View_Helper_FormOptions extends Zend_View_Helper_Abstract
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
     */
    public function formOptions($id_student, $form)
    {
        $options = array();
        $student_auth = new App_Auth_StudentAuthenticator();
        $session = new Zend_Session_Namespace('user');
        $access = $student_auth->validateStudentAccess($id_student, $session);

//        $myStudentsObj = new Model_Table_MyStudents();
//        $student = $myStudentsObj->find($session->sessIdUser, $id_student);

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

        $formKey = 'form_' . $form->form_no;
        $formAccessArr = $accessArrayObj->$formKey;

        if (isset($formAccessArr[$form->status]['view']) && $formAccessArr[$form->status]['view']) {
            $options[] = '<a href="/form'.$form->form_no.'/view/document/'.$form->id.'/page/1">View</a>';
        }
        if('Draft'==$form->status) {
            if (isset($formAccessArr[$form->status]['edit']) && $formAccessArr[$form->status]['edit']) {
                $options[] = '<a href="/form'.$form->form_no.'/edit/document/'.$form->id.'/page/1">Edit</a>';
            }
            if (isset($formAccessArr[$form->status]['delete']) && $formAccessArr[$form->status]['delete']) {
                $options[] = '<a href="/form'.$form->form_no.'/delete/document/'.$form->id.'">Delete</a>';
            }
            if (isset($formAccessArr[$form->status]['finalize']) && $formAccessArr[$form->status]['finalize']) {
                $options[] = '<a href="/form'.$form->form_no.'/finalize/document/'.$form->id.'">Finalize</a>';
            }
        }
        if('Suspended'==$form->status) {
            if (isset($formAccessArr['Draft']['view']) && $formAccessArr['Draft']['view']) {
                $options[] = '<a href="/form'.$form->form_no.'/view/document/'.$form->id.'/page/1">View</a>';
            }
            if (isset($formAccessArr['Draft']['delete']) && $formAccessArr['Draft']['delete']) {
                $options[] = '<a href="/form'.$form->form_no.'/delete/document/'.$form->id.'">Delete</a>';
            }
            if (isset($formAccessArr['Draft']['edit']) && $formAccessArr['Draft']['edit']) {
                $options[] = '<a href="/form'.$form->form_no.'/resume/document/'.$form->id.'">Resume Draft Status</a>';
            }
        }
        if('Final'==$form->status && '004' == $form->form_no) {
            if (isset($formAccessArr['Final']['delete']) && $formAccessArr['Final']['delete']) {
                $options[] = '<a href="/form'.$form->form_no.'/delete/document/'.$form->id.'">Delete</a>';
            }
            if (isset($formAccessArr['Final']['dupe_form_004']) && $formAccessArr['Final']['dupe_form_004']) {
                $options[] = '<a href="/form004/dupe/document/'.$form->id.'/page//option/dupe_form_004">Dupe</a>';
            }
            if (isset($formAccessArr['Final']['createpr']) && $formAccessArr['Final']['createpr']) {
                $options[] = '<a href="/form010/create/student/'.$id_student.'/parent_key/id_form_004/parent_id/'.$form->id.'">Create PR</a>';
            }
            if (isset($formAccessArr['Final']['summary']) && $formAccessArr['Final']['summary']) {
                $options[] = '<a href="/form004/print/summary/1/document/'.$form->id.'">Summary</a>';
            }
            if (isset($formAccessArr['Final']['dupe_form_004_update']) && $formAccessArr['Final']['dupe_form_004_update']) {
                $options[] = '<a href="/form004/dupe/document/'.$form->id.'/option/dupe_form_004/dupe_type/full">Update</a>';
            }


        }
        if('Final'==$form->status && '002' == $form->form_no) {
            if (isset($formAccessArr['Final']['dupe']) && $formAccessArr['Final']['dupe']) {
                $options[] = '<a href="/form002/dupe/document/'.$form->id.'/page//option/dupe">Dupe</a>';
            }
        }
        if (isset($formAccessArr[$form->status]['log']) && $formAccessArr[$form->status]['log']) {
            $options[] = '<a href="/form'.$form->form_no.'/log/document/'.$form->id.'" target="_blank">Log</a>';
        }
        if (isset($formAccessArr[$form->status]['print']) && $formAccessArr[$form->status]['print']) {
            $options[] = '<a href="/form'.$form->form_no.'/print/document/'.$form->id.'">Print</a>';
        }

        if($form->filePath) {
            $splitPath = preg_split('/\//', $form->filePath);
            $folder1 = $splitPath[1];
            $folder2 = $splitPath[2];
            $file = $splitPath[3];
            $options[] = '<a href="/file-download/pdf/folder1/'. $folder1 .'/folder2/' . $folder2 . '/file/' .
                             $file . '">Download</a>';
        }

        return implode(' | ', $options);
    }
}