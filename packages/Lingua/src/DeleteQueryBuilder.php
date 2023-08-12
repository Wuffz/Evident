<?php

namespace Evident\Lingua;

use \Closure;
use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\TranspilerInterface;
use Evident\Lingua\Traits\BuildsQueries;
use Evident\Lingua\Traits\Fromable;
use Evident\Lingua\Traits\Groupable;
use Evident\Lingua\Traits\Joinable;
use Evident\Lingua\Traits\Limitable;
use Evident\Lingua\Traits\Offsetable;
use Evident\Lingua\Traits\Orderable;
use Evident\Lingua\Traits\Tableable;
use Evident\Lingua\Traits\TranspilesClosures;
use Evident\Lingua\Traits\Whereable;

class DeleteQueryBuilder {

    use Whereable;
    use Joinable;
    use BuildsQueries;
    use Tableable;

    private \PDO $connection;
    private TranspilerInterface $transpiler;
    
    
    public function __construct(\PDO $connection, TranspilerInterface $transpiler ) {
        $this->connection = $connection;
        $this->transpiler = $transpiler;
    }    

    public function from(string $table = null): self
    {
        return $this->setTable($table);
    }

    public function getQuery(): Query
    {
        $query = "DELETE FROM {$this->table}";

        return new Query(
            $this->connection,
            $this->buildQuery($query),
            $this->getBindings()
        );
    }
    
}
