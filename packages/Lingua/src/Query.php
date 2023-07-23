<?php

namespace Evident\Lingua;

class Query {
    public function __construct(
        private \PDO $connection,
        private string $query,
        private array $bindings
    ){}

    public function getQuery() {
        return $this->query;
    }
    public function getBindings() {
        return $this->bindings;
    }

}