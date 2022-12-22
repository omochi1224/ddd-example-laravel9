<?php

declare(strict_types=1);

namespace SampleHR\Presentation\Request;

use SampleHR\Application\UseCases\Register\Adapter\RegisterUserInput;

/**
 *
 */
final readonly class RegisterUserLaravelInputRequest extends BaseRequest implements RegisterUserInput
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
