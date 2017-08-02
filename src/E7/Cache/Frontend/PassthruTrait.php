<?php

namespace E7\Cache\Frontend;

use E7\SimpleCache\Exception\InvalidArgumentException;

trait PassthruTrait
{
    /**
     * @var callable
     */
    private $callback;

    public function __invoke()
    {
        return $this->call($this->callback, func_get_args());
    }

    /**
     * @param   callable $callback
     * @return  \E7\Cache\Pattern\AbstractFrontend
     * @throws  \E7\Cache\Exception\InvalidArgumentException
     */
    public function setCallback(callable $callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Parameter one must be callable.');
        }
        $this->callback = $callback;
        return $this;
    }
}