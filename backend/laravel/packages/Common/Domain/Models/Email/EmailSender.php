<?php

declare(strict_types=1);

namespace Common\Domain\Models\Email;

/**
 *
 */
interface EmailSender
{
    /**
     * @param Email $email
     *
     * @return void
     */
    public function send(Email $email): void;
}
