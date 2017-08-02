<?php

namespace E7\Cache\Frontend;

use Psr\SimpleCache\CacheInterface;

/**
 * Baseclass for cache frontend
 */
abstract class AbstractFrontend
{
    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $cache;

    /**
     * @var integer|null
     */
    private $defaultTtl;

    /**
     * Constructor
     *
     * @param   \Psr\SimpleCache\CacheInterface $cache
     */
    public function __construct(CacheInterface $cache, array $options = [])
    {
        $this->cache = $cache;
        $this->setOptions($options);
    }

    /**
     * @param   array $options
     * @return  \E7\Cache\Frontend\AbstractFrontend
     */
    public function setOptions(array $options = [])
    {
        if (!empty($options['default_ttl'])) {
            $this->defaultTtl = (int) $options['default_ttl'];
        }

        return $this;
    }

    /**
     * @return  \Psr\SimpleCache\CacheInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return  integer|null
     */
    public function getDefaultTtl()
    {
        return $this->defaultTtl;
    }

    /**
     * @param   array $args
     * @return  string
     */
    public function getCachekey(array $args)
    {
        return '';
    }
}