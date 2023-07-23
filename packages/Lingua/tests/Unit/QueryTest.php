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

class QueryTest extends SqlTestCase{
    public function testQueryResponse() {
        $all = '*';
        $query = (new QueryBuilder($this->pdo, $this->transpiler))->from('users')->select(fn() => $all)->getQuery();
        $bindings = $query->getBindings();
        $sql = $query->getQuery();
        $this->assertTrue($bindings[':all'] == $all);
        $this->assertEquals($sql, 'SELECT :all FROM users');
        
    }
}