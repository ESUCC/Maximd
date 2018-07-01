<?php

	class My_Classes_Decorators_Colored {
		
		public static $elementDecorators = array(
	        'ViewHelper',
	    	array (array ('data' => 'HtmlTag' ), 
	    	array ('tag' => 'div', 'class' => 'colorme' ) )
	    );
	    
	    public static $dojoDecorators = array(
	        'DijitElement',
	    );
	    
	    public static $dojoDecoratorsColored = array(
	        'DijitElement',
	    	array('HtmlTag', array('tag' => 'div', 'class'=>'colorme'))
	    );
	    
	    public static $simpleDecorators = array(
	        'ViewHelper',
	    	array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
	    );	
	    
	    public static $emptyDecorators = array(
	        'ViewHelper',
	    	array(array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'colorme' ) ),
		);
	    	    	
	    public static $labelDecorators = array(
	        'ViewHelper',
	    	array('Label', array('tag' => 'span'))
	    );

		public static $dojoDateDecorators = array(
	        'DijitElement',
	    	array(array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'colorme' ) ),
	    );
		public static $dojoSubformDateDecorators = array(
	        'DijitElement',
	    	array(array ('data' => 'HtmlTag' ), array ('tag' => 'span', 'class' => 'refresh_date' ) ),
	    	array(array ('data' => 'HtmlTag' ), array ('tag' => 'div', 'class' => 'colorme' ) ),
	    );
	    
	}