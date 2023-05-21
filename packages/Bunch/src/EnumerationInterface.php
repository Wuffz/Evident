<?php

namespace Evident\Bunch;

use Closure;
use Iterator;
use IteratorAggregate;

/**
 * @template T
 */
interface EnumerationInterface extends IteratorAggregate
{
    /**
     * @return Iterator 
     */
    public function getIterator(): Iterator;
    /**
     * @return EnumerationInterface<T> 
     */
    public function getEnumerator(): EnumerationInterface;
    /**
     * @param Closure<T> $expr
     * @return EnumerationInterface<T>
     */
    public function filter(Closure $expr): EnumerationInterface;
    /**
     * @param Closure<T> $expr
     * @return T
     */
    public function first(?Closure $expr = null): mixed;
    /**
     * @param Closure<T> $expr
     * @return T
     */
    public function last(?Closure $expr): mixed;
    /**
     * @return EnumerationInterface<T>
     */
    public function all(): EnumerationInterface;
    /**     
     * @param int $count
     * @return EnumerationInterface<T>
     */
    public function skip(int $count): EnumerationInterface;
    /**
     * @param int $count
     * @return EnumerationInterface<T>
     */
    public function take(int $count): EnumerationInterface;

    /**
     * @return int
     */
    public function count(): int;
}
