<?php
declare(strict_types=1);
namespace Evident\Matter\Tests\Unit;

use Evident\Matter\DataSource\RemoteDataSetInterface;
use Evident\Matter\Driver\PDO\Driver as PdoDriver;
use PDO;
use PHPUnit\Framework\TestCase;

final class ExpressionTest extends TestCase
{
    // repositories
    private RemoteDataSetInterface $contacts;
    private RemoteDataSetInterface $orders;

    public function setUp(): void
    {
        // pass aliasses for classes
        $pdo = new PDO("sqlite:" . __DIR__ . "/../Resource//database.sql");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $driver = new PdoDriver($pdo);


        $this->contacts = $driver->from('contacts');
        $this->orders = $driver->from('orders');
    }

    public function testGetFirst()
    {
        $f = $this->orders->first();
        $this->assertEquals(1, $f->id);
    }
}
