<?php

declare(strict_types=1);

namespace Common\Domain\Models\Email;

use Auth\Domain\Models\User\User;
use Base\FactorySupport\Factory;
use Common\Domain\Models\Email\ValueObject\EmailTemplate;

interface EmailFactory extends Factory
{
    public function makeFromUser(User $user, string $subject, string $text, EmailTemplate $emailTemplate): Email;
}
