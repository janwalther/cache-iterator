<?php
namespace janwalther\CacheIterator\test;

use janwalther\CacheIterator\CacheIterator;

class CacheIteratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var CacheIterator */
    private $iterator;

    /** @var RandomIterator */
    private $innerIterator;

    protected function setUp()
    {
        $this->innerIterator = new RandomIterator(3);
        $this->iterator = new CacheIterator($this->innerIterator);
    }

    /** @test */
    public function it_returns_same_entries_on_multiple_iterations()
    {
        $values1 = array();
        foreach($this->iterator as $value) {
            $values1[] = $value;
        }

        $values2 = array();
        foreach($this->iterator as $value) {
            $values2[] = $value;
        }

        static::assertEquals($values1, $values2);
        static::assertEquals($values1, iterator_to_array($this->iterator));
        static::assertCount(3, $values1);
        static::assertSame(3, $this->innerIterator->getMethodCalls());
    }

    /** @test */
    public function it_is_nestable()
    {
        $values1 = array();
        $values2 = array();
        foreach($this->iterator as $value1) {
            $values1[] = $value1;

            $values2 = array();
            foreach(clone $this->iterator as $value2) {
                $values2[] = $value2;
            }
        }

        static::assertEquals($values1, $values2);
        static::assertEquals($values1, iterator_to_array($this->iterator));
        static::assertCount(3, $values1);
        static::assertSame(1, $this->innerIterator->getMethodCalls()); // other methodCalls go to cloned iterator
    }
}

class RandomIterator implements \Iterator
{
    private $count;
    private $key = 0;
    private $methodCalls = 0;

    /**
     * RandomIterator constructor.
     *
     * @param int $count
     */
    public function __construct($count)
    {
        $this->count = $count;
    }

    public function current()
    {
        $this->methodCalls++;
        return mt_rand();
    }

    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        $this->key++;
    }

    public function rewind()
    {
        $this->key = 0;
    }

    public function valid()
    {
        return $this->key < $this->count;
    }

    /**
     * @return int
     */
    public function getMethodCalls()
    {
        return $this->methodCalls;
    }
}
