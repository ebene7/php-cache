<?php

namespace E7\Cache\Frontend;

use E7\Cache\Simple\CacheInterface;
use E7\Cache\Simple\ArrayCache;

class PassthruTest extends FrontendTestCase
{
    /**
     * @param   \E7\Cache\Simple\CacheInterface $cache
     * @param   callable $callback 
     * @param   array $options
     * @return  \E7\Cache\Simple\CacheInterface
     */
    protected function createObject(CacheInterface $cache = null, $callback = null, array $options = [])
    {
        if (null === $cache) {
            $cache = new ArrayCache();
        }

        if (null === $callback) {
            $callback = function() {};
        }
        
        $class = substr(get_class($this), 0, -4);
        return new $class($cache, $callback, $options);
    }
}
