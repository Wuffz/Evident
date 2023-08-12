<?php

namespace Evident\Lingua\Tests\Unit;

use Evident\Lingua\QueryBuilder;
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
    public function aggregate(): SelectQueryBuilder {
        $queryBuilder = new SelectQueryBuilder($this->pdo, $this->transpiler);
        return $queryBuilder
            ->from(self::USERS)
            ->select(fn($users) => [$users->id])
            ->where( fn($users) => $users->age > 18);
    
    }
    public function testAggregationCount() {
        $count = $this->aggregate()->count();
        $this->assertEquals(2, $count);
    }
    public function testAggregationAvg() {
        $avg = $this->aggregate()->avg(fn($users) => $users->age);
        $this->assertEquals(27.5, $avg);
    }
    
    public function testAggregationSum() {
        $sum = $this->aggregate()->sum(fn($users) => $users->age);
        $this->assertEquals(55, $sum);
    }
    
    public function testAggregationMin() {
        $min = $this->aggregate()->min(fn($users) => $users->age);
        $this->assertEquals(22, $min);
    }
    
    public function testAggregationMax() {
        $max = $this->aggregate()->max(fn($users) => $users->age);
        $this->assertEquals(33, $max);
    }

    public function testSelectAllQuery() {
        $queryBuilder = new SelectQueryBuilder($this->pdo, $this->transpiler);
        
        $all = $queryBuilder
            ->from(self::USERS)
            ->select()
            ->all();
        
        $this->assertEquals(3, count($all));
    }
}