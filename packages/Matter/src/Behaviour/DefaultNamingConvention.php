<?php
namespace Evident\Matter\Behaviour;
/*
 *  Fow now we use CamelCaseFullFullTableNamesConvention as the default because of the chonook db.
 *  We do not like the naming conventions in chinook, therefore we have a seperate default we can implement later .
 */ 
class DefaultNaming implements NamingInterface {
    <?php

namespace Evident\Matter\Behaviour;

use Evident\Matter\Utilities\EntityReflector;
use Evident\Matter\Utilities\Inflector;

class DefaultNamingConvention implements NamingInterface {
    private $namespace;
    public function __construct(string $namespace) {
        $this->namespace = ltrim($namespace,'\\');
    }
    
    private function removeNamespace(Object|string $obj) {
        $obj = $this->forceAsString($obj);
        $obj = str_replace($this->namespace.'\\', '', $obj);
        return $obj;
    }
    private function forceAsString(Object|string $obj): string {
        return is_object($obj)?get_class($obj):$obj;
    }
    public function getRemoteNameFromEntity(String|Object $entity): String {
        $entity = $this->removeNamespace($entity);
        return (new Inflector($entity))->underscore()->pluralize()->toString();
    }
    public function getRemoteNameForProperties(String|Object $entity): Array {
        if ( is_string($entity) && !class_exists($entity) ) {
            return [];
        }
        $remotenames = [];
        $ref = new EntityReflector($entity);
        //todo: shouldńt we do this with a proxy class, which can extract the wanted properties?
        foreach ( $ref->getProperties() as $prop ) {
            $name = (new Inflector($prop->getName()))->camelize()->toString();
            $remotenames[ $prop->getName() ] = $name;
        }
        return $remotenames;
    }
}
}