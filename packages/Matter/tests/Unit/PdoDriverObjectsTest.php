<?php
declare(strict_types=1);
namespace Evident\Matter\Tests\Unit;

use Evident\Matter\Behaviour\CamelCaseFullTableNamesConvention;
use Evident\Matter\DataSource\RemoteDataSetInterface;
use PDO;
use Evident\Matter\Driver\PDO\Driver as PdoDriver;
use Evident\Matter\Driver\PDO\RemoteDataSet as PdoRemoteDataset;

use Evident\Matter\Tests\Resources\Entities\Album;
use Evident\Matter\Tests\Resources\Entities\Artist;

use PHPUnit\Framework\TestCase;

final class PdoDriverObjectsTest extends TestCase
{
    // repositories
    private PdoRemoteDataset $albums;
    private PdoRemoteDataset $artists;

    private RemoteDataSetInterface $contacts;
    private RemoteDataSetInterface $orders;

    private PdoDriver $driver;

    public function setUp(): void
    {
        // pass aliasses for classes
        $pdo = new PDO("sqlite:" . __DIR__ . "/../Resources/chinook.db");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $naming = new CamelCaseFullTableNamesConvention('\\Evident\\Matter\\Tests\\Resources\\Entities');
        $this->driver = new PdoDriver($pdo, $naming);
        $this->albums = $this->driver->from(Album::class);
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
        // Use full table names, this works always, for conventions we should be able to disable this.
        // Or at least in default, singularize. there's no need for trial and error when configuration is ok.
        // all depends on default conventions
        $f = $this->artists->filter(fn($artists) => $artists->Name == 'Godsmack')->first();
        
        // fetching, and running the filter using on the full recordset would by silly.
        $f = $this->albums->filter(fn(Album $a) => $a->Title == 'Big Ones')->first();
        $this->assertEquals(5, $f->AlbumId);
    }

    // public function testSetLocalName() {
        
    // }
    // public function testGetLocalName() {

    // }
    // public function testGetRemoteName() {

    // }
    // public function testSetConnection(){

    // }
    // public function testFilter(){

    // }
    // public function testSkip(){

    // }
    // public function testTake(){

    // }
    // public function testFirst(){

    // }
    // public function testLast(){

    // }
    // public function testAll(){

    // }
    // public function testCount(){

    // } 
}
