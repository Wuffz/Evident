<?php

namespace Evident\Lingua;

use Evident\Expressio\Transpiler\TranspilerInterface;

class Query {

    private \PDO $connection;
    private TranspilerInterface $transpiler;

    public function __construct(\PDO $connection, TranspilerInterface $transpiler ) {
        $this->connection = $connection;
        $this->transpiler = $transpiler;
    }
    public function select($fields = []) {
        return (new SelectQuery($this->connection, $this->transpiler))->select($fields);
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