<?php

namespace Evident\Lingua\Traits;

use Closure;
use Evident\Expressio\Expression;

trait Whereable {
    use TranspilesClosures;

    protected $whereConditions = [];

    public function where(Closure $closure): self
    {
        $transpilation = $this->transpileClosure($closure);
        $this->whereConditions[] = $transpilation->statement;
        return $this;
    }
}