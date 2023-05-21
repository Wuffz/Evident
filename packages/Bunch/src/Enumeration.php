<?php

namespace Evident\Bunch;

use ArrayIterator;
use Closure;
use Iterator;
use OutOfBoundsException;


/**
 * Enumeration object is immutable by itself, it may however return new immutable objects without interfering with the others
 * @template T
 */
class Enumeration implements EnumerationInterface
{

    protected Iterator $iterator;
    /**
     * @param array<T>|Closure|Iterator $source
     */
    public function __construct(Iterator|Closure|array $source)
    {
        if (is_array($source)) {
            $source = new ArrayIterator($source);
        }
        $this->iterator = $source instanceof Closure ? $source() : $source;
    }


    public function getIterator(): Iterator
    {
        return $this->iterator;
    }

    /**
     * @return EnumerationInterface<T>
     */
    public function getEnumerator(): EnumerationInterface
    {
        return new self(function () {
            foreach ($this as $k => $v) {
                yield $k => $v;
            }
        });
    }

    /**
     * @return T
     */
    public function first(?Closure $expr = null): mixed
    {
        foreach ($this as $k => $v) {
            if ($expr === null || $expr($v, $k))
                return $v;
        }
        throw new OutOfBoundsException("There was no match found");
    }

    /**
     * @return T
     */
    public function last(?Closure $expr = null): mixed
    {
        $found = false;
        $result = null;
        foreach ($this as $k => $v) {
            if ($expr === null || $expr($v, $k)) {
                $found = true;
                $result = $v;
            }
        }
        if (!$found) {
            throw new OutOfBoundsException("There was no match found");
        }
        return $result;
    }

    /**
     * @return EnumerationInterface<T>
     */
    public function all(): EnumerationInterface
    {
        return new self(function () {
            foreach ($this as $k => $v) {
                yield $k => $v;
            }
        });
    }

    /**
     * @return EnumerationInterface<T>
     */
    public function skip(int $count): EnumerationInterface
    {
        return new self(function () use ($count) {
            $iterator = $this->getIterator();
            $iterator->rewind();
            for ($i = 0; $i < $count && $iterator->valid(); ++$i) {
                $iterator->next();
            }
            while ($iterator->valid()) {
                yield $iterator->key() => $iterator->current();
                $iterator->next();
            }
        });
    }

    /**
     * @return EnumerationInterface<T>
     */
    public function take(int $count): EnumerationInterface
    {
        if ($count <= 0) {
            return new self(new \EmptyIterator);
        }
        return new self(function () use ($count) {
            foreach ($this as $k => $v) {
                yield $k => $v;
                if (--$count == 0)
                    break;
            }
        });
    }

    /**
     * 
     * the count of items in this collection
     * 
     * @return int 
     * 
     */
    public function count(): int
    {
        $count = 0;
        $iterator = $this->getIterator();
        $iterator->rewind();
        while ($iterator->valid()) {
            $count++;
            $iterator->next();
        }
        return $count;

    }

    /**
     * @return EnumerationInterface<T>
     */
    public function filter(Closure $expr): EnumerationInterface
    {
        return new self(function () use ($expr) {
            foreach ($this as $k => $v) {
                if ($expr($v, $k)) {
                    yield $k => $v;
                }
            }
        });
    }


}
