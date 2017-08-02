<?php

namespace E7\Cache\Exception;

class CacheExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $ex = new CacheException();
        $this->assertInstanceOf(\Exception::class, $ex); /* test base class */
        $this->assertInstanceOf(\Psr\SimpleCache\CacheException::class, $ex);  /* test interface  */
    }
}
