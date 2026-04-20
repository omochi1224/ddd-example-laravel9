# AGENTS.md

## Commands

All commands run from `backend/laravel/`. Use Docker for PHP 8.5:
```bash
docker run --rm -v $(pwd)/backend/laravel:/var/www -w /var/www php:8.5-fpm php vendor/bin/phpunit
docker run --rm -v $(pwd)/backend/laravel:/var/www -w /var/www php:8.5-fpm php -d memory_limit=512M vendor/bin/phpstan analyse -c phpstan.neon
docker run --rm -v $(pwd)/backend/laravel:/var/www -w /var/www composer:latest [cmd]
```

Composer scripts (inside `backend/laravel/`):
- `composer test` — PHPUnit
- `composer static-type-check` — PHPStan (needs 512M+ memory)
- `composer sniffer` — PHP_CodeSniffer PSR12 on packages/
- `composer deptrac` — Architecture layer enforcement
- `composer insights` — PHP Insights code quality analysis
- `composer all` — phpcs → phpstan → deptrac → phpunit

## Architecture

DDD / Clean Architecture. All business logic in `packages/`, framework-agnostic.

### Package Structure
- `packages/Base/` — Reusable DDD primitives (ValueObject, Domain, Resource, Transaction, etc.). Excluded from PHPStan/Deptrac/phpinsights scanning.
- `packages/Sample/` — Business domain with 4 layers:
  - `Domain/` → Pure PHP, zero framework deps. Entities, ValueObjects, Domain Services, Repositories (interfaces), Role Objects
  - `Application/` → Use Cases (interactors). Input/Output ports in `Adapter/`
  - `Infrastructure/` → Framework-specific: Eloquent repositories, InMemory repositories, HashService, NotificationSender
  - `Presentation/` → HTTP: Controllers (invokable, thin), FormRequest adapters, Resources

### Layer Dependency Rules (enforced by Deptrac via `layer.yaml`)
- Domain → Base only
- Application → Domain, Base (Infrastructure dependency removed — Use Cases are framework-agnostic)
- Infrastructure → Domain, Base
- Presentation → Application, Domain, Base

### DI / Environment Binding
`AppServiceProvider` delegates to env-specific providers in `app/Providers/ServiceProvider/`:
- **Local**: Eloquent repos, DummyNotificationSender
- **Test**: InMemory repos, DummyNotificationSender
- **Production**: InMemory repos (placeholder)
- **Staging**: Eloquent repos

### Layer Dependency Rules (enforced by Deptrac via `layer.yaml`)
- Entities: `final readonly class`, private constructors with static named constructors (`User::temporaryRegister()`, `User::restoreFromDb()`), `Getter` trait for magic property access
- Role Objects: `RoleObject` marker interface in `Base/RoleObjectSupport/`. Entities can express roles (e.g., `Administrator`) via composition.
- Use Cases: `final readonly class`, `__invoke()`, return `UseCaseResult`
- Controllers: `$resource($useCase($input))` — one-liner delegation
- Domain Exceptions: extend `DomainException`, use `#[HttpStatusCode(422)]` attribute on `MESSAGE` constant
- Test doubles: `InMemory*Repository` classes in `Infrastructure/Repositories/InMemory/`
- API Documentation: Scribe v5 attributes on controllers (`#[Endpoint]`, `#[BodyParam]`, `#[Response]`)

## Key Files

- `bootstrap/app.php` — Laravel 12 slim bootstrap (middleware, exceptions, routing configured here)
- `bootstrap/providers.php` — Service provider registration
- `deptrac.yaml` → points to `packages/Sample/layer.yaml`
- `phpstan.neon` — Level max, scans `packages/` only, excludes `Base/` and `Tests/`
- `phpcs.xml` — PSR12 with test directory exclusions (Japanese method names, helper classes)
- `config/insights.php` — PHP Insights config, scans `packages/Sample/` only

## Gotchas

- PHPStan needs `-d memory_limit=512M` (level max + Larastan is memory-hungry)
- Deptrac uses `qossmic/deptrac-shim` v1.0.2 (PHP 8.5 deprecated warnings from bundled Symfony, but functional)
- `ProfileBirthDay` validates birthday must be strictly before now — tests must use past dates like `(new \DateTime())->modify('-1 year')`
- PHPUnit tests live inside `packages/Sample/*/Tests/` (Unit suite) AND `tests/*/Feature/` (Feature suite)
- Some domain tests extend `Tests\TestCase` (Laravel bootstrap) while others extend `PHPUnit\Framework\TestCase` directly (pure unit)
- PHPUnit Feature suite path is `tests/*/Feature/` — tests directly in `tests/Feature/` won't be picked up
- Test helper classes (e.g., `ConcreteHash`) in test files are accepted — phpcs excludes Tests from "one class per file" rule
- Japanese test method names are intentional — phpcs excludes Tests from camelCase check
- `Getter` trait returns `null` for undefined properties (known tradeoff, existing code relies on this)
