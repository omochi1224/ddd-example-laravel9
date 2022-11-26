<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

#[\Attribute] final class HttpStatusCode
{
    /**
     * @param int $status
     */
    public function __construct(private int $status)
    {
    }

    /**
     * @return int
     */
    public function value(): int
    {
        return $this->status;
    }
}
