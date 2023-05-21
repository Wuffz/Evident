<?php
namespace Evident\Matter\Driver\PDO;

use Evident\Matter\DataSource\DriverInterface;
use Evident\Matter\DataSource\RemoteDataSetInterface;
use Evident\Matter\Driver\PDO\DataSet;
use PDO;

class Driver implements DriverInterface
{
    private PDO $pdo;
    /**
     * @param PDO $pdo
     * @return void
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    /**
     * Represents an SQL Database Table, by name
     *
     * @param mixed $table
     * 
     * @return RemoteDataSetInterface
     * 
     */
    public function from(string $table): RemoteDataSetInterface
    {
        $dataset = new RemoteDataSet();
        $dataset->setLocalName($table);
        $dataset->setConnection($this->pdo);
        return $dataset;
    }
}
