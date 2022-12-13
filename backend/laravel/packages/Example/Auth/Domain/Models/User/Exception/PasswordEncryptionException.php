<?php

declare(strict_types=1);

namespace Auth\Domain\Models\User\Exception;


use Base\AttributeSupport\HttpStatusCode;
use Base\ExceptionSupport\DomainException;

final class PasswordEncryptionException extends DomainException
{
    #[HttpStatusCode(500)]
    public const MESSAGE = 'パスワードはすでに暗号化されています。';
}
