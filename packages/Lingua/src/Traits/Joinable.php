<?php

namespace Evident\Lingua\Traits;

use Closure;

trait Joinable {
    use TranspilesClosures;

    private array $joinClauses = [];

    public function join(string $table, Closure $closure, string $type = ''): self
    {
        $transpilation = $this->transpileClosure($closure);
        $condition = $transpilation->statement;
        $this->joinClauses[] = "$type JOIN $table ON $condition";
        return $this;
    }
    public function leftJoin(string $table, Closure $closure) {
        return $this->join($table, $closure, 'LEFT');
    }
}