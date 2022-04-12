<?php

declare(strict_types=1);

namespace Auth\Feature\Controller;


use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class RegisterUserControllerTest extends TestCase
{
    use WithFaker;

    public function test_正常()
    {
        $email = $this->faker->safeEmail;
        $password = 'U4s_qtL,';

        $data = [
            'email' => $email,
            'password' => $password,
        ];

        $response = $this->call('POST', route('register'), $data)
            ->assertOk();
        $response->assertExactJson(['email' => $email]);
    }

}
