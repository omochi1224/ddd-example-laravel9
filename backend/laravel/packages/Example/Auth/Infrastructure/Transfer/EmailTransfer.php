<?php

declare(strict_types=1);

namespace Auth\Infrastructure\Transfer;

use Auth\Domain\Models\User\User;
use Auth\Presentation\Sender\Sender;
use Illuminate\Support\Facades\Mail;

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
