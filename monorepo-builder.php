<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\Config\MBConfig;

// @todo: we need more configuration here

return static function (MBConfig $mbConfig): void {
    $mbConfig->packageDirectories([__DIR__ . '/packages']);
};
