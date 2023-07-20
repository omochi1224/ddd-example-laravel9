<?php

declare(strict_types=1);

namespace Base\UseCaseSupport;

use Base\ExceptionSupport\DomainException;

interface Result
{
    /**
     * @return static
     */
    public static function success(mixed $resultValue): self;

    /**
     * @return static
     */
    public static function fail(DomainException|ErrorCode $useCaseError): self;

    public function getResultValue(): null|object;

    public function isError(): bool;

    /**
     * @throws \Exception
     */
    public function getErrorMessage(): ?string;

    public function getHttpStatus(): ?int;

    /**
     * @return \Exception|DomainException|null
     */
    public function getException(): null|\Exception|DomainException;
}
