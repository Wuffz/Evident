<?php

namespace Evident\Lingua\Traits;

use Closure;
use Evident\Lingua\Query;

trait Queryable {

    public function getQuery(): Query {
        throw new \Exception('getQuery not implemented!');
        return new Query($this->pdo, '', []);
    }  
}