<?php

namespace Evident\Lingua\Traits;

use Closure;

trait Groupable {

    use TranspilesClosures;

    protected $orderByColumns = [];

    
    public function orderBy(Closure $closure): self
    {
        $transpilation = $this->transpileClosure($closure);
        $columns = explode(", ", $transpilation->statement);
        $this->orderByColumns = array_merge($this->orderByColumns, $columns);
        return $this;
    }
}