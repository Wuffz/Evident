<?php


namespace Evident\Matter\Utilities;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

class Dehydrator {
    private $data = [];
    private $entity;

    public function __construct(object|string $entity) {
        if ( is_string($entity) ) {
            $reflectionClass = new ReflectionClass(ChildClass::class);
            $entity = $reflectionClass->newInstanceWithoutConstructor();
        }
        $this->entity = $entity;
        $reflection = new ReflectionObject($entity);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $this->data[$property->getName()] = $property->getValue($entity);
        }
    }

    public function set(string $name, $value) {
        if (array_key_exists($name, $this->data)) {
            $reflection = new ReflectionProperty($this->entity, $name);
            $declaringClass = $reflection->getDeclaringClass()->getName();
            $wasAccessible = $reflection->isPublic();
            $reflection->setAccessible(true);
            $reflection->setValue($this->entity, $value);
            $reflection->setAccessible($wasAccessible);
            $this->data[$name] = $value;
        } else {
            throw new InvalidArgumentException("Property '$name' does not exist in ".get_called_class($this->entity));
        }
    }
    public function with(... $params ): self {
        foreach ( $params as $param => $value ) {
            $this->set($param, $value);
        }
        return $this;
    }
    public function getEntity() {
        foreach ($this->data as $name => $value) {
            $reflection = new ReflectionProperty($this->entity, $name);
            $declaringClass = $reflection->getDeclaringClass()->getName();
            $wasAccessible = $reflection->isPublic();
            $reflection->setAccessible(true);
            $reflection->setValue($this->entity, $value);
            $reflection->setAccessible($wasAccessible);
        }

        return $this->entity;
    }
}
