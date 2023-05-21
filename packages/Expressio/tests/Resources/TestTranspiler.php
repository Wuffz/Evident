<?php

namespace Evident\Expressio\Tests\Resources;

use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\TranspilationInterface;
use Evident\Expressio\Transpiler\TranspilerInterface;
use PhpParser\Node;

class TestTranspilation implements TranspilationInterface {
    public string $source;
}
class TestTranspiler implements TranspilerInterface {
    public function transpile(Expression $statement): TestTranspilation {
        $s = new TestTranspilation();
        $s->source = $statement->getReflection()->getSource();
        return $s;
    }
}