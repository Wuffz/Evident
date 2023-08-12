<?php

namespace Evident\Lingua\Traits;

trait Offsetable {

    protected $offset = null;

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }
}