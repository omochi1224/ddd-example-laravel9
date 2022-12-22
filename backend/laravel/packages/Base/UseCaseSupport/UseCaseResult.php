<?php

declare(strict_types=1);

namespace Base\UseCaseSupport;

use Base\AdapterSupport\AdapterOutput;
use Base\ExceptionSupport\DomainException;
use Exception;

/**
 * Class UseCaseResult
 *
 * @package Basic\UseCaseSupport
 */
readonly final class UseCaseResult
{
    /**
     * UseCaseResult constructor.
     *
     * @param AdapterOutput|null $resultValue
     * @param ErrorCode|null     $errorCode
     */
    public function __construct(
        private ?AdapterOutput $resultValue = null,
        private ?ErrorCode $errorCode = null,
    ) {
    }

    /**
     * @param mixed $adapterOutput
     *
     * @return static
     */
    public static function success(AdapterOutput $adapterOutput): self
    {
        return new UseCaseResult($adapterOutput);
    }

    /**
     * @param DomainException|ErrorCode $useCaseError
     *
     * @return static
     */
    public static function fail(DomainException|ErrorCode $useCaseError): self
    {
        if (! ($useCaseError instanceof ErrorCode)) {
            return new UseCaseResult(errorCode: ErrorCode::of($useCaseError));
        }
        return new UseCaseResult(errorCode: $useCaseError);
    }

    /**
     * @return AdapterOutput
     */
    public function getResultValue(): AdapterOutput
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
     * @throws Exception
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
     * @return Exception|DomainException|null
     */
    public function getException(): null|Exception|DomainException
    {
        return $this->errorCode?->getException();
    }
}
