<?php

namespace Evident\Matter;

class Repository
{
    private $connectionManager;
    public function __construct($connectionManager)
    {
        $this->connectionManager = $connectionManager;
    }
}