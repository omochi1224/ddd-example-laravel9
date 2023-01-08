<?php

declare(strict_types=1);

namespace Base\RequestSupport;

/**
 *
 */
interface Request
{
    public function authorize(): bool;

    public function rules(): array;
}
