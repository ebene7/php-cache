<?php

namespace E7\Cache\Simple;

use E7\Cache\Exception\InvalidArgumentException;

/**
 * Baseclass for simplecaches
 */
abstract class AbstractCache
    implements CacheInterface
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var integer|null
     */
    private $defaultLifetime;

    /**
     * Construct
     *
     * @param   array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
        $this->init();
    }

    /**
     * Initialize object after constructor has done its work
     */
    public function init()
    {
        $options = $this->getOptions();

        if (!empty($options['namespace'])) {
            $this->namespace = $options['namespace'];
        }

        if (!empty($options['default_lifetime'])) {
            $this->defaultLifetime = (int) $options['default_lifetime'];
        }
    }

    /**
     * @param   array $options
     * @return  \E7\Cache\Simple\AbstractCache
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge(
            $this->getDefaultOptions(),
            $options);

        return $this;
    }

    /**
     * @return  array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return  string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return  integer|null
     */
    public function getDefaultLifetime()
    {
        return $this->defaultLifetime;
    }

    /**
     * @return  array
     */
    public function getDefaultOptions()
    {
        return [
            'namespace' => '',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getMultiple($keys, $default = null)
    {
        $keys = $this->normalizeKeys($keys);
        $values = [];

        foreach ($keys as $key) {
            $values[$key] = $this->get($key, $default);
        }

        return $values;
    }

    /**
     * {@inheritDoc}
     */
    public function setMultiple($values, $ttl = null)
    {
        $values = $this->normalizeValues($values);
        $success = true;

        foreach ($values as $key => $value) {
            $success = $this->set($key, $value, $ttl) && $success;
        }

        return $success;
    }

    /**
     * {@inheritDoc}
     */
    public function deleteMultiple($keys)
    {
        $keys = $this->normalizeKeys($keys);
        $success = true;

        foreach ($keys as $key) {
            $success = $this->delete($key) && $success;
        }

        return $success;
    }

    /**
     * @param   string $key
     * @return  string
     * @throws  \E7\Cache\Exception\InvalidArgumentException
     */
    protected function getIdByKey($key)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException('The given key is not a valid string.');
        }

        return $this->normalizeKey($key);
    }

    /**
     * @param   string $key
     * @return  string
     */
    protected function normalizeKey($key)
    {
        return $key;
    }

    /**
     * @param   array|\Traversable $keys
     * @return  array
     * @throws  \E7\Cache\Exception\InvalidArgumentException
     */
    protected function normalizeKeys($keys)
    {
        if ($keys instanceof \Traversable) {
            $keys = iterator_to_array($keys, false);
        }

        if (!is_array($keys)) {
            throw new InvalidArgumentException(sprintf('Cache keys must be array or Traversable.'));
        }

        return $keys;
    }

    /**
     * @param   array|\Traversable $keys
     * @return  array
     * @throws  \E7\Cache\Exception\InvalidArgumentException
     */
    protected function normalizeValues($values)
    {
        if ($values instanceof \Traversable) {
            $values = iterator_to_array($values, true);
        }

        if (!is_array($values)) {
            throw new InvalidArgumentException(sprintf('Values must be array or Traversable.'));
        }

        return $values;
    }

    /**
     * @param   integer|null $ttl
     * @return  integer|null
     * @throws  \E7\Cache\Exception\InvalidArgumentException
     */
    protected function normalizeTtl($ttl = null)
    {
        if (null === $ttl) {
            return null;
        }

        $ttl = abs(intval($ttl));

        if (empty($ttl)) {
            throw new InvalidArgumentException('TTL must be an positive integer value.');
        }

        return $ttl;



        //--

        if (null === $ttl) {
            return $this->defaultLifetime;
        }
        if ($ttl instanceof \DateInterval) {
            $ttl = (int) \DateTime::createFromFormat('U', 0)->add($ttl)->format('U');
        }
        if (is_int($ttl)) {
            return 0 < $ttl ? $ttl : false;
        }
        throw new InvalidArgumentException(sprintf('Expiration date must be an integer, a DateInterval or null, "%s" given', is_object($ttl) ? get_class($ttl) : gettype($ttl)));
    }

    /**
     * @param   string $key
     * @return  boolean
     */
    protected function cleanExpired($key)
    {
        return false;
    }
}

