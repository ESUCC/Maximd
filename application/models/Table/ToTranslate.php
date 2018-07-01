<?php

/**
 * to_translate
 *  
 * @author sbennett
 * @version 
 */
	
require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_ToTranslate extends Model_Table_AbstractIepForm
{
	/**
	 * The default table name 
	 */
    protected $_name = 'to_translate';
	protected $_primary = array('to_translate_id');
	protected $_translationIndex = array('es' => 0, 'en' => 1);
	
	public function getOpenKeys() {
	    return $this->fetchAll(
	        $this->select()
	             ->where('to_translate_status = ?', 'open'))->toArray();
	}
	
	public function flagKeysToTranslate(Form_TranslationGateKeeper $form, $keys, $formNumber, $page, $user_id) {
	    foreach ($this->_translationIndex AS $key => $value) {
    	    for ($i=1;$i<=count($keys);$i++) {
    	        
    	        $row = $this->fetchRow(
        	        $this->select()
        	        ->where('to_translate_key = ?', $form->getValue('key_'.$i))
        	        ->where('to_translate_locale = ?', $key)
        	        ->where('to_translate_form = ?', $formNumber)
        	        ->where('to_translate_page = ?', $page)
    	            ->where('to_translate_status = ?','open'));
    	        
    	        if ('t' == $form->getValue($key.'_flag_'.$i) && empty($row['to_translate_id'])) {
    	            $sql = "INSERT INTO to_translate 
    	                    (to_translate_form, to_translate_page, to_translate_locale,
    	                     to_translate_key, to_translate_status, id_author_last_mod)
    	                    VALUES (?,?,?,?,?,?)";
    	            $this->getAdapter()->query($sql, array(
    	                $formNumber,
    	                $page,
    	                $key,
    	                $form->getValue('key_'.$i),
    	                'open',
    	                $user_id));
    	        } else {
    	            if (!empty($row['to_translate_id']) && 't' != $form->getValue($key.'_flag_'.$i)) {
        	            $row->to_translate_status = 'translated';
        	            $row->save();
    	            }
    	        }
    	    }
	    }
	}
}