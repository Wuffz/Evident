<?php

namespace Evident\Lingua\Traits;

trait Limitable {

    protected $limit = null;

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
}