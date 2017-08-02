<?php

namespace E7\Cache\Simple;

/**
 * ArrayCache
 */
class ArrayCache extends AbstractCache
{
    /**
     * @var array
     */
    private $values = [];

    /**
     * @var array
     */
    private $expiries = [];

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        $id = $this->getIdByKey($key);
        $this->cleanExpired($key);
        return !empty($this->values[$id]) ? $this->values[$id] : $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $ttl = null)
    {
        $id = $this->getIdByKey($key);

        if (null !== $ttl) {
            $ttl = $this->normalizeTtl($ttl);
            $expiresAt = time() + $ttl;
            $this->expiries[$id] = $expiresAt;
        }

        $this->values[$id] = $value;
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        $id = $this->getIdByKey($key);
        unset($this->values[$id], $this->expiries[$id]);
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->values = [];
        $this->expiries = [];
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        $id = $this->getIdByKey($key);
        $this->cleanExpired($key);
        return isset($this->values[$id]);
    }

    /**
     * {@inheritDoc}
     */
    protected function cleanExpired($key)
    {
        $id = $this->getIdByKey($key);

        if (!empty($this->expiries[$id]) && $this->expiries[$id] <= time()) {
            $this->delete($key);
            return true;
        }

        return false;
    }
}