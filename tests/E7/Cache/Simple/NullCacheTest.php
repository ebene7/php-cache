<?php

namespace E7\Cache\Simple;

class NullCacheTest extends CacheTestCase
{
    public function testSetAndGet()
    {
        $cache = $this->createObject();
        $cacheKey = 'test-cache-key-' . time();
        $testValue = 'test-value-' . time();
        $ttl = 1;

        // test for empty value
        $this->assertNull($cache->get($cacheKey));

        // test set expect false
        $result = $cache->set($cacheKey, $testValue, $ttl);
        $this->assertFalse($result);

        // test has expected false
        $this->assertFalse($cache->has($cacheKey));

        // test get, expect null (default value)
        $this->assertNull($cache->get($cacheKey));

        // test get after livetime and still expect null
        sleep($ttl + 1);
        $this->assertNull($cache->get($cacheKey));
    }

    public function testSetAndGetWithEmptyArray()
    {
        $cache = $this->createObject();
        $cacheKey = 'test-cache-key-' . time();

        // test for empty value
        $this->assertNull($cache->get($cacheKey));

        // set empty array
        $cache->set($cacheKey, []);

        // test for array value
        $this->assertFalse($cache->has($cacheKey));

        $value = $cache->get($cacheKey);
        $this->assertNull($value);
    }
}