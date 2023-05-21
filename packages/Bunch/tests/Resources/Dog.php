<?php

namespace Bunch\Tests\Resources;


class Dog
{
    public function __construct(
        public string $name
    ) {
    }
    public function bark()
    {
        return $this->name . ': woooof';
    }
}
