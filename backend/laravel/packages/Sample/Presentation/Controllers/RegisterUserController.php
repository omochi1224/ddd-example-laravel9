<?php

declare(strict_types=1);

namespace Sample\Presentation\Controllers;

use Base\ExceptionSupport\ToFrameworkException;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Sample\Application\UseCases\User\Adapter\TemporaryRegisterUserInput;
use Sample\Application\UseCases\User\TemporaryRegisterUserUseCase;
use Sample\Presentation\Resource\RegisterUserResource;

#[Group('ユーザー登録')]
final readonly class RegisterUserController
{
    #[Endpoint('ユーザー仮登録', 'メールアドレスとパスワードでユーザーを仮登録します。')]
    #[BodyParam('email', description: 'メールアドレス', example: 'test@example.com', required: true)]
    #[BodyParam('password', description: '8文字以上、大小・数字・特殊文字を含む', example: 'Password!1234@pAssword', required: true)]
    #[Response('{"email": "test@example.com"}', 200, '仮登録成功')]
    #[Response('{"message": "すでに登録済みのメールアドレスです。"}', 422, 'メールアドレス重複')]
    #[Response('{"message": "メールアドレスが正しくありません。"}', 422, '不正なメールアドレス')]
    #[Response('{"message": "パスワードの強度が不足しています。"}', 422, 'パスワードポリシー違反')]
    #[Response('Method Not Allowed', 405, 'HTTPメソッド不正')]
    public function __invoke(
        TemporaryRegisterUserInput $input,
        TemporaryRegisterUserUseCase $useCase,
        RegisterUserResource $resource
    ): RegisterUserResource {
        return $resource($useCase($input));
    }
}
