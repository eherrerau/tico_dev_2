<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/public',
        __DIR__ . '/scripts',
    ]);

    $rectorConfig->skip([
        __DIR__ . '/vendor',
        __DIR__ . '/templates_c',
        __DIR__ . '/logs',
        __DIR__ . '/data',
        __DIR__ . '/zamorafr',
        __DIR__ . '/trash',
    ]);

    // Define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::INSTANCEOF,
    ]);

    // Import names
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    // Parallel processing
    $rectorConfig->parallel(120, 8, 2);
};
