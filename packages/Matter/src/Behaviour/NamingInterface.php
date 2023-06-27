<?php
namespace Evident\Matter\Behaviour;
interface NamingInterface {
    public function getRemoteNameFromEntity(String|Object $class): String;
    public function getRemoteNameForProperties(String|Object $class): array;
}