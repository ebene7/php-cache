<?php

namespace E7\Cache\Exception;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $ex = new InvalidArgumentException();
        $this->assertInstanceOf(\InvalidArgumentException::class, $ex);  /* test base class */
        $this->assertInstanceOf(\Psr\SimpleCache\InvalidArgumentException::class, $ex);   /* test interface  */
    }
}