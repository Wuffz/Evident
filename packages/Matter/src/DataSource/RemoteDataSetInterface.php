<?php

namespace Evident\Matter\DataSource;

/**
 * @todo: this is a mockup
 * @template T
 */
interface RemoteDataSetInterface extends QueryableInterface
{
    /**
     * Must return a stringable name
     *
     * @return string
     * 
     */
    public function getLocalName(): string;

    /**
     * Must return a stringable name
     *
     * @return string
     * 
     */
    public function getRemoteName(): string;
    /**
     * Make sure the Queryable has a connection to execute its queries to.
     *
     * @param mixed $connection
     * @return void
     * 
     */
    public function setConnection(mixed $connection): void;

    public function debugInfo(): array;
}
