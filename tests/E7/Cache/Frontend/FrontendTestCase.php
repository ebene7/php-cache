<?php

namespace E7\Cache\Frontend;

use E7\Cache\Simple\CacheInterface;
use E7\Cache\Simple\ArrayCache;

abstract class FrontendTestCase extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $frontend = $this->createObject();
        $this->assertInstanceOf(AbstractFrontend::class, $frontend);
    }

    /**
     * @param   \E7\Cache\Simple\CacheInterface $cache
     * @param   array $options
     * @return  \E7\Cache\Simple\CacheInterface
     */
    protected function createObject(CacheInterface $cache = null, array $options = [])
    {
        if (null === $cache) {
            $cache = new ArrayCache();
        }

        $class = substr(get_class($this), 0, -4);
        return new $class($cache, $options);
    }
}

