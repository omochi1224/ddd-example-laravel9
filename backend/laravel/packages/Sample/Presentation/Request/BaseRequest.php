<?php

declare(strict_types=1);

namespace Sample\Presentation\Request;

use Illuminate\Http\Request;

/**
 *
 */
abstract readonly class BaseRequest
{
    /**
     * @param Request $request
     */
    public function __construct(protected Request $request)
    {
        $this->request->validate($this->rules());
    }

    /**
     * @return array<mixed>
     */
    abstract public function rules(): array;
}
