<?php

declare(strict_types=1);

namespace Base\UseCaseSupport;

use Base\ExceptionSupport\DomainException;

interface Result
{
    /**
     * @param mixed $resultValue
     *
     * @return static
     */
    public static function success(mixed $resultValue): self;

    /**
     * @param DomainException|ErrorCode $useCaseError
     *
     * @return static
     */
    public static function fail(DomainException|ErrorCode $useCaseError): self;

    /**
     * @return object|null
     */
    public function getResultValue(): null|object;

    /**
     * @return bool
     */
    public function isError(): bool;

    /**
     * @return string|null
     *
     * @throws \Exception
     */
    public function getErrorMessage(): ?string;

    /**
     * @return int|null
     */
    public function getHttpStatus(): ?int;

    /**
     * @return \Exception|DomainException|null
     */
    public function getException(): null|\Exception|DomainException;
}
