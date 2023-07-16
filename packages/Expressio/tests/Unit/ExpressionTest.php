<?php
declare(strict_types=1);

namespace Evident\Expressio\Tests\Unit;
 
use PHPUnit\Framework\TestCase;

use Evident\Expressio\Expression;
use Evident\Expressio\Tests\Resources\TestTranspiler;

final class ExpressionTest extends TestCase
{
    public function testGreaterThan() { 

        $transpiler = new TestTranspiler();
        $expr = new Expression( fn($uu) => $uu->a > $uu->b );
        
        $stmt = $transpiler->transpile($expr);
        
        $this->assertEquals('return $uu->a > $uu->b;', $stmt->source);
    }
}