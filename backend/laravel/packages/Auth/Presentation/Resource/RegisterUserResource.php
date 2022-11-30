<?php

declare(strict_types=1);

namespace Auth\Presentation\Resource;

use Auth\Adapter\Http\RegisterUserOutput;
use Base\ResourceSupport\Resource;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property-read RegisterUserOutput $data
 */
final class RegisterUserResource extends Resource
{
    /**
     * @return array<string, mixed>
     */
    #[ArrayShape(['email' => 'mixed|string'])]
    public function jsonSerialize(): array
    {
        return [
            'email' => $this->data->value()['email'],
        ];
    }
}
