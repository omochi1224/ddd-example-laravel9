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
     * @return array<array<\string>>
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
