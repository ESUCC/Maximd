<?php

/**
 * FormatColumns
 *  
 * @author sbennett
 * @version 
 */

class Model_Table_FormatColumns extends Zend_Db_Table_Abstract
{
    protected $_name = 'iep_format_columns';
    protected $_primary = 'id_format_columns';
    
    /**
     * 
     * @param string $format
     */
    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    public function getFormatColumns($format) {
        switch ($format) {
            
            case 'School List':
                return array(
                    'formatColumn0' => 'id_student',
                    'formatColumn1' => 'name_full',
                    'formatColumn2' => 'name_county',
                    'formatColumn3' => 'name_district',
                    'formatColumn4' => 'name_school',
                    'formatColumn5' => 'role');
                break;
            case 'Phonebook':
                return array(
                        'formatColumn0' => 'name_full',
                        'formatColumn1' => 'manager',
                        'formatColumn2' => 'address',
                        'formatColumn3' => 'phone',
                        'formatColumn4' => '',
                        'formatColumn5' => '');
                break;
            case 'MDT':
                return array(
                        'formatColumn0' => 'id_student',
                        'formatColumn1' => 'name_full',
                        'formatColumn2' => 'iep',
                        'formatColumn3' => 'mdt',
                        'formatColumn4' => '',
                        'formatColumn5' => '');
                break;
            case 'Mailing Label Data':
                return array(
                        'formatColumn0' => 'name_full',
                        'formatColumn1' => 'address',
                        'formatColumn2' => '',
                        'formatColumn3' => '',
                        'formatColumn4' => '',
                        'formatColumn5' => '');
                break;
            default:
            return $this->fetchRow($this->select()->where('id_format_columns = ?', $format))->toArray();
        }
    }
    
    public function getFormatPairs(Zend_Session_Namespace $user)
    {
        $id = isset($user->sessIdUser) ? $user->sessIdUser : $user->id_guardian;
     //   $this->writevar1($this->_name,'this is the name');
      //  $this->writevar1($id,'this is the id');
        
        return $this->getAdapter()->fetchPairs(
                $this->select()->from(
                        $this->_name, 
                        array('id_format_columns', 'format_name')
                        )->where('id_user = ?', $id)
                );
    }
    
    public function addNewUserSearchFormat(Zend_Session_Namespace $user, $format)
    {
        $row = $this->createRow();
        $row->format_name = $format;
        $row->id_user = $user->sessIdUser;
        $row->save();
        return $row->toArray();
    }
    
    public function updateColumnForFormat($format, $column, $value) {
        $row = $this->fetchRow($this->select()->where('id_format_columns = ?', $format));
        $row->$column = $value;
        $row->save();
        return true;
    }
}

