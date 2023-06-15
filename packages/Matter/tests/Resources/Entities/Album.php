<?php
declare(strict_types=1);
namespace Evident\Matter\Tests\Resources\Entities;

/**
 * Example Album Entity
 */
class Album {
    // <AlbumID => ClassNameId or just Id as a convention >
    private int $albumId;
    private string $title;
    // ClassNameId will be resolved to Artist::class?
    private int $artistId; 
}