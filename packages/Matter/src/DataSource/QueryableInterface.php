<?php

namespace Evident\Matter\DataSource;

use Evident\Bunch\EnumerationInterface;
use Closure;
/**
 * @template T
 */
interface QueryableInterface extends EnumerationInterface
{
    // Overrides from EnumerationInterface
    /**
     * @param Closure<T> $expr
     * @return QueryableInterface<T>
     */
    public function filter(Closure $expr): QueryableInterface;

    /**     
     * @param int $count
     * @return QueryableInterface<T>
     */
    public function skip(int $count): QueryableInterface;
    /**
     * @param int $count
     * @return RecordSetInterface<T>
     */
    public function take(int $count): QueryableInterface;

    /**
     * @param Closure<T> $expr
     * @return mixed Record object
     */
    public function first(?Closure $expr = null): mixed;
    /**
     * @param Closure<T> $expr
     * @return mixed Record object
     */
    public function last(?Closure $expr): mixed;
    /**
     * @return RecordSetInterface<T>
     */
    public function all(): RecordSetInterface;

    // Queryable custom functions, may be moved to EnumerationInterface some day when supported on objects.

    /**
     * @param DataSetInterface $dataset
     * @return QueryableInterface<T>
     */
   // public function combine(DataSetInterface $dataset): QueryableInterface;

    /**
     * @param Closure $expression Expression accepting T as parameter, the return value will be the new mapping
     * @return QueryableInterface<T>
     */
    public function map(Closure $expression): QueryableInterface;

    /**
     * @param Closure $expression Expression accepting T as parameter the return value will be used to group them
     * @return QueryableInterface<T>
     */
    public function groupBy(Closure $expression): QueryableInterface;

    /**
     * @return QueryableInterface
     */
    public function getQueryable(): QueryableInterface;
}
