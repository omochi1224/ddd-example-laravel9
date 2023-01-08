<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

/**
 *
 */
#[\Attribute]final readonly class Text
{
    public function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}
