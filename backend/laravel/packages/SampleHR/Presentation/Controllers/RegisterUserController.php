<?php

declare(strict_types=1);

namespace SampleHR\Presentation\Controllers;

use SampleHR\Application\UseCases\Register\Adapter\RegisterUserInput;
use SampleHR\Application\UseCases\Register\RegisterUserUseCase;
use SampleHR\Presentation\Resource\RegisterUserResource;
use Base\ExceptionSupport\ToFrameworkException;

/**
 *
 */
readonly final class RegisterUserController
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
