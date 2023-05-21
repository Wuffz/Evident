<?php

namespace Evident\Matter\Driver\PDO;

use Closure;
use Evident\Expressio\Expression;
use Evident\Expressio\Transpiler\AnsiSqlTranspilation;
use Evident\Expressio\Transpiler\AnsiSqlTranspiler;
use Evident\Expressio\Transpiler\TranspilationInterface;
use Evident\Matter\DataSource\RecordSetInterface;
use Evident\Matter\DataSource\RemoteDataSetInterface;
use Evident\Matter\Withable;
use PDO;
use PDOStatement;

/**
 * Represents a single table for query building.
 */
class DataSet implements RemoteDataSetInterface
{
    use Withable;
    private $local_name;

    /**
     * @var \Evident\Expressio\Expression[]
     */
    private $filters;

    private int $skip;
    private int $take;

    private PDO $pdo;
    /**
     * set the local entity name, eg entity name
     *
     * @param string $name
     * 
     * @return void
     * 
     */
    public function setLocalName(string $name): void
    {
        $this->local_name = $name;
    }
    /**
     * get the local name e.g. entity name
     *
     * @return string
     * 
     */
    public function getLocalName(): string
    {
        return $this->local_name;
    }

    /**
     * string version of the remote dataset name ( e.g. table name )
     *
     * @return string
     * 
     */
    public function getRemoteName(): string
    {
        // for now.
        return $this->local_name;
    }
    /**
     * Set the current pdo connection
     *
     * @param PDO $pdo
     * 
     * @return void
     * 
     */
    public function setConnection(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    // querying capabilities
    public function filter(Closure $expr): self
    {
        $expression = new Expression($expr);
        return $this->withProperty('filters', $this->filters + $expression);

    }
    public function skip(int $count): self
    {
        return $this->withProperty('skip', $count);
    }
    public function take(int $count): self
    {
        return $this->withProperty('take', $count);
    }
    private function getWhereTranspilation(array $aliasses = []): AnsiSqlTranspilation
    {

        // compile multiple into one transpilation
        $statements = [];
        $bindings = [];

        $transpiler = new AnsiSqlTranspiler();
        $transpiler->setAliasses($aliasses);
        foreach ($this->filters as $expr) {
            $expr = $transpiler->transpile($expr);
            $statements[] = ' ( ' . $expr->statement . ' ) ';
            $bindings = $bindings + $expr->bindings;
        }

        $statement = implode(" && ", $statements);
        $transpilation = new AnsiSqlTranspilation();
        $transpilation->statement = $statement;
        $transpilation->bindings = $bindings;
        return $transpilation;

    }
    private function getSelectPdoStatement($context = []): PDOStatement
    {
        $query = 'select * from ' . $this->getRemoteName();
        $bindings = [];

        if (count($this->filters) > 0) {
            $where = $this->getWhereTranspilation();
            $bindings = $bindings + $where->bindings;
            $query .= ' WHERE ' . $where->statement;
        }

        if ($this->take !== null) {
            $query .= ' LIMIT ' . $this->take;
        }

        if ($this->skip !== null) {
            $query .= ' OFFSET ' . $this->skip;
        }


        $stmt = $this->pdo->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL]);
        foreach ($bindings as $binding => $value) {
            $stmt->bindParam($binding, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        return $stmt;

    }
    /**
     * Fetch the first occurence in query
     * 
     * @todo: Optional decent entity dehydration
     *
     * @param Closure|null $expr
     * @param string $type
     * 
     * @return object
     * 
     */
    public function first(?Closure $expr = null): object
    {
        // actual fetching using filters, take and skip
        $stmt = $this->filter($expr)->take(1)->getSelectPdoStatement();
        return $stmt->fetchObject(PDO::FETCH_CLASS);
    }
    public function last(?Closure $expr = null): mixed
    {
        $stmt = $this->filter($expr)->take(1)->getSelectPdoStatement();
        return $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_LAST);
    }
    public function all(): RecordSetInterface
    {
        $stmt = $this->getSelectPdoStatement();
        return new RecordSet($stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_OBJ));
    }

    public function combine(DataSetInterface $dataset, Closure $expr): RemoteDataSetInterface
    {

        throw new \Exception('To be implemented.');
        /* 
        if ($dataset instanceof self && $dataset->pdo === $this->pdo) {
            // use the remote source to do the combining and mapping    
        } else {
            $this->all()->
        }*/.


        // determine if the datasources are the same, otherwise get both datasets and combine locally.
    }
    public function map(Closure $expression): RemoteDataSetInterface
    {
        // basically an aggregated select here
    }
    public function groupBy(Closure $expression): RemoteDataSetInterface
    {

    }
    public function getQueryable(): RemoteDataSetInterface
    {

    }

}
