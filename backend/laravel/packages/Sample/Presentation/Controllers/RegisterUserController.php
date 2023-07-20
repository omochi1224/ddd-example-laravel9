<?php

declare(strict_types=1);

namespace Sample\Presentation\Controllers;

use Base\ExceptionSupport\ToFrameworkException;
use Sample\Application\UseCases\User\Adapter\TemporaryRegisterUserInput;
use Sample\Application\UseCases\User\TemporaryRegisterUserUseCase;
use Sample\Presentation\Resource\RegisterUserResource;

readonly final class RegisterUserController
{
    /**
     * @return RegisterUserResource
     *
     * @throws ToFrameworkException
     */
    public function __invoke(
        TemporaryRegisterUserInput $input,
        TemporaryRegisterUserUseCase $useCase,
        RegisterUserResource $resource
    ): RegisterUserResource {
        return $resource($useCase($input));
    }
}
