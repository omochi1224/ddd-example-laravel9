<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

/**
 *
 */
#[\Attribute]
final class Text
{
    /**
     * @param string $value
     */
    public function __construct(private string $value)
    {
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
