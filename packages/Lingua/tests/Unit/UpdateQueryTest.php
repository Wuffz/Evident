<?php

namespace Evident\Lingua\Tests\Unit;

use Evident\Lingua\UpdateQueryBuilder;

class UpdateQueryTest extends SqlTestCase {

    public function testUpdateQuery() {
        
        $num = 1;
        $queryBuilder = new UpdateQueryBuilder($this->pdo, $this->transpiler);
        $query = $queryBuilder
            ->update('users')
            ->set([
                'stars' => 5,
            ])
            ->join('books', fn($users, $books) => $users->id == $books->user_id)
            ->where(fn($users) => $books->amount_sold > 100)
            ->getQuery();

        $this->assertEquals(
            'UPDATE users SET stars = :stars  JOIN books ON users.id = books.user_id WHERE books.amount_sold > 100',
            $query->getQuery()
        );
    }    
    
}