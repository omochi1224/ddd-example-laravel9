<?php

declare(strict_types=1);

namespace Base\RequestSupport;

/**
 *
 */
interface Request
{
    /**
     * @return bool
     */
    public function authorize(): bool;

    /**
     * @return array
     */
    public function rules(): array;
}
