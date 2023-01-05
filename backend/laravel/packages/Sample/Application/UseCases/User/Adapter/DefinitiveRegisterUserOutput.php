<?php

declare(strict_types=1);

namespace Sample\Application\UseCases\User\Adapter;

use Base\AdapterSupport\AdapterOutput;
use Sample\Domain\Models\User\IUser;

final readonly class DefinitiveRegisterUserOutput implements AdapterOutput
{
    /**
     * @codingStandardsIgnoreFile
     *
     * @param IUser $user
     */
    public function __construct(
        public IUser $user
    ) {
    }
}
