<?php
declare(strict_types=1);

namespace Evident\Bunch\Tests\Unit;

use Evident\Bunch\Enumeration;
use PHPUnit\Framework\TestCase;

final class EnumerationTest extends TestCase
{
    private $enumeration;
    public function setUp(): void
    {
        $this->enumeration = new Enumeration([1, 2, 3]);
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

    public function testLast()
    {
        $num = $this->enumeration->last();
        $this->assertTrue($num == 3);
    }

    public function testSkip()
    {
        $num = $this->enumeration->skip(1)->take(1)->first();
        $this->assertTrue($num == 2);
    }
}
