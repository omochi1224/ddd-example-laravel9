<?php

declare(strict_types=1);

namespace Sample\Presentation\Resource;

use Base\ResourceSupport\Resource;

final readonly class RegisterUserResource extends Resource
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $user = $this->data->user;
        return [
            'email' => $user->userEmail->value(),
        ];
    }
}
