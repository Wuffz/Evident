<?php

namespace Evident\Expressio\Transpiler;

use Evident\Expressio\Transpiler\TranspilationInterface;

class AnsiSqlTranspilation implements TranspilationInterface{
    public String $statement;
    public Array $bindings;
}