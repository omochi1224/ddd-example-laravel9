<?php

declare(strict_types=1);

namespace Auth\Presentation\Resource;

use Auth\Presentation\Sender\Sender;
use Base\ResourceSupport\Resource;
use JetBrains\PhpStorm\ArrayShape;

readonly final class RegisterUserResource extends Resource
{
    /**
     * @param Sender $sender
     */
    public function __construct(private Sender $sender)
    {
    }

    /**
     * @return array<string, mixed>
     */
    #[ArrayShape([
        'email' => 'string'
    ])]
    public function jsonSerialize(): array
    {
        $this->send();
        $user = $this->data;
        return [
            'email' => $user->userEmail->value(),
        ];
    }

    /**
     * @return void
     */
    private function send(): void
    {
        $this->sender->send($this->data);
    }

}
