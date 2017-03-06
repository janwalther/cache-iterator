<?php
namespace janwalther\CacheIterator;

class CacheIterator implements \Iterator
{
    private $iterator;
    private $cache = [];

    public function __construct(\Iterator $it)
    {
        $this->iterator = $it;
    }

    public function current()
    {
        if (!isset($this->cache[$this->key()])) {
            $this->cache[$this->key()] = $this->iterator->current();
        }

        return $this->cache[$this->key()];
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }
}
