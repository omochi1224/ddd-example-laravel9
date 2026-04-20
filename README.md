# DDD Example Laravel

Laravel 12 + PHP 8.5 で実装されたドメイン駆動設計（DDD） / クリーンアーキテクチャのサンプルプロジェクトです。すべてのビジネスロジックはフレームワークに依存しない `packages/` 配下に配置されています。

## アーキテクチャ

```
packages/
├── Base/                          # 再利用可能なDDDプリミティブ
│   ├── DomainSupport/             #   ValueObject, Domain, Resource...
│   ├── ExceptionSupport/          #   例外基底クラス, フレームワーク変換
│   ├── ResourceSupport/           #   JSONリソース基底
│   ├── RoleObjectSupport/         #   ロールオブジェクト マーカーインターフェース
│   ├── TransactionSupport/        #   トランザクション抽象
│   └── ...
│
└── Sample/                        # ビジネスドメイン（4層構成）
    ├── Domain/                    #   ドメイン層（純粋PHP）
    │   ├── Models/                #     エンティティ (User, Profile), 値オブジェクト, ロールオブジェクト
    │   ├── Services/              #     ドメインサービス
    │   └── Tests/                 #     ドメイン単体テスト
    ├── Application/               #   アプリケーション層
    │   └── UseCases/User/         #     ユースケース（Interactor）, Input/Outputポート
    ├── Infrastructure/            #   インフラ層（フレームワーク依存）
    │   ├── Repositories/          #     Eloquent/InMemoryリポジトリ実装
    │   ├── Notification/          #     メール送信
    │   └── EloquentModels/        #     Eloquentモデル
    └── Presentation/              #   プレゼンテーション層
        ├── Controllers/           #     コントローラ（thin, invoke）
        ├── Request/               #     FormRequestアダプタ
        └── Resource/              #     JSONリソース
```

### レイヤー依存ルール（Deptracで強制）

```
Domain        → Base のみ
Application   → Domain, Base（Infrastructure非依存）
Infrastructure→ Domain, Base
Presentation  → Application, Domain, Base
```

## 要件

- Docker & Docker Compose v2
- PHP 8.5
- MySQL 5.7

## セットアップ

```bash
cp .env.example .env
make init
```

## API

### POST `/api/register` — ユーザー仮登録

メールアドレスとパスワードでユーザーを仮登録します。

**Request Body**

| パラメータ  | 型   | 必須 | 説明 |
|------------|------|------|------|
| `email`    | string | Yes | メールアドレス |
| `password` | string | Yes | 8文字以上、大文字・小文字・数字・特殊文字を含む |

**Response Examples**

```json
// 200 - 仮登録成功
{"email": "test@example.com"}

// 422 - バリデーションエラー
{"message": "すでに登録済みのメールアドレスです。"}
{"message": "メールアドレスが正しくありません。"}
{"message": "パスワードの強度が不足しています。"}
```

APIドキュメントは Scribe v5 で生成できます:

```bash
make app  # コンテナに入る
php artisan scribe:generate
```

## 開発コマンド

Dockerコンテナ内で実行:

```bash
make test         # PHPUnit
make app          # コンテナに入る
make migrate      # マイグレーション
make tinker       # Tinker
```

ホストからDocker経由で実行:

```bash
# テスト
docker run --rm -v $(pwd)/backend/laravel:/var/www -w /var/www php:8.5-fpm php vendor/bin/phpunit

# PHPStan (レベルmax)
docker run --rm -v $(pwd)/backend/laravel:/var/www -w /var/www php:8.5-fpm php -d memory_limit=512M vendor/bin/phpstan analyse -c phpstan.neon

# PSR12
docker run --rm -v $(pwd)/backend/laravel:/var/www -w /var/www php:8.5-fpm vendor/bin/phpcs --standard=phpcs.xml ./packages/

# Deptrac (アーキテクチャ検証)
docker run --rm -v $(pwd)/backend/laravel:/var/www -w /var/www php:8.5-fpm vendor/bin/deptrac analyse

# PHP Insights
docker run --rm -v $(pwd)/backend/laravel:/var/www -w /var/www php:8.5-fpm php vendor/bin/phpinsights analyse --no-interaction
```

Composer scripts (`backend/laravel/` 内):

```bash
composer test              # PHPUnit
composer static-type-check # PHPStan
composer sniffer           # PSR12
composer deptrac           # Deptrac
composer insights          # PHP Insights
composer all               # phpcs → phpstan → deptrac → phpunit
```

## 主要な設計パターン

| パターン | 実装 |
|----------|------|
| エンティティ | `final readonly class`, プライベートコンストラクタ + 名前付きコンストラクタ (`User::temporaryRegister()`, `User::restoreFromDb()`) |
| ロールオブジェクト | `RoleObject` マーカーインターフェース。エンティティにロールをコンポジションで付与 (`Administrator`) |
| ユースケース | `final readonly class`, `__invoke()`, `UseCaseResult` を返却 |
| コントローラ | `$resource($useCase($input))` — ワンライナー委譲 |
| ドメイン例外 | `DomainException` を継承, `#[HttpStatusCode(422)]` 属性でHTTPステータスを付与 |
| DI | 環境別ServiceProvider (`Local`, `Test`, `Production`, `Staging`) でリポジトリ実装を切替 |
| テストダブル | `InMemory*Repository` を Infrastructure に配置 |

## テスト

```
packages/Sample/*/Tests/    # ドメイン/Application層の単体テスト
tests/*/Feature/            # HTTP機能テスト
```

```
85 tests, 143 assertions
```

## ライセンス

MIT
