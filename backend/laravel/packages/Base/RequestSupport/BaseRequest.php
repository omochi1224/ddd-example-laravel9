<?php

declare(strict_types=1);

namespace Base\RequestSupport;

use Base\Request\ApiFormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    use ApiFormRequestTrait;

    /**
     * @return bool
     */
    abstract public function authorize(): bool;

    /**
     * @return array
     */
    abstract public function rules(): array;
}
