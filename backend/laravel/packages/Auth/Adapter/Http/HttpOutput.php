<?php

declare(strict_types=1);

namespace Auth\Adapter\Http;

use Base\DomainSupport\Domain\Domain;

/**
 *
 */
abstract class HttpOutput
{
    /**
     * @param mixed $value
     *
     * @return HttpOutput
     */
    public static function invoke(Domain $value): HttpOutput
    {
        return new static($value);
    }

    /**
     * @return mixed
     */
    abstract public function value(): mixed;
}
