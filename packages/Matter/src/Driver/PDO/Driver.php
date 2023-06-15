<?php
namespace Evident\Matter\Driver\PDO;


use Evident\Matter\Behaviour\DefaultNaming;
use Evident\Matter\Behaviour\NamingInterface;
use Evident\Matter\DataSource\DriverInterface;
use Evident\Matter\DataSource\RemoteDataSetInterface;
use Evident\Matter\Driver\PDO\DataSet;
use PDO;

class Driver implements DriverInterface
{
    private PDO $pdo;
    private NamingInterface $naming;
 
    public function __construct(PDO $pdo, ?NamingInterface $naming)
    {
        $this->pdo = $pdo;
        $this->naming = $naming ?? new DefaultNaming();    
    }
    /**
     * Represents an SQL Database Table, by name
     *
     * @param mixed $table
     * 
     * @return RemoteDataSetInterface
     * 
     */
    public function from(string $tableOrEntityClass): RemoteDataSetInterface
    {
        // do detection on table or entity here
        $dataset = new RemoteDataSet();
        $remote = $this->naming->tableFromEntity($tableOrEntityClass);
        $dataset->setLocalName($tableOrEntityClass);
        $dataset->setRemoteName($remote ?? $tableOrEntityClass);
        $dataset->setConnection($this->pdo);
        return $dataset;
    }
}
