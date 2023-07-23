<?php

namespace Evident\Lingua;

use \Closure;
use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\TranspilationInterface;
use Evident\Expressio\Transpiler\TranspilerInterface;

class SelectQuery {

    private \PDO $connection;
    private TranspilerInterface $transpiler;

    protected $selectColumns = [];
    protected $fromTable = '';
    protected $joinClauses = [];
    protected $whereConditions = [];
    protected $orderByColumns = [];
    protected $groupByColumns = [];
    protected $limit = null;
    protected $offset = null;
    protected $bindings = [];

    public function __construct(\PDO $connection, TranspilerInterface $transpiler ) {
        $this->connection = $connection;
        $this->transpiler = $transpiler;
    }    

    public function select(Closure $select): self
    {
        $expr = new Expression($select);
        $transpilation = $this->transpileExpression($expr);
        $columns = explode(", ", $transpilation->statement);
        $this->selectColumns = array_merge($this->selectColumns, $columns);
        return $this;
    }

    public function from(string $table): self
    {
        $this->fromTable = $table;
        return $this;
    }

    public function join(string $table, Closure $closure, string $type = 'INNER'): self
    {
        $expr = new Expression($closure);
        $transpilation = $this->transpileExpression($expr);
        $condition = $transpilation->statement;
        $this->joinClauses[] = "$type JOIN $table ON $condition";
        return $this;
    }
    public function leftJoin(string $table, Closure $closure) {
        return $this->join($table, $closure, 'LEFT');
    }

    public function where(Closure $closure): self
    {
        $expr = new Expression($closure);
        $transpilation = $this->transpileExpression($expr);
        $this->whereConditions[] = $transpilation->statement;
        return $this;
    }

    public function orderBy(Closure $closure): self
    {
        $expr = new Expression($closure);
        $transpilation = $this->transpileExpression($expr);
        $columns = explode(", ", $transpilation->statement);
        $this->orderByColumns = array_merge($this->orderByColumns, $columns);
        return $this;
    }

    public function groupBy(Closure $closure): self
    {
        $expr = new Expression($closure);
        $transpilation = $this->transpileExpression($expr);
        $columns = explode(", ", $transpilation->statement);
        $this->groupByColumns = array_merge($this->groupByColumns, $columns);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getQuery(): array
    {
        $query = "SELECT " . implode(', ', $this->selectColumns);
        $query .= " FROM {$this->fromTable}";

        if (!empty($this->joinClauses)) {
            $query .= ' ' . implode(' ', $this->joinClauses);
        }

        if (!empty($this->whereConditions)) {
            $query .= ' WHERE ' . implode(' AND ', $this->whereConditions);
        }

        if (!empty($this->groupByColumns)) {
            $query .= ' GROUP BY ' . implode(', ', $this->groupByColumns);
        }

        if (!empty($this->orderByColumns)) {
            $query .= ' ORDER BY ' . implode(', ', $this->orderByColumns);
        }

        if ($this->offset !== null) {
            $query .= " OFFSET {$this->offset}";
        }

        if ($this->limit !== null) {
            $query .= " LIMIT {$this->limit}";
        }

        return [ $query, $this->bindings ];
    }
    protected function transpileExpression(Expression $expression): TranspilationInterface
    {
        $transpilation = $this->transpiler->transpile($expression);
        $this->bindings = array_merge($transpilation->bindings);
        return $transpilation;
        // Implement your transpiler logic here
        // This function should convert the Expression object to a valid SQL statement
        // using your existing AnsiSqlTranspiler or any custom logic.
        // For simplicity, I'll assume it returns a TranspilationResult object.
    }
}
