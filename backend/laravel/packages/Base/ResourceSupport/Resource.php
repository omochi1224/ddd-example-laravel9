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
     * @throws ToFrameworkException
     * @throws Exception
     */
    public function __construct(protected readonly UseCaseResult $useCaseResult)
    {
        if ($this->useCaseResult->isError()) {
            throw new ToFrameworkException(
                $this->useCaseResult->getErrorMessage(),
                $this->useCaseResult->getHttpStatus()
            );
        }
        $this->data = $this->useCaseResult->getResultValue();
    }

    /**
     * @return array
     */
    abstract public function jsonSerialize(): array;

    /**
     * @param array $array
     *
     * @return array
     */
    protected function toStringId(array $array): array
    {
        return array_map(function (object $object) {
            return $object->value();
        }, $array);
    }
}
