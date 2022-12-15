<?php

declare(strict_types=1);

namespace SampleHR\Infrastructure\Transfer;

use SampleHR\Domain\Models\User\User;
use SampleHR\Presentation\Sender\Sender;

final class EmailTransfer implements Sender
{
    /**
     * @param object $object
     *
     * @return void
     */
    public function send(object $object): void
    {
        /** @var User $user */
        $user = $object;
//        dump($user);
//        Mail::to($user->userEmail->value())->send();
    }
}
