<?php
/**
 * App_Form_DecoratorHelper
 *
 * @category   Zend
 * @package    App_Form
 */
class App_Form_DecoratorHelper 
{
    
	static function inlineElement($dojo = false, $id = null, $colorMe = true)
    {
    	// description
        $decorators = array(
            array('Description', array('tag' => 'span')),
        );
        
        // element output
        if($dojo) {
        	array_push($decorators, 'DijitElement');
        } else {
        	array_push($decorators, 'ViewHelper');
        }
        
        // label
        array_push($decorators, array('Label', array('tag' => 'span')));
        
        // colorme span
        if($id != null && $colorMe) {
        	array_push($decorators, array('HtmlTag', array ('tag' => 'span', 'class' => 'colorme', 'id'  => $id . '-colorme')) );
        }
            
        return $decorators;
    } 

    static function inlineEditor($dojo = false, $id = null, $colorMe = true)
    {
        // element output
        if($dojo) {
            array_push($decorators, 'DijitElement');
        } else {
            array_push($decorators, 'ViewHelper');
        }
        
        // colorme span
        if($id != null && $colorMe) {
//            array_push($decorators, array('HtmlTag', array ('tag' => 'span', 'class' => 'colorme', 'id'  => $id . '-colorme')) );
        }
        return $decorators;
    } 
    
    static function descLabelViewHelper($dojo = false, $id = null, $colorMe = true)
    {
        $decorators = array(
	        array('Description', array('tag' => 'span')),
	        array('Label', array('tag' => 'span')),
	        'ViewHelper',
        );
        return $decorators;
    } 

    
    static function checkBoxLeft($dojo = false, $id = null, $colorMe = true)
    {
        $decorators = array(
            'ViewHelper',
            array('label', array('tag' => 'span', 'placement' => 'append')),
            array('Description', array('tag' => 'span')),
            array('HtmlTag', array ('tag' => 'div', 'class' => 'colorme', 'id'  => $id . '-colorme')), 
            array (array ('data' => 'HtmlTag' ), array ('tag' => 'span') )
        );  
        return $decorators;
    }

    
}
