<?php

declare(strict_types=1);

namespace Tests\Common\Feature\Controller;

use Sample\Domain\Models\User\User;
use Sample\Domain\Models\User\UserRepository;
use Sample\Domain\Models\User\ValueObject\UserEmail;
use Sample\Infrastructure\Repositories\InMemory\InMemoryUserRepository;
use Tests\TestCase;

final class RegisterApiTest extends TestCase
{
    private function validParams(): array
    {
        return [
            'email' => 'test@example.com',
            'password' => 'Password!1234@pAssword',
        ];
    }

    // ─── 正常系 ───

    public function test_正常系_仮登録が成功する()
    {
        $response = $this->postJson('/api/register', $this->validParams());

        $response->assertStatus(200);
        $response->assertExactJson([
            'email' => 'test@example.com',
        ]);
    }

    public function test_正常系_同じメールアドレスでも異なるパスワードで登録できる()
    {
        $this->postJson('/api/register', $this->validParams())->assertStatus(200);

        $response = $this->postJson('/api/register', [
            'email' => 'different@example.com',
            'password' => 'DifferentPass!5678@wOrd',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('email', 'different@example.com');
    }

    // ─── 異常系: バリデーションエラー ───

    public function test_異常系_メールアドレスが未入力の場合は422()
    {
        $response = $this->postJson('/api/register', [
            'password' => 'Password!1234@pAssword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_異常系_パスワードが未入力の場合は422()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_異常系_メールアドレスとパスワード両方未入力の場合は422()
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_異常系_空のリクエストボディの場合は422()
    {
        $response = $this->postJson('/api/register');

        $response->assertStatus(422);
    }

    // ─── 異常系: ドメインバリデーションエラー ───

    public function test_異常系_不正なメールアドレス形式の場合は422()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'invalid-email',
            'password' => 'Password!1234@pAssword',
        ]);

        $response->assertStatus(422);
    }

    public function test_異常系_パスワードが短すぎる場合は422()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
            'password' => 'Ab1!',
        ]);

        $response->assertStatus(422);
    }

    public function test_異常系_パスワードに大文字が含まれない場合は422()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
            'password' => 'password!1234',
        ]);

        $response->assertStatus(422);
    }

    public function test_異常系_パスワードに小文字が含まれない場合は422()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
            'password' => 'PASSWORD!1234',
        ]);

        $response->assertStatus(422);
    }

    public function test_異常系_パスワードに数字が含まれない場合は422()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
            'password' => 'Password!abcd',
        ]);

        $response->assertStatus(422);
    }

    public function test_異常系_パスワードに特殊文字が含まれない場合は422()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'test@example.com',
            'password' => 'Password1234',
        ]);

        $response->assertStatus(422);
    }

    public function test_異常系_すでに登録済みのメールアドレスの場合は422()
    {
        $email = UserEmail::of('test@example.com');
        $user = User::socialTemporaryRegister($email);
        $dummyRepository = new InMemoryUserRepository();
        $dummyRepository->create($user);
        $this->app->instance(UserRepository::class, $dummyRepository);

        $response = $this->postJson('/api/register', $this->validParams());

        $response->assertStatus(422);
    }

    // ─── 異常系: HTTPメソッド ───

    public function test_異常系_GETリクエストは405()
    {
        $response = $this->getJson('/api/register');

        $response->assertStatus(405);
    }

    // ─── 異常系: レスポンス形式 ───

    public function test_正常系_レスポンスがJSON形式である()
    {
        $response = $this->postJson('/api/register', $this->validParams());

        $response->assertStatus(200);
        $this->assertStringContainsString(
            'application/json',
            $response->headers->get('Content-Type')
        );
    }
}
