<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

#[\Attribute]final readonly class HttpStatusCode
{
    public function __construct(private int $status)
    {
    }

    public function value(): int
    {
        return $this->status;
    }
}
