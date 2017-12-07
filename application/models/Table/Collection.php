<?php

/**
 * Collection
 *
 * @author jlavere
 * @version
 */

class Model_Table_Collection extends Model_Table_AbstractIepForm
{
    /**
     * The default table name
     */
    protected $_name = 'collection';
    protected $_primary = 'id_collection';
    protected $_dependentTables = array(
        'Model_Table_CollectionItem',
    );

    function writevar1($var1,$var2) {
    
        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }
    
    public function get($userId, $collectionName='default') {
        $collection = $this->fetchRow(
            $this->select()
                ->where('id_personnel = ?', $userId)
                ->where('name = ?', $collectionName)
        );
        if(is_null($collection)) {
            return null;
        } else {
            return $collection;
        }
    }
    public function getItems($userId, $collectionName='default') {
        $collection = $this->get($userId, $collectionName);
        if(is_null($collection)) {
            return null;
        } else {
            return $collection->findDependentRowset('Model_Table_CollectionItem')->toArray();
        }
    }


}

