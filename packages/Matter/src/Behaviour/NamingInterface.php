<?php
namespace Evident\Matter\Behaviour;
interface NamingInterface {
    public function tableFromEntity(String|Object $class): String;
}