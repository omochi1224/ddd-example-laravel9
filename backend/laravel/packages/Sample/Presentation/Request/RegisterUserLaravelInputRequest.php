<?php

declare(strict_types=1);

namespace Sample\Presentation\Request;

use Sample\Application\UseCases\User\Adapter\TemporaryRegisterUserInput;

/**
 *
 */
final readonly class RegisterUserLaravelInputRequest extends BaseRequest implements TemporaryRegisterUserInput
{
    /**
     * @return  array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required'],
        ];
    }

    public function getEmail(): string
    {
        return $this->request->get('email');
    }

    public function getPassword(): string
    {
        return $this->request->get('password');
    }
}
