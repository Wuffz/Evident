<?php

namespace Evident\Lingua;

use Exception;
use PDO;

class Query {
    public function __construct(
        private \PDO $connection,
        private string $query,
        private array $bindings
    ){

    }
    public function getQuery() {
        return $this->query;
    }
    public function getBindings() {
        return $this->bindings;
    }
    public function getPdoStatement(): \PDOStatement {
        $stmt = $this->connection->prepare($this->query);
        if ( $stmt === false ) {
            throw new Exception('failed to prepare statement');
        }
        foreach ($this->bindings as $binding => $value) {
            $stmt->bindValue($binding, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
    
        return $stmt;
    }
}