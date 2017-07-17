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
        	$options[] = '<a href="https://iep.esucc.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=view">View</a>';
        }
        if('Draft'==$form->status) {
            if (isset($formAccessArr[$form->status]['edit']) && $formAccessArr[$form->status]['edit']) {
                $options[] = '<a href="https://iep.esucc.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=edit">Edit</a>';
            }
            if (isset($formAccessArr[$form->status]['delete']) && $formAccessArr[$form->status]['delete']) {
                $options[] = '<a href="https://iep.esucc.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=delete">Delete</a>';
            }
            if (isset($formAccessArr[$form->status]['finalize']) && $formAccessArr[$form->status]['finalize']) {
                $options[] = '<a href="https://iep.esucc.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=finalize">Finalize</a>';
            }
        }
        if('Suspended'==$form->status) {
            if (isset($formAccessArr['Draft']['view']) && $formAccessArr['Draft']['view']) {
                $options[] = '<a href="https://iep.esucc.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=view">View</a>';
            }
            if (isset($formAccessArr['Draft']['delete']) && $formAccessArr['Draft']['delete']) {
                $options[] = '<a href="https://iep.esucc.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=delete">Delete</a>';
            }
            if (isset($formAccessArr['Draft']['edit']) && $formAccessArr['Draft']['edit']) {
                $options[] = '<a href="https://iep.esucc.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=resume">Resume Draft Status</a>';
            }
        }
        if (isset($formAccessArr[$form->status]['log']) && $formAccessArr[$form->status]['log']) {
            $options[] = '<a href="https://iep.esucc.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=log">Log</a>';
        }
        if (isset($formAccessArr[$form->status]['print']) && $formAccessArr[$form->status]['print']) {
            $options[] = '<a href="https://iep.esucc.org/form_print.php?form=form_'.$form->form_no.'&document='.$form->id.'">Print</a>';
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