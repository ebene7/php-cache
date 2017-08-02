<?php

namespace E7\Cache\Frontend;

use Psr\SimpleCache\CacheInterface;

/**
 * Passthru fronend
 */
class Passthru extends Callback
{
    use PassthruTrait;

    /**
     * Constructor
     *
     * @param   \Psr\SimpleCache\CacheInterface $cache
     * @param   callable $callback
     * @param   array $options
     */
    public function __construct(CacheInterface $cache, $callback, array $options = [])
    {
        parent::__construct($cache, $options);
        $this->setCallback($callback);
    }
}