<?php

namespace E7\Cache\Frontend;

use E7\Cache\Simple\CacheInterface;
use E7\Cache\Simple\ArrayCache;

class ObjectTest extends FrontendTestCase
{
    /**
     * @param   \E7\Cache\Simple\CacheInterface $cache
     * @param   object $object 
     * @param   array $options
     * @return  \E7\Cache\Simple\CacheInterface
     */
    protected function createObject(CacheInterface $cache = null, $object = null, array $options = [])
    {
        if (null === $cache) {
            $cache = new ArrayCache();
        }

        if (null === $object) {
            $object = new \stdClass();
        }
        
        $class = substr(get_class($this), 0, -4);
        return new $class($cache, $object, $options);
    }
}