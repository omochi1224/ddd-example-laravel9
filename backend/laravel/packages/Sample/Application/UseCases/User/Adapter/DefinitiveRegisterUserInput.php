<?php

declare(strict_types=1);

namespace Sample\Application\UseCases\User\Adapter;

/**
 *
 */
interface DefinitiveRegisterUserInput
{
    /**
     * @return string
     */
    public function getUserId(): string;

    /**
     * @return array{lastName: string, firstName: string}
     */
    public function getName(): array;

    /**
     * @return \DateTime
     */
    public function getBirthday(): \DateTime;

    /**
     * @return string
     */
    public function getImage(): string;

    /**
     * @return int
     */
    public function getGender(): int;
}
