<?php

namespace Evident\Lingua\Tests\Unit;

use Evident\Expressio\Transpiler\AnsiSqlTranspiler;
use Evident\Lingua\DeleteQueryBuilder;
use Evident\Lingua\InsertQueryBuilder;
use Evident\Lingua\QueryBuilder;
use Evident\Lingua\SelectQueryBuilder;
use Evident\Lingua\UpdateQueryBuilder;
use PHPUnit\Framework\TestCase;
use PDO;

class TestQueryBuilder extends SqlTestCase{
    public function testInsertQuery() {
        $builder = (new QueryBuilder($this->pdo, $this->transpiler))->into('users')->insert([]);
        $this->assertInstanceOf(InsertQueryBuilder::class, $builder);
    }
    public function testDeleteQuery() {
        $builder = (new QueryBuilder($this->pdo, $this->transpiler))->from('users')->delete();
        $this->assertInstanceOf(DeleteQueryBuilder::class, $builder);
    }
    public function testUpdateQuery() {
        $builder = (new QueryBuilder($this->pdo, $this->transpiler))->update('users')->set([]);
        $this->assertInstanceOf(UpdateQueryBuilder::class, $builder);
    }
    public function testSelectQuery() {
        $builder = (new QueryBuilder($this->pdo, $this->transpiler))->from('users')->select(fn() => '*');
        $this->assertInstanceOf(SelectQueryBuilder::class, $builder);
    }
}