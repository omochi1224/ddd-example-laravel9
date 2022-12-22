<?php

declare(strict_types=1);

namespace SampleHR\Presentation\Controllers;

use Base\ExceptionSupport\ToFrameworkException;
use SampleHR\Application\UseCases\Register\Adapter\RegisterUserInput;
use SampleHR\Application\UseCases\Register\TemporaryRegisterUserUseCase;
use SampleHR\Presentation\Resource\RegisterUserResource;

/**
 *
 */
readonly final class RegisterUserController
{
    /**
     * @param RegisterUserInput            $input
     * @param TemporaryRegisterUserUseCase $useCase
     * @param RegisterUserResource         $resource
     *
     * @return RegisterUserResource
     *
     * @throws ToFrameworkException
     */
    public function __invoke(
        RegisterUserInput $input,
        TemporaryRegisterUserUseCase $useCase,
        RegisterUserResource $resource
    ): RegisterUserResource {
        return $resource($useCase($input));
    }
}
