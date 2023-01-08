<?php

declare(strict_types=1);

namespace Base\RequestSupport;

interface Input
{
    public function rules(): array;
}
