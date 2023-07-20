<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

#[\Attribute] final class HttpStatusCode
{
    public function __construct(private readonly int $status)
    {
    }

    public function value(): int
    {
        return $this->status;
    }
}
