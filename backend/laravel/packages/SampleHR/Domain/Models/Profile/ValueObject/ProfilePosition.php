<?php

declare(strict_types=1);

namespace SampleHR\Domain\Models\Profile\ValueObject;


enum ProfilePosition: int
{
    /** 主任 */
    case chief = 0;
}
