<?php

class My_View_Helper_StudentOptionsMenu extends Zend_View_Helper_Abstract
{
	//
	// function should be priv aware
	//
	
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
	public function studentOptionsMenu($multiOptions, $id_student)
    {
		if(is_null($id_student)) return '';
//    	$session = new Zend_Session_Namespace('studentOptionsMenu');
		$multiOptions = array(
                'choose'=>'Choose...', 
                'view'=>'View Student', 
                'edit'=>'Edit Student', 
                'charts'=>'Student Charting',
                'parent/student'=>'Parent',
                'team'=>'Student Team',
                'search-forms'=>'Student Forms',
                'log'=>'Student Log',
		);

        $element = new Zend_Form_Element_Select('options', array(
            'id' => 'options',
        ));
        foreach ($multiOptions as $key => $value) {
            $element->addMultiOption($key, $value);
          // $this->writevar1($key,' '.$value.' this is the multioptions');
        }
        $element->setDecorators(array('Label', 'ViewHelper'));
          $element->setAttrib('onchange', "javascript:if(this.value) location.href='/student/'+this.value+'/id_student/'+$id_student;");
      //  $element->setAttrib('onchange', "javascript:if(this.value) location.href='/parent/search/'+this.value+'/id_student/'+$id_student;");
//        $element->setValue($session->currentvalue);
        $element->setLabel('Options:');
      //   $this->writevar1($element,'these re the elements');
        return $element;

    }

}

