<?php

declare(strict_types=1);

namespace Base\UseCaseSupport;

use Base\ExceptionSupport\DomainException;
use Base\ExceptionSupport\Exception;

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
     * @param object|null    $resultValue
     * @param ErrorCode|null $errorCode
     */
    public function __construct(private ?object $resultValue, private ?ErrorCode $errorCode)
    {
    }

    /**
     * @param object $resultValue
     *
     * @return static
     */
    public static function success(object $resultValue): self
    {
        return new static($resultValue, null);
    }

    /**
     *
     * @param DomainException|ErrorCode $useCaseError
     *
     * @return static
     */
    public static function fail(DomainException|ErrorCode $useCaseError): self
    {
        if (!($useCaseError instanceof ErrorCode)) {
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
     * @return boolean
     */
    public function isError(): bool
    {
        return !is_null($this->errorCode);
    }

    /**
     * @return string|null
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
     * @return Exception|null
     */
    public function getException(): ?Exception
    {
        return $this->errorCode?->getException();
    }
}
