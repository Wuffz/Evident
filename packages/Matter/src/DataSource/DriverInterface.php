<?php
namespace Evident\Matter\DataSource;

interface DriverInterface
{
    public function from(string $table): RemoteDataSetInterface;
}
