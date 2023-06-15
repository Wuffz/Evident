<?php
declare(strict_types=1);
namespace Evident\Matter\Tests\Resources\Entities;

/**
 * Example Artist Entity
 */
class Artist {
    // <artistId => classNameId,ClassNameId or just Id as a convention >
    private int $artistId;
    private int $name;
}