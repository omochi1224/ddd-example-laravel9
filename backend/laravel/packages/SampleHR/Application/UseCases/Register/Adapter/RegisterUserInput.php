<?php

declare(strict_types=1);

namespace SampleHR\Application\UseCases\Register\Adapter;

/**
 *
 */
interface RegisterUserInput
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
