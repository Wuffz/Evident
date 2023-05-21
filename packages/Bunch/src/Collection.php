<?php

namespace Evident\Bunch;

use AppendIterator;
use ArrayIterator;
use Closure;
use Iterator;

/**
 * @todo: optimize this, i think we should wrap the generators with a cacheiterator of some sorts.
 * that should resolve the issues with de resume() Error[Exception]
 * 
 * The collection class is mutable, and may change over time.
 * 
 * @template T
 */
class Collection extends Enumeration implements CollectionInterface, EnumerationInterface
{
    /**
     * @property array<T>|Closure|Iterator $source;
     */

    public function __construct(
        private Iterator|Closure|array $source
    ) {
        parent::__construct($source);
    }
    /**
     * @param T $item
     */
    public function add($value, $key = null): CollectionInterface
    {
        $new = new AppendIterator();
        $new->append($this->getIterator());
        if ($key) {
            $new->append(new ArrayIterator([$key => $value]));
        } else {
            $new->append(new ArrayIterator([$value]));
        }
        $this->iterator = $new;
        return $this;
    }

    /**
     * Clears the enire collection
     *
     * @return CollectionInterface
     * 
     */
    public function clear(): bool
    {
        $this->iterator = new ArrayIterator([]);
        return true;
    }
    /**
     * @param T $item
     */
    public function contains($item): bool
    {
        return (bool) $this->filter(fn($a) => $a == $item)->count();
    }
    /**
     * 
     * number of items removed from the list ( may include dublicates )
     * 
     * @param T $item
     * @return int 
     */
    public function remove(mixed $item): int
    {
        $removed = 0;
        $keep = [];
        foreach ($this as $k => $v) {
            if ($v !== $item) {
                $keep[$k] = $v;
            } else {
                $removed++;
            }
        }
        $this->iterator = new ArrayIterator($keep);
        return $removed;
    }
}
