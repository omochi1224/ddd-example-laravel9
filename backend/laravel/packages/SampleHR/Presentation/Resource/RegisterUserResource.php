<?php

declare(strict_types=1);

namespace SampleHR\Presentation\Resource;

use Base\ResourceSupport\Resource;
use JetBrains\PhpStorm\ArrayShape;
use SampleHR\Application\UseCases\Register\Adapter\RegisterUserOutput;
use SampleHR\Domain\Models\Notification\NotificationSender;

/**
 * @property-read RegisterUserOutput $data
 */
readonly final class RegisterUserResource extends Resource
{
    /**
     * @param NotificationSender $sender
     */
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
