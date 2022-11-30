<?php

declare(strict_types=1);

namespace Auth\Presentation\Requests;

use Auth\Adapter\Http\RegisterUser;
use Base\RequestSupport\Request;
use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
final class RegisterUserRequest extends FormRequest implements Request
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required'],
        ];
    }

    /**
     * @return RegisterUser
     */
    public function makeDto(): RegisterUser
    {
        return new RegisterUser($this->email, $this->password);
    }
}
