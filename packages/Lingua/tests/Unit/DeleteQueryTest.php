<?php

namespace Evident\Lingua\Tests\Unit;

use Evident\Lingua\DeleteQueryBuilder;

class DeleteQueryTest extends SqlTestCase
{

    public function testDeleteQuery()
    {

        $num = 1;
        $queryBuilder = new DeleteQueryBuilder($this->pdo, $this->transpiler);
        $query = $queryBuilder
            ->from('users')
            ->join('books', fn($users, $books) => $users->id == $books->user_id)
            ->where(fn($books) => $books->amount_sold > 100)
            ->getQuery();

        $this->assertEquals(
            'DELETE FROM users JOIN books ON users.id = books.user_id WHERE books.amount_sold > 100',
            $query->getQuery()
        );
    }

}