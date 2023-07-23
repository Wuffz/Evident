<?php

namespace Evident\Lingua;

use \Closure;
use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\TranspilerInterface;
use Evident\Lingua\Traits\Bindingable;
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

class InsertQueryBuilder {

    use Tableable;
    use Bindingable;

    use BuildsQueries;

    private \PDO $connection;
    private TranspilerInterface $transpiler;
    
    protected string $insertTable;
    protected array $insertValues = [];
    
    public function __construct(\PDO $connection, TranspilerInterface $transpiler ) {
        $this->connection = $connection;
        $this->transpiler = $transpiler;
    }    

    public function values(array $values): self
    {
        $this->insertValues[] = $values;

        return $this;
    }

    public function getQuery(): Query
    {
        $query = "INSERT INTO {$this->table} (";

        // Get the columns from the first set of values (assuming all sets have the same columns)
        $columns = array_keys($this->insertValues[0]);
        $query .= implode(', ', $columns);

        $query .= ") VALUES ";

        $valuePlaceholders = array_fill(0, count($columns), '?');
        $valuePlaceholderString = "(" . implode(', ', $valuePlaceholders) . ")";
        $valuePlaceholderStrings = array_fill(0, count($this->insertValues), $valuePlaceholderString);

        $query .= implode(', ', $valuePlaceholderStrings);

        return new Query(
            $this->connection,
            $this->buildQuery($query),
            $this->getBindings()
        );
    }
    
}
