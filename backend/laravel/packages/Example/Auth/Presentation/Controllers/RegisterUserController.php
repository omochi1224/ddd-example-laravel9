<?php

declare(strict_types=1);

namespace Auth\Presentation\Controllers;

use Auth\Application\UseCases\Register\Adapter\RegisterUserInput;
use Auth\Application\UseCases\Register\RegisterUserUseCase;
use Auth\Presentation\Resource\RegisterUserResource;
use Auth\Presentation\Sender\Sender;
use Base\ExceptionSupport\ToFrameworkException;

/**
 *
 */
final class RegisterUserController
{
    /**
     * @param RegisterUserInput    $input
     * @param RegisterUserUseCase  $useCase
     * @param RegisterUserResource $resource
     *
     * @return RegisterUserResource
     *
     * @throws ToFrameworkException
     */
    public function __invoke(
        RegisterUserInput $input,
        RegisterUserUseCase $useCase,
        RegisterUserResource $resource
    ): RegisterUserResource {
        return $resource($useCase($input));
    }
}
