<?php

namespace E7\Cache\Frontend;

use Psr\SimpleCache\CacheInterface;

/**
 * Object frontend
 */
class Object extends Callback
{
    use ObjectTrait;

    /**
     * Constructor
     *
     * @param   \Psr\SimpleCache\CacheInterface $cache
     * @param   array $options
     * @param   object $object
     */
    public function __construct(CacheInterface $cache, $object, array $options = [])
    {
        parent::__construct($cache, $options);
        $this->setObject($object);
    }
}