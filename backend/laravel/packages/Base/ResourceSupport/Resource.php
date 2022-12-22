<?php

declare(strict_types=1);

namespace Base\ResourceSupport;

use Base\ExceptionSupport\ToFrameworkException;
use Base\UseCaseSupport\UseCaseResult;
use JsonSerializable;

/**
 * 共通 Resource
 */
readonly abstract class Resource implements JsonSerializable
{
    /**
     * @var mixed
     */
    protected mixed $data;

    /**
     * @param UseCaseResult $useCaseResult
     *
     * @return Resource
     * @throws ToFrameworkException
     */
    public function __invoke(UseCaseResult $useCaseResult): static
    {
        if ($useCaseResult->isError()) {
            throw new ToFrameworkException(
                $useCaseResult->getErrorMessage(),
                $useCaseResult->getHttpStatus()
            );
        }
        $this->data = $useCaseResult->getResultValue();
        return $this;
    }

    /**
     * @return array
     */
    abstract public function jsonSerialize(): array;
}
