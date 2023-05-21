<?php
// This file is intended for manual IDE testing. No actual processable code is in here

use Bunch\Tests\Resources\Dog;
use Evident\Bunch\Enumerator;


/*
Test if $dog is :
Dog $dog
*/
$collection = new Enumerator([new Dog('odin'), new Dog('loki')]);

$dog = $collection
    ->where(fn(Dog $dog) => $dog->name == 'odin')
    ->first();
