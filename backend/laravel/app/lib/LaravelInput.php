<?php

declare(strict_types=1);

namespace App\lib;


use Illuminate\Http\Request;

abstract class LaravelInput
{
    /**
     * @param Request $request
     */
    public function __construct(protected readonly Request $request)
    {
        $this->request->validate($this->rules());
    }

    /**
     * @return array
     */
    abstract public function rules(): array;
}
