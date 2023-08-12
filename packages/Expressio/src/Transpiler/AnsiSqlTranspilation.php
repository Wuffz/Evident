<?php

namespace Evident\Expressio\Transpiler;

use Evident\Expressio\Transpiler\TranspilationInterface;
/**
 * @codeCoverageIgnore
 * @todo Rewrite this using getters and setters, so we can have code coverage
 */
class AnsiSqlTranspilation implements TranspilationInterface{
    public String $statement;
    public Array $bindings;
}