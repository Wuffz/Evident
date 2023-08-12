<?php

namespace Evident\Lingua\Tests\Unit;

use Evident\Expressio\Transpiler\AnsiSqlTranspiler;
use PDO;
use PHPUnit\Framework\TestCase;
use Evident\Expressio\Transpiler\TranspilerInterface;

class SqlTestCase extends TestCase {

    static ?\PDO $pdoCached = null;

    protected ?\PDO $pdo;

        
    protected TranspilerInterface $transpiler;

    function setUp(): void
    {   
        if ( self::$pdoCached !== null ) {
            $this->pdo = self::$pdoCached;
        } else {
            $this->rebuildDatabase();
        }


        $this->transpiler = new AnsiSqlTranspiler(['user.id' => 'userId']);
        // we disable anticolide, so we get straight forward queries
        $this->transpiler->disableAntiColide();
    }

    public function testSomethingToShutup() {
        $this->assertTrue(true);
    }

    public function rebuildDatabase() {

        $this->pdo = new \PDO("sqlite::memory:");
        
        $this->pdo->query("CREATE TABLE users (
            id INTEGER NOT NULL PRIMARY KEY,
            username VARCHAR (20) NOT NULL,
            password VARCHAR (32) NOT NULL,
            age INTEGER NOT NULL
        )");


        $this->pdo->query("CREATE UNIQUE INDEX users_username ON users (username)");

        $this->pdo->query("INSERT INTO users (username, password, age) VALUES
            ('user_a', '" . password_hash('password_a',PASSWORD_DEFAULT) . "', '11'),
            ('user_b', '" . password_hash('password_b',PASSWORD_DEFAULT) . "', '22'),
            ('user_c', '" . password_hash('password_c',PASSWORD_DEFAULT) . "', '33');
        ");

         /* 
        $pdo->query("CREATE TABLE comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            parent_type VARCHAR(50) NOT NULL,
            parent_id INTEGER NOT NULL,
            user_id VARCHAR(50) DEFAULT 'Anonymous' NOT NULL,
            text TEXT
        )");

        $pdo->query("CREATE TABLE articles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id VARCHAR(50) DEFAULT 'Anonymous' NOT NULL,
            title VARCHAR(255) NOT NULL,
            text TEXT
        )");
        $pdo->query("CREATE TABLE images (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id VARCHAR(50) DEFAULT 'Anonymous' NOT NULL,
            url VARCHAR(255) NOT NULL,
            caption VARCHAR(255)
        )");*/ 
       

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        self::$pdoCached = $this->pdo;

            
    }
}