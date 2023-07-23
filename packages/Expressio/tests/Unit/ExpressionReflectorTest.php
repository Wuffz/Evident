<?php
declare(strict_types=1);

namespace Evident\Expressio\Tests\Unit;

use Evident\Expressio\Expression;
use Evident\Expressio\ExpressionReflector;
use Evident\Expressio\ExpressionReflectorException;
use PhpParser\Node\Stmt\Return_;
use PHPUnit\Framework\TestCase;

final class ExpressionReflectorTest extends TestCase
{
    public function testCreateExpression() {
        $expression = new Expression( fn() => true );
        $this->assertInstanceOf(Expression::class, $expression);
    }

    public function testExpressionReflectorAst() { 
        $ref = (new Expression( fn($uu) => $uu->a > $uu->b ))->getReflection();
        $this->assertInstanceOf(ExpressionReflector::class, $ref);

        $ast = $ref->getAst();
        $this->assertInstanceOf(Return_::class, $ast[0]);
    }

    public function testExpressionReflectorErrorCapture() {
        $fns = [
            fn($uu) => $uu->a > $uu->b ,
        ];
        $ref = (new Expression( $fns[0]) )->getReflection();
        $this->assertInstanceOf(ExpressionReflector::class, $ref);
    }

   /*  
   // we need to find a new failure.. cause this works...
   public function testExpressionReflectorException() {
        $this->expectException(ExpressionReflectorException::class);
        $fns = [
            fn($uu) => $uu->a > $uu->b # comment
        ];
        $ref = (new Expression( $fns[0]) )->getReflection();
    }*/ 

    public function testFailOnMultipleClosures() {
        $this->expectException(ExpressionReflectorException::class);
        $fns = [ fn($a) => true, fn($a) => false ];
        $ref = (new Expression( $fns[0]) )->getReflection();
    }

}