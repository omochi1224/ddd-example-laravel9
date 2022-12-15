<?php

declare(strict_types=1);

namespace Base\RequestSupport;


interface Input
{
    /**
     * @return array
     */
     public function rules(): array;
}
