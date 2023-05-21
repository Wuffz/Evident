<?php
namespace Evident\Expressio\Transpiler;

use Evident\Expressio\Expression;

interface TranspilerInterface {
    // should return transpiled sourcecode from the statement reflector
    public function transpile(Expression $expr): TranspilationInterface; 
}