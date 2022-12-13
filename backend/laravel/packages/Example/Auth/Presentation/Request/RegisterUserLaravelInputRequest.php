<?php

declare(strict_types=1);

namespace Auth\Presentation\Request;

use App\lib\LaravelInput;
use Auth\Application\UseCases\Register\Adapter\RegisterUserInput;

/**
 *
 */
final class RegisterUserLaravelInputRequest extends LaravelInput implements RegisterUserInput
{
    /**
     * @return \string[][]
     */
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required'],
        ];
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->request->get('email');
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->request->get('password');
    }
}
