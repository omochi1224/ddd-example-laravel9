<?php

use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\Config\PhpVersion;
use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__]);
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_84,
    ]);
    $rectorConfig->rules([
        ParenthesizeNestedTernaryRector::class,
        ArrayKeyExistsTernaryThenValueToCoalescingRector::class,
    ]);
    $rectorConfig->phpVersion(PhpVersion::PHP_85);

    $rectorConfig->skip([
        '**/tmp/*',
        '**/css/*',
        '**/js/*',
        '**/data/*',
        '**/doc/*',
        '**/guide/*',
        '**/images/*',
        '**/files/*',
        '**/sp/*',
        '**/tpl/*',
        '**/tests/*',
        '**/test/*',
        '**/smarty*',
        '**/vendor/*',
        '**/lp/**',
        '**/rector/**',
        '**/View/**',
    ]);
};
