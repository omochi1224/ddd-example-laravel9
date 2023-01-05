<?php

declare(strict_types=1);

namespace Sample\Domain\Models\User\ValueObject;

use Base\AttributeSupport\Text;
use Base\DomainSupport\ValueObject\ValueObject;
use ReflectionEnum;

/**
 *
 */
enum UserStatus: int implements ValueObject
{
    #[Text('仮登録')]
    case Temporary = 0;

    #[Text('本登録')]
    case Definitive = 100;

    #[Text('禁止')]
    case Ban = 400;

    #[Text('退会')]
    case Unsubscribe = 900;

    /**
     * @param UserStatus $valueObject
     *
     * @return bool
     */
    public function equals(ValueObject $valueObject): bool
    {
        return $this->value === $valueObject->value;
    }

    /**
     * @return int
     */
    public function value(): int
    {
        return $this->value;
    }

    /**
     * @return string
     *
     * @throws \ReflectionException
     */
    public function description(): string
    {
        $reflection = new ReflectionEnum($this);
        return $reflection->getCase($this->name)
            ->getAttributes()[0]
            ->newInstance()
            ->value();
    }

    /**
     * @param string $value
     *
     * @return UserStatus
     *
     * @throws \Exception
     */
    public static function of(string $value): UserStatus
    {
        return match ($value) {
            'Temporary' => UserStatus::Temporary,
            'Definitive' => UserStatus::Definitive,
            'Unsubscribe' => UserStatus::Unsubscribe,
            'Ban' => UserStatus::Ban,
            default => throw new \Exception('Unexpected match value')
        };
    }
}
