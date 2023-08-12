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

class UpdateQueryBuilder {

    use Tableable;
    use Whereable;
    use Joinable;
    use Bindingable;

    use BuildsQueries;

    private \PDO $connection;
    private TranspilerInterface $transpiler;
    
    protected string $updateTable;
    protected array $updateValues = [];
    
    public function __construct(\PDO $connection, TranspilerInterface $transpiler ) {
        $this->connection = $connection;
        $this->transpiler = $transpiler;
    }    
    
    public function update($table): self {
        $this->setTable($table);
        return $this;
    }
    
    // use closure here.
    public function set(array $values): self
    {
        $this->updateValues = array_merge($this->updateValues, $values);
        return $this;
    }

    public function getQuery(): Query
    {
        $query = "UPDATE {$this->table} SET ";

        $setColumns = [];

        foreach ($this->updateValues as $column => $value) {
            $setColumns[] = "$column = :$column";
            $this->addBinding(':'.$column, $value);
        }
        
        $query .= implode(', ', $setColumns);

        return new Query(
            $this->connection,
            $this->buildQuery($query),
            $this->getBindings()
        );
    }
    
}
