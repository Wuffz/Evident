<?php
declare(strict_types=1);
namespace Evident\Matter\Tests\Unit;

use Evident\Matter\Behaviour\SnakeCaseToPascalCaseFullyKeyedConvention;
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
        $naming = new SnakeCaseToPascalCaseFullyKeyedConvention('\\Evident\\Matter\\Tests\\Resources\\Entities');
        $this->driver = new PdoDriver($pdo, $naming);
        $this->albums = $this->driver->from(Album::class);
        $this->artists = $this->driver->from('artists');
    }

    public function testGetFirst()
    {
        $f = $this->albums->first();
        $this->assertEquals(1, $f->getId());
    }
    public function TestPrepareStatementsDirectInputScalarVersusVeriable() 
    {
        $input = 'Big Ones';
        $f = $this->albums->filter(fn(Album $a) => $a->title == 'Big Ones' && $a->title == $input)->debugInfo();
        foreach ( $f[1] as $key => $value ) {
            
            $this->assertMatchesRegularExpression('/'.$key.'/', $f[0]);
            $this->assertEquals($f[1][$key], $value);
        }
        
    }
    
    public function testGetWhereByNameEntity()
    {
        // fails on $n => n.Title, no context to db name is given.
        // Use full table names, this works always, for conventions we should be able to disable this.
        // Or at least in default, singularize. there's no need for trial and error when configuration is ok.
        // all depends on default conventions
        $f = $this->artists->filter(fn($artists) => $artists->Name == 'Godsmack')->first();
        $this->assertEquals($f->ArtistId, 87);
    }
    public function testGetWhereByNameEntityAndProperty()
    {
         // when in the convention, it should translate a.title to albums.Title
         // also should remap the albums.AlbumId to $f->id
         $f = $this->albums->filter(fn(Album $a) => $a->title == 'Big Ones')->first();
         
         // we do get an actual stdClass, but it's not hydrated into the entity
         $this->assertEquals(5, $f->getId());
         
    }
    public function testScalarsWithDoubleNames() {
        $f = $this->albums;

        $search = 'Big Ones';
        $f = $f->filter(fn(Album $a) => $a->title == $search);

        $search = 3;

        // Current: This should translate to albums.AlbumId , not albums.id
        $f = $f->filter(fn(Album $a) => $a->artist_id == $search);

        $f = $f->first();
        
        $this->assertEquals(5, $f->getId());
        // make 2 where's with different input values, but same input name, check if collide
    }
}
