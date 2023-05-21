<?php
declare(strict_types=1);

namespace Evident\Expressio\Tests\Unit;

use Evident\Expressio\Expression;
use Evident\Expressio\ExpressionReflector;
use PhpParser\Node\Stmt\Return_;
use PHPUnit\Framework\TestCase;

final class ExpressionReflectorTest extends TestCase
{
   

    public function testExpressionReflectorAst() { 
        $ref = (new Expression( fn($uu) => $uu->a > $uu->b ))->getReflection();
        $this->assertInstanceOf(ExpressionReflector::class, $ref);

        $ast = $ref->getAst();
        $this->assertInstanceOf(Return_::class, $ast[0]);
    }
}