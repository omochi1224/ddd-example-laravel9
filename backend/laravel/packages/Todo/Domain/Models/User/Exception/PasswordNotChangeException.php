<?php

declare(strict_types=1);

namespace Todo\Domain\Models\User\Exception;

use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class PasswordNotChangeException extends DomainException
{
    #[HttpStatusCode(400)]
    public const MESSAGE = 'ソーシャルログインの場合パスワードの変更はできません。';
}
