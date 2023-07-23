<?php

namespace Evident\Expressio\Tests\Unit;

use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\AnsiSqlTranspiler;
use Evident\Expressio\Tests\Resources\User;
use PHPUnit\Framework\TestCase;

class SqlTranspilerTest extends TestCase
{

    private AnsiSqlTranspiler $transpiler;
    private array $operators;
    private $min_age = 18;


    public function setUp(): void
    {
        // pass aliasses for classes

    }
    // we should test with remapping, most of these tests are now in Matter, which is okay, but should be here aswell.

    public function assertAsSql(\Closure $fn, string $expected, array $expected_bindings = [])
    {
        // setup transpiler
        $transpiler = new AnsiSqlTranspiler();
        $transpiler->disableAntiColide();
        $transpiler->setAliasses([User::class => 'users']);


        $expr = new Expression($fn);
        $transpilation = $transpiler->transpile($expr);
        $statement = $transpilation->statement;
        $bindings = $transpilation->bindings;

        //var_dump($bindings);

        $this->assertEquals($expected, $statement);
        foreach ($expected_bindings as $k => $v) {
            $this->assertEquals($v, $bindings[$k]);
        }


    }
    public function testTranspilationToSqlWithBindings()
    {
        $max_age = 200;

        $this->assertAsSql(
            fn(User $u) => $u->admin == true && ($u->age > $this->min_age && $u->age < $max_age),
            '( users.admin = 1 AND ( users.age > :this.min_age AND users.age < :max_age ) )',
            [
                ':this.min_age' => $this->min_age,
                ':max_age' => $max_age,
            ]
        ); 
    }
    public function testTranspilationBasicOperators() {
        // comparisons
        $this->assertAsSql(fn($a, $b) => $a == $b, 'a = b');
        $this->assertAsSql(fn($a, $b) => $a > $b, 'a > b');
        $this->assertAsSql(fn($a, $b) => $a < $b, 'a < b');
        $this->assertAsSql(fn($a, $b) => $a * $b, 'a * b');

        // mathematical equations
        $this->assertAsSql(fn($a, $b) => $a % $b, 'MOD( a , b )');
        $this->assertAsSql(fn($a, $b) => $a ** $b, 'POWER( a , b )');

        // concat
        // $this->assertAsSql( fn( $a , $b  ) => $a . $b, 'a || b' ); // in sql we want to work with objects mapping, not new objects.. perhaps in the furure there's a need for this.
    }

}
