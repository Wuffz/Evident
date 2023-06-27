<?php

namespace Evident\Matter\Utilities;

use Evident\Bunch\Collection;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

class EntityReflector {
    private ReflectionClass $reflection;
    public function __construct(Object|string $entity) {
        $this->reflection = new ReflectionClass($entity);
    }
    public function getProperties() {
        return $this->getScalarProperties();
    }
    public function getScalarProperties(): Collection {
       
        $props = new Collection( $this->reflection->getProperties() );
        $props->filter(function(ReflectionProperty $p) {
            if ( $p instanceof ReflectionNamedType ) {
                return in_array($p->getType()->getName(), [
                    'int','float','string','bool'
                ]);
            } else 
            if ( $p instanceof ReflectionUnionType ) {
                // only allow a nullable single scalar here.
                $types = new Collection( $p->getTypes() );
                $types = $types->filter(function ( ReflectionNamedType $type ) {
                    return $type->getName() !== 'null'; // filter out null's 
                });
                if ( $types->count() > 1 ) {
                    return false;
                }
                return in_array($types->first()->getName(), [
                    'int','float','string','bool'
                ]);
                
            }
        });
        return $props;
    }
}