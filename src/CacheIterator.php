<?php
namespace janwalther\CacheIterator;

class CacheIterator implements \Iterator
{
    private $cache;

    /** @var \Iterator */
    private $innerIterator;

    /**
     * CacheIterator constructor.
     *
     * @param \Iterator $innerIterator
     */
    public function __construct(\Iterator $innerIterator)
    {
        $this->innerIterator = $innerIterator;
        $this->cache = new \ArrayObject();
    }

    public function current()
    {
        if (!isset($this->cache[$this->getInnerIterator()->key()])) {
            $this->cache($this->getInnerIterator()->current());
        }

        return $this->cache[$this->key()];
    }

    protected function cache($value) {
        $this->cache[$this->key()] = $value;
    }

    public function next()
    {
        $this->getInnerIterator()->next();
    }

    public function key()
    {
        return $this->getInnerIterator()->key();
    }

    public function valid()
    {
        return $this->getInnerIterator()->valid();
    }

    public function rewind()
    {
        $this->getInnerIterator()->rewind();
    }

    private function getInnerIterator() {
        return $this->innerIterator;
    }

    public function __clone()
    {
        $this->innerIterator = clone $this->innerIterator;
    }
}
