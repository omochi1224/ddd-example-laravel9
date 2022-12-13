<?php

namespace Tests;

use Auth\Domain\Models\User\HashService;
use Auth\Domain\Models\User\ValueObject\UserHashPassword;
use Base\DomainSupport\ValueObject\StringValueObject;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}

class ConcreteHash implements HashService {

    /**
     * @param StringValueObject $raw
     *
     * @return UserHashPassword
     */
    public function hashing(StringValueObject $raw): UserHashPassword
    {
        return UserHashPassword::of(hash('sha256', $raw->value()));
    }
}
