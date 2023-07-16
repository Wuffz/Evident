<?php


namespace Evident\Matter\Utilities;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use stdClass;

class Hydrator
{
    public function __construct(
        private string $entity,
        private array $aliasses,
    )
    {  

    }

    private function remapData(stdClass $data): stdClass {
        $aliasses = array_flip($this->aliasses);
        foreach ( $aliasses as $old => $new ) {
            if ( property_exists($data, $old)) {
                $data->{$new} = $data->{$old};
                unset($data->{$old});
            }
        }
        return $data;
    }
    public function hydrate(stdClass $data, Object $entity = null): Object
    {
        $data = $this->remapData($data);
        
        if ( !$entity ) {
            $entityName = $this->entity; 
            $entity = new $entityName;
        }
        $reflection = new ReflectionClass($entity);

        foreach ($data as $property => $value) {
            if ($reflection->hasProperty($property)) {
                $propertyReflection = $reflection->getProperty($property);
                $isAccessible = $propertyReflection->isPublic() || $propertyReflection->isProtected();

                if (!$isAccessible) {
                    $propertyReflection->setAccessible(true);
                }

                $propertyReflection->setValue($entity, $value);

                if (!$isAccessible) {
                    $propertyReflection->setAccessible(false);
                }
            }
        }

        return $entity;
    }
}
