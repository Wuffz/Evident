<?php
declare(strict_types=1);
namespace Evident\Matter\Tests\Resources\Entities;

/**
 * Example Album Entity
 */
class Album {
    private int $id;
    public string $title;
    protected string $artist_id;

    public function getId() {
        return $this->id;
    }
}