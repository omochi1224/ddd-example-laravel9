<?php

declare(strict_types=1);

namespace SampleHR\Presentation\Request;

use Illuminate\Http\Request;

abstract readonly class BaseRequest
{
    /**
     * @param Request $request
     */
    public function __construct(protected Request $request)
    {
        $this->request->validate($this->rules());
    }

    abstract public function rules(): array;
}
