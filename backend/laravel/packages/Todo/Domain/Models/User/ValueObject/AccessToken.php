<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\ValueObject;

use Base\DomainSupport\ValueObject\StringValueObject;

/**
 * 仮登録に発行される確認トークン
 */
final readonly class AccessToken extends StringValueObject
{
    public static function generate(): AccessToken
    {
        $token = sha1(uniqid((string) random_int(0, mt_getrandmax()), true));
        return new AccessToken($token);
    }
}
