<?php

class Zend_View_Helper_FormMenuOld extends Zend_View_Helper_Abstract
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
    public function formMenuOld($id_student, $form)
    {
        include("Writeit.php");
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

        if(!empty($form->form_no)) {
            $formKey = 'form_' . $form->form_no;
            
           
            
            $formAccessArr = $accessArrayObj->$formKey;
        } else {
            $formAccessArr = array();
           
        }
        writevar($formAccessArr[$form->status]['view'],'this is the view from FormMenuOld.php');
        if (isset($formAccessArr[$form->status]['view']) && $formAccessArr[$form->status]['view']) {
            $options[] = '<option href="https://iepd.nebraskacloud.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=view">View</option>';
        //  writevar($options,'this is the options');
        }
        if('Draft'==$form->status) {
            if (isset($formAccessArr[$form->status]['edit']) && $formAccessArr[$form->status]['edit']) {
            	$options[] = '<option href="https://iepd.nebraskacloud.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=edit">Edit</option>';
            }
            if (isset($formAccessArr[$form->status]['delete']) && $formAccessArr[$form->status]['delete']) {
                $options[] = '<option href="https://iepd.nebraskacloud.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=delete">Delete</option>';
            }
            if (isset($formAccessArr[$form->status]['finalize']) && $formAccessArr[$form->status]['finalize']) {
                $options[] = '<option href="https://iepd.nebraskacloud.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=finalize">Finalize</option>';
            }
        }
        if('Suspended'==$form->status) {
            if (isset($formAccessArr['Draft']['view']) && $formAccessArr['Draft']['view']) {
                $options[] = '<option href="https://iepd.nebraskacloud.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=view">View</option>';
            }
            if (isset($formAccessArr['Draft']['delete']) && $formAccessArr['Draft']['delete']) {
                $options[] = '<option href="https://iepd.nebraskacloud.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=delete">Delete</option>';
            }
            if (isset($formAccessArr['Draft']['edit']) && $formAccessArr['Draft']['edit']) {
                $options[] = '<option href="https://iepd.nebraskacloud.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=resume">Resume Draft Status</option>';
            }
        }
        if (isset($formAccessArr[$form->status]['log']) && $formAccessArr[$form->status]['log']) {
            $options[] = '<option href="https://iepd.nebraskacloud.org/srs.php?area=student&sub=form_'.$form->form_no.'&document='.$form->id.'&page=1&option=log">Log</option>';
        }
        if (isset($formAccessArr[$form->status]['print']) && $formAccessArr[$form->status]['print']) {
            $options[] = '<option href="https://iepd.nebraskacloud.org/form_print.php?form=form_'.$form->form_no.'&document='.$form->id.'">Print</option>';
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
        $options[] = '</select>';
        return implode(' | ', $options);
    }
}