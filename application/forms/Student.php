<?php

class Form_Student extends Form_AbstractForm {

    public function student_search() {

        $this->setAction ( 'search/search' )->setMethod ( 'post' )->setAttrib ( 'class', 'srchFrmHome' );
        
        //      $searchBox = $this->addElement ( 'text', "searchPhrase", array ('decorators' => $this->elementDecorators ) ); //array ('decorators' => $this->elementDecorators, 'label' => 'Subcat name' ), array ('decorators' => $this->elementDecorators, 'label' => 'Subcat name' ) 
        //      // Setting the decorator for the element to a single, ViewScript, decorator,
        //      // specifying the viewScript as an option, and some extra options:
        $this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'student/viewscripts/student_search.phtml' ) ) ) );

        $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
        
        $this->submit = new Zend_Form_Element_Hidden( 'submit' );
        $this->page = new Zend_Form_Element_Hidden( 'page' );
        $this->submitform = new Zend_Form_Element_Submit( 'submitform' );
        return $this;
    }

    public function student_chart() {
        
        $this->setAction ( 'search/chart' )->setMethod ( 'post' )->setAttrib ( 'class', 'srchFrmHome' );
        
        $this->getView()->setEscape('stripslashes');
        
        $this->setDecorators ( array (array ('ViewScript', array ('viewScript' => 'student/viewscripts/student_chart.phtml' ) ) ) );

//        $JSmodifiedCode = "javascript:modified('', '', '', '', '', '');";
        
        $this->id_student_chart = new App_Form_Element_Hidden('id_student_chart' );
        
        $this->goal_desc = new App_Form_Element_Text('goal_desc', array('label' => 'Chart Title'));
        
        $multiOptions = array('simplechart'=>'Simplechart');
        $this->chart_type = new App_Form_Element_Select('chart_type', array('label' => 'Chart Type', 'multiOptions' => $multiOptions));
        
        $multiOptions = array('Black'=>'Black', 'Red'=>'Red', 'Blue'=>'Blue', 'Green'=>'Green');
        $this->chart_color = new App_Form_Element_Select('chart_color', array('label' => 'Chart Color', 'multiOptions' => $multiOptions));
        
        $this->label_x = new App_Form_Element_Text('label_x', array('label' => 'Label X'));
        
        $this->label_y = new App_Form_Element_Text('label_y', array('label' => 'Label Y'));
        
        $multiOptions = array('date'=>'Date');
        $this->data_type_x = new App_Form_Element_Select('data_type_x', array('label' => 'Data Type', 'multiOptions' => $multiOptions));
        
        $multiOptions = array('float'=>'Numeric');
        $this->data_type_y = new App_Form_Element_Select('data_type_y', array('label' => 'Data Type', 'multiOptions' => $multiOptions));
        
        $this->data_x = new Zend_Form_Element_Textarea('data_x', array('label' => 'X-axis data'));
        $this->data_x->setAttrib('cols', '20');
        $this->data_x->setAttrib('rows', '15');
        
        $this->data_y = new Zend_Form_Element_Textarea('data_y', array('label' => 'Y-axis data'));
        $this->data_y->setAttrib('cols', '20');
        $this->data_y->setAttrib('rows', '15');
        return $this;
    }

}