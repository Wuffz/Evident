<?php

namespace Evident\Lingua\Tests\Unit;

use Evident\Lingua\InsertQueryBuilder;

class InsertQueryTest extends SqlTestCase {

    public function testInsertQuery() {
        
        $num = 1;
        $queryBuilder = new InsertQueryBuilder($this->pdo, $this->transpiler);
        $query = $queryBuilder
            ->into('users')
            ->values([
                'username' => 'patrick',
                'password' => '-',
            ])
            ->getQuery();

        $this->assertEquals(
            'INSERT INTO users (username, password) VALUES (?, ?)',
            $query->getQuery()
        );
        
    }    
    
}