<?php

namespace Evident\Matter\Utilities;

use ReflectionClass;

trait Withable
{
    /**
     * Allows to set a protected or private property on the class, and returning a clone. 
     * therefore the class will be immutable
     *
     * @param mixed $property
     * @param mixed $value
     * @param ?Object $obj
     * 
     * @return self
     * 
     */
    private function ObjectWithProperty(string $property, mixed z$value, $obj): self
    {

        $clone = clone $this;

        // Using reflection to make property public
        $reflectionClass = new ReflectionClass($clone);
        $reflectionProperty = $reflectionClass->getProperty($property);
        $originalVisibility = $reflectionProperty->isPublic() ? "public" : ($reflectionProperty->isProtected() ? "protected" : "private");
        $reflectionProperty->setAccessible(true);

        // set the actual value
        $reflectionProperty->setValue($clone, $value);

        // Restore property visibility and return the clone
        $reflectionProperty->setAccessible(false);
        if ($originalVisibility == "protected") {
            $reflectionProperty->setAccessible(true);
            $reflectionClass->getMethod('setProtectedPropertyValue')->invoke($clone, $property, $value);
            $reflectionProperty->setAccessible(false);
        } else {
            $reflectionProperty->setAccessible($originalVisibility == "public" ? true : false);
        }

        return $clone;
    }
    
    protected function setProtectedPropertyValue($propertyName, $value)
    {
        $this->$propertyName = $value;
    }


    /**
     * Accepts an array of named properties so we can set them in one go.
     *
     * @param mixed ...$properties
     * 
     * @return self
     * 
     */
    private function withProperties(...$properties): self
    {
        $target = $this;
        foreach ($properties as $property => $value) {
            $target = $target->withProperty($property, $value);
        }
        return $target;
    }

}
