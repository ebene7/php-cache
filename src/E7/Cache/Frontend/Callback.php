<?php

namespace E7\Cache\Frontend;

/**
 * Callback frontend
 */
class Callback extends AbstractFrontend
{
    /**
     * @param   callable $callback
     * @param   array $args
     * @param   integer $ttl
     * @return  mixed
     */
    public function call(callable $callback, array $args = [], $ttl = null)
    {
        $cache = $this->getCache();
        $key = $this->getCachekey($callback, $args);
        $ttl = $ttl ?: $this->getDefaultTtl();

        if (!$cache->has($key)) {
            $result = call_user_func_array($callback, $args);
            $cache->set($key, $result, $ttl);
        }

        return $cache->get($key);
    }

    /**
     * @param   callable $callback
     * @param   array $args
     * @return  string
     */
    public function getCachekey(callable $callback, array $args = [])
    {
        $callbackStr = $this->convertCallableToString($callback);
        $this->ksortr($args);
        return md5($callbackStr . '#' . serialize($args));
    }

    /**
     * @param   mixed $callback
     * @return  string
     */
    protected function convertCallableToString($callback)
    {
        if (is_string($callback)) {
            return $callback;
        }

        if (is_object($callback)) {
            return spl_object_hash($callback);
        }

        if (is_array($callback)) {
            $parts = [];

            foreach ($callback as $item) {
                $parts[] = $this->convertCallableToString($item);
            }

            return implode('::', $parts);
        }
    }

    protected function ksortr(&$array, $sortFlags = SORT_REGULAR)
    {
        if (!is_array($array)) {
            return false;
        }

        ksort($array, $sortFlags);

        foreach ($array as &$branch) {
            $this->ksortr($branch, $sortFlags);
        }

        return true;
    }

}

