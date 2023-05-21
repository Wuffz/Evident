<?php
declare(strict_types=1);

namespace Evident\Bunch\Tests\Unit;

use Evident\Bunch\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    private function getCollection()
    {
        return new Collection([1, 2, 3, 4]);
    }
    public function testAdd()
    {
        $collection = $this->getCollection();
        $collection->add(null);
        $count = $collection->count();

        $this->assertTrue($count == 5);
    }

    public function testClear()
    {
        $collection = $this->getCollection();
        $collection->clear();
        $count = $collection->count();
        $this->assertTrue($count == 0);
    }
    public function testContains()
    {
        $contains = $this->getCollection()->contains(4);
        $this->assertTrue($contains == true);
    }
    public function testRemove()
    {
        $collection = $this->getCollection()->add(2)->add(2);
        $removed = $collection->remove(2);
        $this->assertTrue($removed == 3);
        $this->assertTrue($collection->skip(1)->first() == 3);
    }
}
