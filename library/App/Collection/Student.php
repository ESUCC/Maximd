<?php

class App_Collection_Student extends Model_Table_Collection
{
    function writevar1($var1,$var2) {

        ob_start();
        var_dump($var1);
        $data = ob_get_clean();
        $data2 = "-------------------------------------------------------\n".$var2."\n". $data . "\n";
        $fp = fopen("/tmp/textfile.txt", "a");
        fwrite($fp, $data2);
        fclose($fp);
    }

    public function getNames($userId, $collectionName='default', $additionalFields = array()) {

        $returnArray = array();
        /**
         * collection of students
         */
        $model = new Model_Table_Collection();
        $collectionItems = $model->getItems($userId, $collectionName);
      //  $this->writevar1($collectionItems,'these re the colleciton items');
        $modelStudent = new Model_Table_StudentTable();
        if (count($collectionItems) > 0) {
            foreach ($collectionItems as $collectionItem) {

                $studentArr = $modelStudent->studentInfo($collectionItem['value']);
           //     $this->writevar1($studentArr,'student array');
                if (count($studentArr) == 0) {
                    continue;
                }
                $student = $studentArr[0];
                if (!is_null($student)) {
                    $tempData = array(
                        'id' => $student['id_student'],
                        'name' => $student['name_student_full']
                    );
                    foreach($additionalFields as $key => $fieldName) {
                        if(isset($student[$fieldName])) {
                            $tempData[$key] = $student[$fieldName];
                        }
                    }
                    $returnArray[] = $tempData;
                }
            }
        }
        return $returnArray;
    }

    public function add($userId, $value, $collectionName='default') {
        $modelCollection = new Model_Table_Collection();
        $collection = $modelCollection->get($userId, $collectionName);
        if(false==$collection) {
            $collectionId = $modelCollection->insert(array('id_personnel'=>$userId, 'name'=>$collectionName));
            $collection = $modelCollection->get($userId, $collectionName);
        }
        if(!is_null($collection)) {
            /**
             * confirm it's not already there
             */
            foreach ($collection->findDependentRowset('Model_Table_CollectionItem') as $collectionItem) {
                if($value == $collectionItem->value) {
                    return null;
                }
            }

            $modelCollectionItems = new Model_Table_CollectionItem();
            $result = $modelCollectionItems->insert(array('id_collection'=>$collection->id_collection, 'value'=>$value));
            return $result;
        } else {
            return null;
        }
    }
    public function collectionNameExists($userId, $collectionName)
    {
        $modelCollection = new Model_Table_Collection();
        $collection = $modelCollection->get($userId, $collectionName);
        if(count($collection)) {
            return true;
        } else {
            return false;
        }
    }
    public function addCollection($userId, $collectionName='default') {
        $modelCollection = new Model_Table_Collection();
        return $modelCollection->insert(
            array(
                'id_personnel' => $userId,
                'name' => $collectionName
            )
        );
    }

    public function remove($userId, $value, $collectionName='default') {
        $modelCollection = new Model_Table_Collection();
        $collection = $modelCollection->get($userId, $collectionName);

        if(!is_null($collection)) {
            $modelCollectionItems = new Model_Table_CollectionItem();

            $where = $modelCollectionItems->getAdapter()->quoteInto('id_collection = ?', $collection->id_collection) . ' and ' .
                $modelCollectionItems->getAdapter()->quoteInto('value = ?', $value);
            $result = $modelCollectionItems->delete($where);
            return $result;
        } else {
            return null;
        }
    }
    public function removeAllCollectionItems($userId, $collectionName) {
        $modelCollection = new Model_Table_Collection();
        $collection = $modelCollection->get($userId, $collectionName);
        if(!is_null($collection)) {
            $modelCollectionItems = new Model_Table_CollectionItem();
            $where = $modelCollectionItems->getAdapter()->quoteInto('id_collection = ?', $collection->id_collection);
            $result = $modelCollectionItems->delete($where);
            return $result;
        } else {
            return null;
        }
    }
    public function removeCollection($userId, $collectionName) {
        $modelCollection = new Model_Table_Collection();
        $collection = $modelCollection->get($userId, $collectionName);
        if(!is_null($collection)) {
            $result = $collection->delete();
            return $result;
        } else {
            return false;
        }
    }
    public function removeCollectionById($userId, $collectionId) {
        $modelCollection = new Model_Table_Collection();
        $select = $modelCollection->select()->where("id_personnel = ?", $userId)
            ->where("id_collection = ?", $collectionId);
        $collection = $modelCollection->fetchRow($select);
        if(!is_null($collection)) {
            $this->removeAllCollectionItems($userId, $collection->name);
            $result = $collection->delete();
            return $result;
        } else {
            return false;
        }
    }

    public function getMyCollections($userId)
    {
        $modelCollection = new Model_Table_Collection();
        $collections = $modelCollection->fetchAll(
            $modelCollection->select()
                ->from(
                    'collection',
                    array(
                        '*',
                        'id' => 'id_collection'
                    )
                )
                ->where("id_personnel = ?", $userId)
                ->order(array('name asc'))
        );

        if (!is_null($collections)) {
            return $collections;
        } else {
            return null;
        }
    }

}