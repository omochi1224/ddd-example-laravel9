<?php

declare(strict_types=1);

namespace Auth\Domain\Models\User\ValueObject;


use Base\DomainSupport\ValueObject\StringValueObject;

/**
 * 仮登録に発行される確認トークン
 */
final class AccessToken extends StringValueObject
{
    /**
     * @return AccessToken
     */
    public static function generate(): AccessToken
    {
        $token = sha1(uniqid((string)mt_rand(), true));
        return new AccessToken($token);
    }
}
