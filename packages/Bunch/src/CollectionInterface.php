<?php

namespace Evident\Bunch;

use IteratorAggregate;

/**
 * @template T
 */
interface CollectionInterface extends EnumerationInterface, IteratorAggregate
{

    /**
     * @param T $item
     */
    public function add(mixed $item);
    public function clear(): bool;
    /**
     * @param T $item
     */
    public function contains($item): bool;
    /**
     * @param T $item
     */
    public function remove(mixed $item): int;

}
