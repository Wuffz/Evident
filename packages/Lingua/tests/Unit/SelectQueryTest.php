<?php

namespace Evident\Lingua\Tests\Unit;

use Evident\Lingua\SelectQueryBuilder;

class SelectQueryTest extends SqlTestCase {

    const USERS = 'users';
    const ORDERS = 'orders';

    public function testSelectQuery() {
        
        $num = 1;
        $queryBuilder = new SelectQueryBuilder($this->pdo, $this->transpiler);
        $query = $queryBuilder
            ->from(self::USERS)
            ->select(fn($users,$orders) => [$users->id,$users->name, $orders->order_date])
            ->leftJoin(self::ORDERS, fn($users, $orders) => $users->id == $orders->user_id)
            ->where( fn($users) => $users->surname == 'patrick' )
            ->where( fn($users) => $users->age > $num )
            ->orderBy(fn($users) => $users->name)
            ->groupBy(fn($users) => $users->id)
            ->limit(10)
            ->offset(50)
            ->getQuery();

        $this->assertEquals(
            'SELECT users.id, users.name, orders.order_date FROM users LEFT JOIN orders ON users.id = orders.user_id WHERE users.surname = "patrick" AND users.age > :num GROUP BY users.id ORDER BY users.name OFFSET 50 LIMIT 10',
            $query->getQuery()
        );
    }
}