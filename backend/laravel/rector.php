<?php

use Rector\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;
use Rector\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__]);
    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
    ]);
    $rectorConfig->rules([
        ParenthesizeNestedTernaryRector::class, // 三項演算子のネスト
        ArrayKeyExistsTernaryThenValueToCoalescingRector::class, // array_key_exists()対応
    ]);
    $rectorConfig->phpVersion(PhpVersion::PHP_81);

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
        'lib/classes/MPO/PDF/tcpdf/*',
        '/lib/emojiCfgData/*',
        '/lib/Google/*',
        '/lib/PHPWord/*',
        '/lib/royalcanin/*',
        LongArrayToShortArrayRector::class, // array() -> []への変換（php-cs-fixer側でリファクタを行う)
        RemoveExtraParametersRector::class, // function method parameter remove
        JsonThrowOnErrorRector::class, // json_encode()またはjson_decode()にJSON_THROW_ON_ERRORオプションを渡さない
        OptionalParametersAfterRequiredRector::class, // デフォルト引数の順番を変更するルール
    ]);
};
