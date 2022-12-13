<?php

declare(strict_types=1);

namespace Base\ResourceSupport;

use Base\DomainSupport\Domain\Domain;
use Base\ExceptionSupport\ToFrameworkException;
use Base\UseCaseSupport\UseCaseResult;
use Exception;
use JsonSerializable;

/**
 * 共通 Resource
 *
 * @property Domain|object|array|null $data
 */
abstract class Resource implements JsonSerializable
{
    /**
     * @param UseCaseResult $useCaseResult
     *
     * @return Resource
     * @throws ToFrameworkException
     */
    public function __invoke(UseCaseResult $useCaseResult): static
    {
        $useCaseResult1 = $useCaseResult;
        if ($useCaseResult1->isError()) {
            throw new ToFrameworkException(
                $useCaseResult1->getErrorMessage(),
                $useCaseResult1->getHttpStatus()
            );
        }
        $this->data = $useCaseResult1->getResultValue();
        return $this;
    }

    /**
     * @return array
     */
    abstract public function jsonSerialize(): array;
}
