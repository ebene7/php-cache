<?php

namespace E7\Cache\Simple;

abstract class CacheTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider    providerSetValuesViaOptions
     * @param           array $options
     * @param           array $expected
     */
    public function testSetValuesViaOptions(array $options, array $expected)
    {
        $cache = $this->createObject($options);
        $value = call_user_func([$cache, $expected['method']]);
        $this->assertEquals($expected['value'], $value);
    }

    /**
     * @return  array
     */
    public function providerSetValuesViaOptions()
    {
        return [
            [
                ['namespace' => 'test-namespace'],
                [
                    'method' => 'getNamespace',
                    'value' => 'test-namespace',
                ]
            ],
            [
                ['default_lifetime' => 42],
                [
                    'method' => 'getDefaultLifetime',
                    'value' => 42,
                ]
            ]
        ];
    }

    public function testInstanceOf()
    {
        $cache = $this->createObject();

        $this->assertInstanceOf(\Psr\SimpleCache\CacheInterface::class, $cache);
        $this->assertInstanceOf(\E7\Cache\Simple\CacheInterface::class, $cache);
    }

    public function testSetAndGet()
    {
        $cache = $this->createObject();
        $cacheKey = 'test-cache-key-' . time();
        $testValue = 'test-value-' . time();
        $ttl = 1;

        // test for empty value
        $this->assertNull($cache->get($cacheKey));

        // test set expect works and returns true
        $result = $cache->set($cacheKey, $testValue, $ttl);
        $this->assertTrue($result);

        // test has expected true
        $this->assertTrue($cache->has($cacheKey));
        
        // test get, expect the given $testValue
        $this->assertEquals($testValue, $cache->get($cacheKey));

        // test get after livetime and expect null
        sleep($ttl + 1);
        $this->assertNull($cache->get($cacheKey));
    }

    public function testGetWithDefaultValue()
    {
        $cache = $this->createObject();
        $cacheKey = 'test-cache-key-' . time();
        $defaultValue = 'test-default-value-' . time();

        // test for empty value
        $this->assertNull($cache->get($cacheKey));

        // test for default value
        $this->assertEquals($defaultValue, $cache->get($cacheKey, $defaultValue));
    }

    /**
     * @param   array $options
     * @return  \E7\Cache\Simple\CacheInterface
     */
    protected function createObject(array $options = [])
    {
        $class = substr(get_class($this), 0, -4);
        return new $class($options);
    }
}

