<?php

declare(strict_types=1);

namespace Auth\Presentation\Resource;

use Auth\Domain\Models\User\User;
use Base\ResourceSupport\Resource;

/**
 * @property-read User $data
 */
final class RegisterUserResource extends Resource
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'email' => $this->data->userEmail->value(),
        ];
    }
}
