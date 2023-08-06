<?php

namespace Evident\Lingua\Traits;

use Closure;

trait Aggregatable {
    use Fetchable;
    // give me a list of the most comon aggregations
    // count, sum, avg, min, max, group_concat
    // countDistinct, sumDistinct, avgDistinct, minDistinct, maxDistinct, group_concatDistinct
    // countAll, sumAll, avgAll, minAll, maxAll, group_concatAll
    // countDistinctAll, sumDistinctAll, avgDistinctAll, minDistinctAll, maxDistinctAll, group_concatDistinctAll

    public function count(?Closure $expr = null):int {
        return $this->aggregate('COUNT', $expr);
    }
    
    public function avg(?Closure $expr = null): float {
        return $this->aggregate('AVG', $expr);
    }

    public function sum(?Closure $expr = null): float {
        return $this->aggregate('SUM', $expr);
    }

    public function min(?Closure $expr = null) {
        return $this->aggregate('MIN', $expr);
    }

    public function max(?Closure $expr = null) {
        return $this->aggregate('MAX', $expr);
    }

    private function aggregate($func, ?Closure $expr) {
        if ($expr) {
            $transpilation = $this->transpileClosure($expr);
            $columns = $transpilation->statement;
        } else {
            $columns = '*';
        }
        $this->selectColumns = [ $func.'('.$columns.') as aggregation' ];
        // fwrite( STDOUT, $this->selectColumns[0].PHP_EOL);
        return $this->fetchColumn() ?? 0;
    }
   
}