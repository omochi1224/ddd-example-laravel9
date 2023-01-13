<?php

declare(strict_types=1);

namespace Todo\Application\UseCases\TemporarySNSRegister;

interface TemporarySNSRegisterInputAdapter
{
    public function getEmail(): string;
}
