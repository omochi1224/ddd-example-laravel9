<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

#[\Attribute] final class HttpStatusCode
{
    /**
     * @param integer $status
     */
    public function __construct(private int $status)
    {
    }

    /**
     * @return integer
     */
    public function value(): int
    {
        return $this->status;
    }
}
