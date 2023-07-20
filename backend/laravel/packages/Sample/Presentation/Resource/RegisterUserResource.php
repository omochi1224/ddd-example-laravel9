<?php

declare(strict_types=1);

namespace Sample\Presentation\Resource;

use Base\ResourceSupport\Resource;
use JetBrains\PhpStorm\ArrayShape;
use Sample\Domain\Models\Notification\NotificationSender;

readonly final class RegisterUserResource extends Resource
{
    public function __construct(
        private NotificationSender $sender
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'email' => 'string',
    ])]
    public function jsonSerialize(): array
    {
        $this->sender->send($this->data->notify);
        $user = $this->data->user;
        return [
            'email' => $user->userEmail->value(),
        ];
    }
}
