<?php

declare(strict_types=1);

namespace Base\DomainSupport\Exception;

use Base\ExceptionSupport\Exception;

/**
 * 識別子エラー
 */
final class InvalidUuidException extends Exception
{
    protected $message = '正しい識別子ではありません。';
    protected $code = 422;
}
