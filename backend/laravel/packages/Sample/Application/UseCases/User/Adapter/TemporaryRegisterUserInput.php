<?php

declare(strict_types=1);

namespace Sample\Application\UseCases\User\Adapter;

/**
 *
 */
interface TemporaryRegisterUserInput
{
    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getPassword(): string;
}
