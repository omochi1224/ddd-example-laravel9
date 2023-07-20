<?php

declare(strict_types=1);

namespace Sample\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    /**
     * CookieAuthenticationController constructor.
     */
    public function __construct(
        private readonly Auth $auth,
    ) {
    }

    /**
     *
     *
     * @throws Exception
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if ($this->getGuard()->attempt($credentials)) {
            $request->session()->regenerate();

            return new JsonResponse(['message' => 'ログインしました']);
        }

        throw new Exception('ログインに失敗しました。再度お試しください');
    }

    public function logout(Request $request): JsonResponse
    {
        $this->getGuard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return new JsonResponse(['message' => 'ログアウトしました']);
    }

    /**
     * @return StatefulGuard|Guard
     */
    private function getGuard(): StatefulGuard|Guard
    {
        return $this->auth->guard(config('auth.defaults.guard'));
    }
}
