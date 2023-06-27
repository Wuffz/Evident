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
         $this->assertEquals(5, $f->id);
         
    }
    public function testScalarsWithDoubleNames() {
        // make 2 where's with different input values, but same input name, check if collide
    }
    public function testLeftJoin()
    {
        $f = $this->albums->leftJoin(fn(Album $a, Artist $ar) => $a->id == $ar->id )->all();
        // we need a short syntax ?
        $f = $this->albums->join(fn($a, Artist $b ) => $a < $b );

    }
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
