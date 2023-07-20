<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

/**
 *
 */
#[\Attribute]
final class Text
{
    public function __construct(private readonly string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}
