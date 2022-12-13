<?php

declare(strict_types=1);

namespace Auth\Presentation\Resource;

use Auth\Application\UseCases\Register\Adapter\RegisterUserOutput;
use Auth\Domain\Models\User\User;
use Auth\Presentation\Sender\Sender;
use Base\ResourceSupport\Resource;
use Base\UseCaseSupport\UseCaseResult;
use Common\Domain\Models\Email\EmailSender;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property-read RegisterUserOutput $data
 */
final class RegisterUserResource extends Resource
{
    /**
     * @param Sender $sender
     */
    public function __construct(private readonly Sender $sender)
    {
    }

    /**
     * @return array<string, mixed>
     */
    #[ArrayShape(['email' => 'mixed|string'])]
    public function jsonSerialize(): array
    {
        $this->send();
        $user = $this->data->value();
        return [
            'email' => $user->userEmail->value(),
        ];
    }

    /**
     * @return void
     */
    private function send(): void
    {
        $this->sender->send($this->data->value());
    }
}
