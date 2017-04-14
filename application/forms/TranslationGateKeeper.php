<?php
class Form_TranslationGateKeeper extends Zend_Form {
    
    /**
     * (non-PHPdoc)
     * @see Zend_Form::init()
     */
    public function __construct($keys)
    {
        $max_key = 0;
        
        if (!empty($keys)) {
            foreach ($keys AS $key => $keyLocalValues)
            {
                $max_key++;
    
                $tmpName = 'key_'.$max_key;
                $belongsTo = 'Keys';
                $this->$tmpName = new App_Form_Element_Hidden($tmpName);
                $this->$tmpName->setValue($key);
                $this->$tmpName->setBelongsTo($belongsTo);
                
                foreach ($keyLocalValues AS $locale => $value) {
                    $tmpName = $locale.'_'.$max_key;
                    $belongsTo = 'Locale_'.$locale;
                    $this->$tmpName = new App_Form_Element_Textarea($tmpName);
                    $this->$tmpName->setValue($value);
                    $this->$tmpName->setBelongsTo($belongsTo);
                    //$this->$tmpName->setAllowEmpty(false);
                    //$this->$tmpName->setRequired(true);
                    $this->$tmpName->setErrorMessages(array('You must enter a value for '.$locale.' locale on key "'.$key.'"'));
                    
                    $tmpName = $locale.'_flag_'.$max_key;
                    $belongsTo = 'Flag_Locale_'.$locale;
                    $this->$tmpName = new App_Form_Element_Checkbox($tmpName);
                    $this->$tmpName->setBelongsTo($belongsTo);
                }
            }
        }
        
        $this->updateKeys = new Zend_Form_Element_Submit('updateKeys',
        array('Label' => 'Update Keys'));
        $this->updateKeys->setAttrib('class', 'btn btn-success');
        
        $this->setDecorators(array(array('ViewScript',
                array('viewScript' =>
                        'translation/gate-keeper.phtml'))));
    }
}