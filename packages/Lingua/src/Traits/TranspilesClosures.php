<?php

namespace Evident\Lingua\Traits;

use Closure;
use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\TranspilationInterface;

trait TranspilesClosures {

    use Bindingable;

    protected function transpileClosure(Closure $closure): TranspilationInterface
    {
        $expression = new Expression($closure);
        $transpilation = $this->transpiler->transpile($expression);
        $this->addBindings($transpilation->bindings);
        return $transpilation;
    }
}