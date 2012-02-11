<?php
/**
 * 
 * File Entity.php 
 *
 * This file includes definition of Entity class.
 * @category
 * 
 */
namespace Sejmometr;

abstract class  Entity
{
    protected $cache = array();
  
  
    /**
     * This method makes two things
     * - makes GET request to API server
     * - deserializes output (we assume that API is JSON based)
     *
     * @param string $url
     *   This url points to method of API
     *
     * @return 
     *   NULL value, if something went wrong
     *   deserialized API call value, if everything went OK
     */
    public static function request($url) {
        static $request_cache = array();
    
        if (isset($request_cache[$url])) {
            return $request_cache[$url];
        } else {
            $request = curl_init("http://api.sejmometr.pl/{$url}");
            curl_setopt($request, CURLOPT_HEADER, 0);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
      
            $data = curl_exec($request);
            curl_close($request);
      
            // return decoded serialized data
            $request_cache[$url] = json_decode($data);
      
            return $request_cache[$url];
        }
    }
  
  
    public static function retrieve($type, Array $object_ids = array()) {
        static $object_cache = array();
        $output = array();
    
        if (!class_exists($type)) {
            throw new \InvalidArgumentException("Class $type doesn't exist");
        }
    
        if (!isset($object_cache[$type])) {
            $object_cache[$type] = array();
        }
    
    
        // We don't want to create single entity multiple time
        // simple static cache will enforce singleton pattern
        foreach ($object_ids as $object_id) {
            if (!isset($object_cache[$type][$object_id])) {
                $object_cache[$type][$object_id] = new $type($object_id);
            }
    
            $output[$object_id] = $object_cache[$type][$object_id];
        }
    
        return $output;
    }
  
    abstract public function getInfo();
  
    public function __get($name) {
        if (!isset($this->cache['info'])) {
            $cache['info'] = $this->getInfo();
        }
  
        if (isset($this->cache['info']) && isset($this->cache['info'][$name])) {
            return $this->cache['info'][$name];
        } else {
            throw new \Exception(
                "This instance doesn't have $name field initialized"
            );
        }
    }

}
