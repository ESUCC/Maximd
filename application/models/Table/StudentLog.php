<?php
/**
 * Model_Table_StudentLog
 *
 * @author jlavere
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Table_StudentLog extends Model_Table_AbstractIepForm
{

//    protected $_name = 'iep_school';
//    protected $_primary = array('id_county', 'id_district', 'id_school');


    public function studentlogList($options)
    {
            $newQuery = false;
            $db = Zend_Registry::get('db');
            $select = $db->select()
               ->from( 'iep_log',
                       array('timestamp_created', 'type', 'name_personnel' => 'get_name_personnel(id_author)')
                     )
               ->where('id_student = ?', $options['id_student'])
               ->order('timestamp_created desc');
            $stmt = $db->query($select);

            if (!Model_CacheManager::isCached(Zend_Registry::get('searchCache'), $options['key'])) {
                $toCache = $stmt->fetchAll();
                // Save result to new cache file
                Model_CacheManager::getCacheForKey(Zend_Registry::get('searchCache'), $options['key'], $toCache);
                $newQuery = true;
            }


	    // Read cache file
            $count = count($cacheResults = Model_CacheManager::getCacheForKey(Zend_Registry::get('searchCache'), $options['key'], false));

            // read result from cache file
            $cacheResults = Model_CacheManager::getCacheForKey(Zend_Registry::get('searchCache'), $options['key'], false);

            return array(
                $newQuery,
                $paginator = Zend_Paginator::factory($cacheResults)->setItemCountPerPage($options['maxRecs'])->setCurrentPageNumber(empty($options['page']) ? 1 : $options['page']),
                $options['key'],
                $count
            );


    }
}
