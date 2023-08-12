<?php

namespace Evident\Lingua\Traits;

use Closure;

trait Orderable {

    use TranspilesClosures;

    protected $groupByColumns = [];

    public function groupBy(Closure $closure): self
    {
        $transpilation = $this->transpileClosure($closure);
        $columns = explode(", ", $transpilation->statement);
        $this->groupByColumns = array_merge($this->groupByColumns, $columns);
        return $this;
    }

}