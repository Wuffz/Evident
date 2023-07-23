<?php

namespace Evident\Lingua\Tests\Unit;

use Evident\Expressio\Transpiler\AnsiSqlTranspiler;
use Evident\Lingua\Query;
use Evident\Lingua\SelectQuery;
use PHPUnit\Framework\TestCase;
use PDO;

class QueryTest extends TestCase {

    private $query;
    private $pdo;
    private $transpiler;
    
    function setUp(): void
    {
        $pdo = new PDO("sqlite:" . __DIR__ . "/../Resources/chinook.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
        
        $this->transpiler = new AnsiSqlTranspiler();
        // we disable anticolide, so we get straight forward queries
        $this->transpiler->disableAntiColide();
        parent::setUp();
    }
    const USERS = 'users';
    const ORDERS = 'orders';

    public function testSelectQuery() {
        
        $num = 1;
        $queryBuilder = new SelectQuery($this->pdo, $this->transpiler);
        $query = $queryBuilder
            ->from(self::USERS)
            ->select(fn($users,$orders) => [$users->id,$users->name, $orders->order_date])
            ->leftJoin(self::ORDERS, fn($users, $orders) => $users->id == $orders->user_id)
            ->where( fn($users) => $users->id == $num )
            ->orderBy(fn($users) => $users->name)
            ->groupBy(fn($users) => $users->id)
            ->limit(10)
            ->offset(50)
            ->getQuery();

        $this->assertEquals(
            'SELECT users.id, users.name, orders.order_date FROM users LEFT JOIN orders ON users.id = orders.user_id WHERE users.id = :num GROUP BY users.id ORDER BY users.name OFFSET 50 LIMIT 10',
            $query[0]
        );
    }
}