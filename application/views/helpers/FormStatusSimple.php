<?php

class Zend_View_Helper_FormStatusSimple extends Zend_View_Helper_Abstract
{

    public function formStatusSimple($formObj)
    {
        $student_auth = new App_Auth_StudentAuthenticator();
        $access = $student_auth->validateStudentAccess($formObj->id_student, new Zend_Session_Namespace('user'));

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

        if(!empty($formObj->form_no)) {
            $formKey = 'form_' . $formObj->form_no;
            $formAccessArr = $accessArrayObj->$formKey;
            if (isset($formAccessArr[$formObj->status]['edit']) && $formAccessArr[$formObj->status]['edit']) {
                $linkTo = 'edit';
            } else {
                $linkTo = 'view';
            }
        } else {
            $linkTo = 'view';
        }
        if($formObj->status != 'Archived' && $formObj->status != 'Final') {
            $returnString = $formObj->status . ' [';
    
            $statusFlags = str_split($formObj->page_status);
            $page = 1;
            foreach ($statusFlags as $flag) {
                if("1"===$flag) {
                    $returnString .= '<a href="/form'.$formObj->form_no.'/'.$linkTo.'/document/'.$formObj->id.'/page/'.$page.'" style="font-weight:500; color:#333;">'.$page.'</a>';
                } else {
                    $returnString .= '<a href="/form'.$formObj->form_no.'/'.$linkTo.'/document/'.$formObj->id.'/page/'.$page.'" style="font-weight:500; color:#FF0000;">'.$page.'</a>';
                }
                $page++;
            }
            $returnString .= ']';
        } else {
            $returnString = $formObj->status;
        }
        if($formObj->status != 'Archived' && !empty($formObj->filePath)) {
            $returnString .= ' (Archive Exists)';
        }

        return  $returnString;
    }
}