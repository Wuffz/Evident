<?php


namespace Evident\Lingua\Traits;

use Closure;
use Evident\Lingua\Query;

trait Fetchable {
    
    use Queryable;


    public function all() {
        $query = $this->getQuery();
        $stmt = $query->getPdoStatement();
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function fetchColumn() {
        $query = $this->getQuery();
        $stmt = $query->getPdoStatement();
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}