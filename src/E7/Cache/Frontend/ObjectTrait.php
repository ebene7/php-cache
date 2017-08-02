<?php

namespace E7\Cache\Frontend;

use E7\Cache\Exception\InvalidArgumentException;

trait ObjectTrait
{
    /**
     * @var object
     */
    private $object;

    /**
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, array $args)
    {
        return $this->call([$this->object, $method], $args);
    }

    /**
     * @param   object $object
     * @return  \E7\Cache\Frontend\AbstractFrontend
     * @throws  \E7\Cache\Exception\InvalidArgumentException
     */
    public function setObject($object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('Parameter 1 is expected to be an object.');
        }

        $this->object = $object;
        return $this;
    }
}