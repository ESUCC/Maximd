<?php
/**
 * 
 * Handles access to the cache
 * 
 * @author stevebennett
 * @version 1.0
 *
 */ 
class Model_CacheManager
{
    /**
     * 
     * Simple function to determine if cache is 
     * set for key name.
     * 
     * @param Zend_Cache_Core $cacheObject
     * @param string $cacheKey
     * 
     * @return bool
     */
    public static function isCached(Zend_Cache_Core $cacheObject, $cacheKey)
    {
        if(false !== $cacheObject->test($cacheKey))
           return true;
        
        return false;
   }

/*Remove Chache file by Key*/

    public static function removeCache(Zend_Cache_Core $cacheObject, $cacheKey)
    {

	$cacheObject->remove($cacheKey);
        return true;
   }

    /**
     * 
     * Function for setting / retreiving cache. 
     * 
     * @param Zend_Cache_Core $cacheObject
     * @param string $cacheKey
     * @param string $toCache
     * 
     * @return cache 
     */
    public static function getCacheForKey(Zend_Cache_Core $cacheObject, $cacheKey, $toCache = false)
    {
        if (false !== $toCache)
            $cacheObject->save($toCache, $cacheKey);

        return $cacheObject->load($cacheKey);
    }
    
    /**
     * Generates a random cache key
     */
    public static function generateCacheKey() {
    
        $id = uniqid();
    
        $id = base_convert($id, 16, 2);
        $id = str_pad($id, strlen($id) + (8 - (strlen($id) % 8)), '0', STR_PAD_LEFT);
        $chunks = str_split($id, 8);
        $id = array();
        foreach ($chunks as $key => $chunk) {
            if ($key & 1) {  // odd
                array_unshift($id, $chunk);
            } else {         // even
                array_push($id, $chunk);
            }
        }
        return base_convert(implode($id), 2, 36);
    }
    
}
