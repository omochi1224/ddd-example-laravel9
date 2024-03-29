<?php

declare(strict_types=1);

namespace App\lib;

use Base\LoggerSupport\Logger;
use Psr\Log\LoggerInterface;

final class LaravelLogger implements Logger
{
    /**
     * LaravelLogger constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert(string $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice(string $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }
}
