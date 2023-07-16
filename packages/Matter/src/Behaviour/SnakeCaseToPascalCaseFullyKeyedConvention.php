<?php

namespace Evident\Matter\Behaviour;

use Evident\Matter\Utilities\EntityReflector;
use Evident\Matter\Utilities\Inflector;

/**
 * Naming Convention converter for the following:
 * snake_case properties on the entities ( e.g. $artist_id )
 * PascalCase properties on the Database ( e.g. Artists.ArtisId)
 * Fully keyed, meaning the private key will be EntityId instead of id
 */
class SnakeCaseToPascalCaseFullyKeyedConvention implements NamingInterface {
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
            if ( strtolower($prop->getName() ) == 'id' ){
                $name = $this->removeNamespace($entity);
                $name .= (new Inflector($prop->getName()))->camelize()->toString();
            } else {
                $name = (new Inflector($prop->getName()))->camelize()->toString();
            }
            $remotenames[ $prop->getName() ] = $name;
        }
        return $remotenames;
    }
}