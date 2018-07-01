<?php

	class My_Classes_Decorators {
		
		public static $elementDecorators = array(
	        'ViewHelper',
	    );
	    
	    public static $dojoDecorators = array(
	        'DijitElement',
	    );
	    
	    public static $simpleDecorators = array(
	        'ViewHelper',
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
	    );	
	    
	    public static $emptyDecorators = array(
	        'ViewHelper'
	    );
	    	
	    public static $descriptionDecorators = array(
	        array('Description', array('tag' => 'span')),
	        'ViewHelper',
	    	
	    	//array(array ('data' => 'HtmlTag' ), array ('tag' => 'span', 'class' => 'refresh_date' ) )
	    );
	    	
        public static $labelDecorators = array(
            'ViewHelper',
            array('Label', array('tag' => 'span')),
            array('Description', array('tag' => 'span')),
        );
	    	    
        public static $labelDecoratorsNoSpan = array(
            'ViewHelper',
            'Label',
        );
        
        public static $labelRightDecorators = array(
	    	array('Label', array('tag' => 'span')),
	    	'ViewHelper',
	    	array('HtmlTag', array('tag' => 'div', 'class'=>'colorme'))
	    );
	    
	    public static $dojoDateDecorators = array(
	        'DijitElement'
	    );

	    public static $dojoDateLabelLeftDecorators = array(
	        'DijitElement',
	    	array('Label', array('tag' => 'span')),
	    	);

	    public static $dojoSubformDateDecorators = array(
	        'DijitElement',
	    	array(array ('data' => 'HtmlTag' ), array ('tag' => 'span', 'class' => 'refresh_date' ) )
	    );
	    
	    
	    //
	    // colored
	    //
		public static $elementDecoratorsColored = array(
	        'ViewHelper',
	    	array (array ('data' => 'HtmlTag' ), 
	    	array ('tag' => 'div', 'class' => 'colorme' ) )
	    );
	    
	    public static $simpleDecoratorsColored = array(
	        'ViewHelper',
	    	array(array ('data' => 'HtmlTag' ), array ('tag' => 'span') ),
	    	array(array ('colormediv' => 'HtmlTag' ), array('tag' => 'div', 'class'=>'colorme'))
	    );	
	    
	    public static $emptyDecoratorsColored = array(
	        'ViewHelper',
	    	array('HtmlTag', array('tag' => 'div', 'class'=>'colorme'))
	    );
	    public static $labelDecoratorsColored = array(
	        'ViewHelper',
	    	array('Label', array('tag' => 'span')),
	    	array('HtmlTag', array('tag' => 'div', 'class'=>'colorme'))
	    );
	    
	    public static $dojoDecoratorsColored = array(
	        'DijitElement',
	    	array('HtmlTag', array('tag' => 'div', 'class'=>'colorme'))
	    );
//		public static $dojoDateDecoratorsColored = array(
//	        'DijitElement',
//	    	array(array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'colorme', 'id'  => $this->getName() . '-element') ),
//		);
	    public static $dojoSubformDateDecoratorsColored = array(
	        'DijitElement',
	    	array(array ('refreshdiv' => 'HtmlTag' ), array ('tag' => 'span', 'class' => 'refresh_date' ) ),
	    	array(array ('colordiv' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'colorme' ) ),
	    );
	    public static $dojoSubformEditorDecorators = array(
	        'DijitElement',
	    	array(array ('data' => 'HtmlTag' ), array ('tag' => 'span', 'class' => 'refresh_editor' ) ),
	    );
	    public static $dojoSubformEditorDecoratorsColored = array(
	        'DijitElement',
	    	array(array ('refreshdiv' => 'HtmlTag' ), array ('tag' => 'span', 'class' => 'refresh_editor' ) ),
	    	array(array ('colordiv' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'colorme' ) ),
	    );
	    
	    
	    
	    
	}