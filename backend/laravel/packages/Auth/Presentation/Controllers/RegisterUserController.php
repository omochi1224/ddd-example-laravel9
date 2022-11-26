<?php

declare(strict_types=1);

namespace Auth\Presentation\Controllers;

use Auth\Application\UseCases\RegisterUserUseCase;
use Auth\Presentation\Requests\RegisterUserRequest;
use Auth\Presentation\Resource\RegisterUserResource;
use Base\ExceptionSupport\ToFrameworkException;

final class RegisterUserController
{
    /**
     * @param RegisterUserRequest $request
     * @param RegisterUserUseCase $useCase
     *
     * @return RegisterUserResource
     *
     * @throws ToFrameworkException
     */
    public function __invoke(RegisterUserRequest $request, RegisterUserUseCase $useCase): RegisterUserResource
    {
        return new RegisterUserResource($useCase($request->makeDto()));
    }
}
