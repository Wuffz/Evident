<?php
declare(strict_types=1);

namespace Evident\Bunch\Tests\Unit;

use Evident\Bunch\Enumeration;
use Iterator;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

 class EnumerationTest extends TestCase
{
    private $enumeration;
    public function setUp(): void
    {
        $this->enumeration = new Enumeration([1, 2, 3]);
    }
    
    public function testGetIterator() {
        $iterator = $this->enumeration->getIterator();
        $this->assertInstanceOf(Iterator::class, $iterator);
    }   
    
    public function testGetEnumerator() {
        $enumerator = $this->enumeration->getEnumerator();
        foreach ( $enumerator as $key => $val  ) {
            $this->assertIsInt($val);
        }
        $this->assertInstanceOf(Enumeration::class, $enumerator);
    }
    public function testAll() {
        $count = $this->enumeration->count();
        $all = $this->enumeration->all();
        $this->assertEquals($count, $all->count());
    }
    public function testCount()
    {
        $count = $this->enumeration->count();
        $this->assertTrue($count == 3);
    }
    public function testFilter()
    {
        $count = $this->enumeration->filter(fn($x) => $x == 1)->count();
        $this->assertTrue($count == 1);
    }
    public function testTake()
    {
        $count = $this->enumeration->take(2)->count();
        $this->assertTrue($count == 2);
    }
    public function testFirst()
    {
        $num = $this->enumeration->first();
        $this->assertTrue($num == 1);
    }
    public function testFirstThrowsOutOfBound() {
        $this->expectException(\OutOfBoundsException::class);
        $num = $this->enumeration->first(fn($i) => $i == 6);
    }

    public function testLast()
    {
        $num = $this->enumeration->last();
        $this->assertTrue($num == 3);
    }
    public function testLastThrowsOutOfBound() {
        $this->expectException(\OutOfBoundsException::class);
        $num = $this->enumeration->last(fn($i) => $i == 6);
    }
    public function testSkip()
    {
        $skipped = $this->enumeration->skip(1);
        $num = $skipped->take(1)->first();

        foreach ( $skipped as $key => $val ) {
            $this->assertIsInt($val);
        }
        $this->assertTrue($num == 2);
    }
    public function testEmpty() {
        $enum = $this->enumeration;
        $this->assertSame(3, $enum->count());
        $enum = $enum->take(-1);
        $this->assertSame(0, $enum->count());
    }
}
