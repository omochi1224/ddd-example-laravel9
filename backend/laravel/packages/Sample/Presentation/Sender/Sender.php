<?php

declare(strict_types=1);

namespace Sample\Presentation\Sender;

/**
 *
 */
interface Sender
{
    public function send(object $object): void;
}
