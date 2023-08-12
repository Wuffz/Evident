<?php

namespace Evident\Lingua;

use Closure;
use Evident\Expressio\Transpiler\TranspilerInterface;

class QueryBuilder {

    private \PDO $connection;
    private TranspilerInterface $transpiler;

    private $tableName = null;

    public function __construct(\PDO $connection, TranspilerInterface $transpiler ) {
        $this->connection = $connection;
        $this->transpiler = $transpiler;
    }
   
    public function from(string $tableName): self {
        $this->tableName = $tableName;
        return $this;
    } 
    public function into(string $tableName): self {
        return $this->from($tableName);
        return $this;
    }
    public function withTable($queryBuilder) {
        if ( !$this->tableName ) {
            return $queryBuilder;
        }
        return $queryBuilder->setTable($this->tableName);
    }
    
    public function select(?Closure $fields = null) : SelectQueryBuilder {
        $queryBuilder = (new SelectQueryBuilder($this->connection, $this->transpiler));
        $queryBuilder = $this->withTable($queryBuilder);
        return $queryBuilder->select($fields);
    }

    public function delete(): DeleteQueryBuilder{
        $queryBuilder = (new DeleteQueryBuilder($this->connection, $this->transpiler));
        $queryBuilder = $this->withTable($queryBuilder);
        return $queryBuilder;
    }

    public function update(): UpdateQueryBuilder{
        $queryBuilder = (new UpdateQueryBuilder($this->connection, $this->transpiler));
        $queryBuilder = $this->withTable($queryBuilder);
        return $queryBuilder;
    }
    
    public function insert() {
        $queryBuilder = (new InsertQueryBuilder($this->connection, $this->transpiler));
        $queryBuilder = $this->withTable($queryBuilder);
        return $queryBuilder;
    }
    
    /* 
    public function delete() {
        return new DeleteQuery();
    }
    public function update() {
        return new UpdateQuery();
    }
    public function insert() {
        return new InsertQuery();
    }
    */
}