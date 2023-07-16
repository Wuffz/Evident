<?php

namespace Evident\Matter\Driver\PDO;

use Evident\Bunch\Collection;
use Evident\Matter\DataSource\RecordSetInterface;
use PDO;

// represents the entities in a recordset, extends on Collection , is mutable
// @todo: should this also be a basic Repository pattern ? or should it use an entity manager?
class RecordSet extends Collection implements RecordSetInterface
{
    private PDO $pdo;

    /**
     * 
     * @todo wrap the entities using a proxy class for hedration and dehydration using their own pdo reference ??
     * @param array $entities
     * @param PDO $pdo
     * 
     * @return void
     * 
     */
    public function __construct(array $entities, PDO $pdo)
    {
        $this->pdo = $pdo;

        parent::__construct($entities);
    }
}
