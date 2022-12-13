<?php

declare(strict_types=1);

namespace Base\UseCaseSupport;

use Base\AdapterSupport\HttpOutput;
use Base\ExceptionSupport\DomainException;

/**
 * Class UseCaseResult
 *
 * @package Basic\UseCaseSupport
 */
class UseCaseResult
{
    /**
     * UseCaseResult constructor.
     *
     * @param HttpOutput|null $resultValue
     * @param ErrorCode|null  $errorCode
     */
    public function __construct(
        private readonly ?HttpOutput $resultValue,
        private readonly ?ErrorCode $errorCode
    ) {
    }

    /**
     * @param HttpOutput $resultValue
     *
     * @return static
     */
    public static function success(HttpOutput $resultValue): self
    {
        return new static($resultValue, null);
    }

    /**
     * @param DomainException|ErrorCode $useCaseError
     *
     * @return static
     */
    public static function fail(DomainException|ErrorCode $useCaseError): self
    {
        if (! ($useCaseError instanceof ErrorCode)) {
            return new static(null, ErrorCode::of($useCaseError));
        }
        return new static(null, $useCaseError);
    }

    /**
     * @return object|null
     */
    public function getResultValue(): null|object
    {
        return $this->resultValue;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return ! is_null($this->errorCode);
    }

    /**
     * @return string|null
     *
     * @throws \Exception
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorCode?->getMessage();
    }

    /**
     * @return int|null
     */
    public function getHttpStatus(): ?int
    {
        if ($this->getException() === null) {
            return 200;
        }
        return $this->getException()->getHttpStatus()->value();
    }

    /**
     * @return \Exception|DomainException|null
     */
    public function getException(): null|\Exception|DomainException
    {
        return $this->errorCode?->getException();
    }
}
