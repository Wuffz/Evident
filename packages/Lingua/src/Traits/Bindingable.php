<?php

namespace Evident\Lingua\Traits;

trait Bindingable {

    protected $bindings = [];

    private function addBinding($var, $val) : void {
        $this->bindings[$var] = $val;
    }

    private function addBindings(... $kv) : void {
        foreach ( $kv as $k => $v ) {
            $this->addBinding($k, $v);
        }
    }

    public function getBindings(): array {
        return $this->bindings;
    }
}