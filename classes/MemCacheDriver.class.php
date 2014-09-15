<?php

if (!defined("INIT"))
    die('Direct access to this file not allowed');

/**
 * Cache Driver storing data in Memcache
 * @access public
 * @license GPL
 * @author Latik, <latkovsky@yandex.ru>
 * @version 0.5
 * @package Doodstrap
 */
class MemCacheDriver implements CacheDriver {

    private $_memcache = null;

    /**
     * Cache class counstructor for memcached
     * @param array $options Options array
     * @throws Exception
     */
    public function __construct($options = null) {
        if ($options == null) {
            $options = array(
                'memcache' => array(
                    'server' => array(
                        'host' => "localhost", 'port' => 11211)
                )
            );
        }
        if (!class_exists('Memcache'))
            throw new Exception('memcached is not installed');
        if (isset($options['memcache']) && is_array($options['memcache'])) {
            $this->_memcache = new Memcache;
            foreach ($options['memcache'] as $server) {
                if (!is_array($server) || !isset($server['host'])) {// host должен быть указан
                    continue;
                }
                $server['port'] = isset($server['port']) ? (int) $server['port'] : 11211;
                $server['persistent'] = isset($server['persistent']) ? (bool) $server['persistent'] : true;
                if (!$this->_memcache->addServer($server['host'], $server['port'], $server['persistent']))
                    throw new Exception('cannot add memcache server, verify that memcached running on localhost:11211');
            }
        }
    }

    /**
     * Set new cache variable
     * @param string $groupName Name of cache group
     * @param string $identifier Name of cache
     * @param mixed $data Data to be cached
     * @param int $ttl Time to live in seconds
     */
    public function set($groupName, $identifier, $data, $ttl = 300) {
        if (!$this->_memcache->replace($this->getGroupKey($groupName) . $identifier, $data, MEMCACHE_COMPRESSED, $ttl)) {
            $this->_memcache->set($this->getGroupKey($groupName) . $identifier, $data, MEMCACHE_COMPRESSED, $ttl);
        }
    }

    /**
     * Gets data from cache
     * @param string $groupName Name of cache group
     * @param string $identifier Name of cache
     * @return mixed Data from cache
     */
    public function get($groupName, $identifier) {
        return $this->_memcache->get($this->getGroupKey($groupName) . $identifier);
    }

    /**
     * Clears cache with given group and name
     * @param string $groupName Name of cache group
     * @param string $identifier Name of cache
     */
    public function clearCache($groupName, $identifier) {
        $this->_memcache->delete($this->getGroupKey($groupName) . $identifier);
    }

    /**
     * Clears cache of given group
     * @param string $groupName Name of cache group
     */
    public function clearGroupCache($groupName) {
        $this->_memcache->increment("$groupName");
    }

    /**
     * Clears all cache
     */
    public function clearAllCache() {
        $this->_memcache->flush();
    }

    /**
     * Adds new memcache server
     * @param string $host Host
     * @param string $port Port
     * @param string $weight Weight
     * @return object Server instance
     */
    public function addServer($host = localhost, $port = 11211, $weight = 10) {
        return $this->_memcache->addServer($host, $port, true, $weight);
    }

    /**
     * Gets group key from memcache server
     * @param string $groupName Name of cache group
     * @return string Group key
     */
    private function getGroupKey($groupName) {
        $gr_key = $this->_memcache->get("$groupName");
        if ($gr_key === false)
            $this->_memcache->set("$groupName", rand(1, 10000));
        return $gr_key;
    }

    /**
     * Gets modification time of group, unimplemented
     * @param string $groupName Name of cache group
     * @param string $identifier Name of cache
     * @return int UNIX timestamp of cache modification time
     */
    public function modificationTime($groupName, $identifier) {
        return time();
    }

    /**
     * Checks that cache exists or no
     * @param string $groupName Name of cache group
     * @param string $identifier Name of cache
     * @return boolean True or false
     */
    public function exists($groupName, $identifier) {
        if ($this->_memcache->get($this->getGroupKey($groupName) . $identifier))
            return true;
        else
            return false;
    }

}

?>