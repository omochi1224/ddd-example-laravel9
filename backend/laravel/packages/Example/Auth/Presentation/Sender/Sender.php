<?php

declare(strict_types=1);

namespace Auth\Presentation\Sender;

/**
 *
 */
interface Sender
{
    /**
     * @param object $object
     *
     * @return void
     */
    public function send(object $object): void;
}
