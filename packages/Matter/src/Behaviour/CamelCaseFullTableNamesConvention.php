<?php

namespace Evident\Matter\Behaviour;

use Evident\Matter\Utilities\Inflector;

class CamelCaseFullTableNamesConvention implements NamingInterface {
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
    public function tableFromEntity(String|Object $entity): String {
        $entity = $this->removeNamespace($entity);
        return (new Inflector($entity))->underscore()->pluralize()->toString();
    }
}