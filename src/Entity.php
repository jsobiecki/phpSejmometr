<?php
/**
 * This file includes implementation of base abstract class for all
 * sejmometr api classes - Entity
 *
 * PHP version 5.3
 * 
 * @abstract 
 * @category  Sejmometr
 * @package   Sejmometr
 * @author    Jarek Sobiecki <jsobiecki@itparpanie.pl> 
 * @copyright 2012 Jarek Sobiecki <jsobiecki@itparpanie.pl>
 * @license   LGPL {@link http://www.gnu.org/licenses/lgpl-3.0.html}
 * @version   GIT: $id$
 * @link      http://github.com/harijari/phpSejmometr
 */
namespace Sejmometr;

/**
 * Entity 
 * 
 * @abstract 
 * @category  Sejmometr
 * @package   Sejmometr
 * @author    Jarek Sobiecki <jsobiecki@itparpanie.pl>
 * @copyright 2012 Jarek Sobiecki <jsobiecki@itparpanie.pl>
 * @license   LGPL {@link http://www.gnu.org/licenses/lgpl-3.0.html}
 * @link      http://github.com/harijari/phpSejmometr
 */
abstract class  Entity
{
    protected $cache = array();
  
  
    /**
     * This method makes two things
     * - makes GET request to API server
     * - deserializes output (we assume that API is JSON based)
     *
     * @param string $url This url points to method of API
     *
     * @return 
     *   NULL value, if something went wrong
     *   deserialized API call value, if everything went OK
     */
    public static function request($url) 
    {
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
  
  
    /**
     * retrieve 
     * 
     * @param mixed $type       Created class type name
     * @param Array $object_ids Array with id of class of $type instances
     *
     * @static
     * @access public
     * @return Array Array of class $type instances. 
     */
    public static function retrieve($type, Array $object_ids = array()) 
    {
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


    /**
     * getInfo This method retrieves basic information about entity. 
     * 
     * @abstract
     * @access public
     * @return mixed This method returns basic information about entity. Mostly
     * it will be array with various data.
     */
    abstract public function getInfo();
  
    /**
     * __get Implementation of magic __get method. In general, it will
     *  try to retrieve data from API server. If getInfo return value included
     *  field with $name as key, this value will be returned. If no, method
     *  will fail and throw exception
     * 
     * @param mixed $name Name of undefined property in class
     *
     * @access public
     * @return mixed Value of field with getInfo() method
     */
    public function __get($name) 
    {
        if (!isset($this->cache['info'])) {
            $this->cache['info'] = $this->getInfo();
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
