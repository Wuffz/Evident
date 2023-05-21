<?php
declare(strict_types=1);
namespace Evident\Matter\Tests\Unit;

use PDO;
use Evident\Matter\DataSource\RemoteDataSetInterface;
use Evident\Matter\Driver\PDO\Driver as PdoDriver;
use PHPUnit\Framework\TestCase;

final class PdoDriverObjectsTest extends TestCase
{
    // repositories
    private RemoteDataSetInterface $contacts;
    private RemoteDataSetInterface $orders;
    private PdoDriver $driver;

    public function setUp(): void
    {
        // pass aliasses for classes
        $pdo = new PDO("sqlite:" . __DIR__ . "/../Resources/chinook.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->driver = new PdoDriver($pdo);
        $this->albums = $this->driver->from('albums');
        $this->artists = $this->driver->from('artists');
    }

    public function testGetFirst()
    {
        $f = $this->albums->first();
        $this->assertEquals(1, $f->AlbumId);
    }
    public function testGetWhere()
    {
        // fails on $n => n.Title, no context to db name is given.
        $f = $this->albums->filter(fn($n) => $n->Title == 'Big Ones')->first();
        $this->assertEquals(5, $f->AlbumId);
    }
}
