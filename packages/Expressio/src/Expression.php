<?php

namespace Evident\Expressio;

use Evident\Expressio\Transpiler\TranspilerInterface;

class Expression {
    
    private ExpressionReflector $reflection;
    
    public function __construct(\Closure $closure) {
        // for convinience sake. We should use a factory here so we can tell it which reflector to use ?
        $this->reflection = new ExpressionReflector($closure);       
    }

    public function getReflection(): ExpressionReflector {
        return $this->reflection;
    } 
}