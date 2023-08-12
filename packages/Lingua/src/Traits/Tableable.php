<?php

namespace Evident\Lingua\Traits;

trait Tableable {

    protected $table = '';
    
    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function from(string $table): self 
    {
        return $this->setTable($table);
    }

    public function into(string $table): self 
    {
        return $this->setTable($table);
    }
}