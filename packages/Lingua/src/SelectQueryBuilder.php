<?php

namespace Evident\Lingua;

use \Closure;
use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\TranspilerInterface;
use Evident\Lingua\Traits\Aggregatable;
use Evident\Lingua\Traits\BuildsQueries;
use Evident\Lingua\Traits\Fetchable;
use Evident\Lingua\Traits\Groupable;
use Evident\Lingua\Traits\Joinable;
use Evident\Lingua\Traits\Limitable;
use Evident\Lingua\Traits\Offsetable;
use Evident\Lingua\Traits\Orderable;
use Evident\Lingua\Traits\Tableable;
use Evident\Lingua\Traits\TranspilesClosures;
use Evident\Lingua\Traits\Whereable;

class SelectQueryBuilder {

    use Whereable;
    use Limitable;
    use Offsetable;
    use Joinable;
    use Groupable;
    use Orderable;
    use Tableable;
    use Aggregatable;
    use Fetchable;

    use TranspilesClosures;
    use BuildsQueries;

    private \PDO $connection;
    private TranspilerInterface $transpiler;
    
    protected $selectColumns = [];
    
    public function __construct(\PDO $connection, TranspilerInterface $transpiler ) {
        $this->connection = $connection;
        $this->transpiler = $transpiler;
    }    
    
    public function select(?Closure $select = null): self
    {   if ( $select !== null ) {
            $transpilation = $this->transpileClosure($select);
            $columns = explode(", ", $transpilation->statement);
        } else {
            $columns = ['*'];
        }
        $this->selectColumns = array_merge($this->selectColumns, $columns);
        return $this;
    }

    public function getQuery(): Query
    {
        $query = "SELECT " . implode(', ', $this->selectColumns);
        $query .= " FROM {$this->table}";
        return new Query(
            $this->connection,
            $this->buildQuery($query),
            $this->getBindings()
        );
    }

}
