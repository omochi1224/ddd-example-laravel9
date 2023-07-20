<?php

declare(strict_types=1);

namespace Base\UseCaseSupport;

use Base\ExceptionSupport\DomainException;
use Exception;

/**
 * エラ-ラップ
 */
final class ErrorCode
{
    private function __construct(private readonly \Exception $exception)
    {
    }

    /**
     * @param DomainException|Exception $exception
     */
    public static function of(DomainException|Exception $exception): self
    {
        return new ErrorCode($exception);
    }

    /**
     * @throws \Exception
     */
    public function getMessage(): ?string
    {
        try {
            return $this->exception::MESSAGE;
        } catch (Exception) {
            throw new \Exception('メッセージ定数が設定されていません。');
        }
    }

    public function getException(): Exception
    {
        return $this->exception;
    }
}
