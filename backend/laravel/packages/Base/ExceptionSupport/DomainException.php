<?php

declare(strict_types=1);

namespace Base\ExceptionSupport;

use Base\AttributeSupport\HttpStatusCode;
use ReflectionClassConstant;

class DomainException extends \Exception
{
    /**
     * AttributeからHttpStatusを取得する
     *
     *
     * @return mixed
     */
    final public function getHttpStatus(string $attributeKey = 'MESSAGE'): HttpStatusCode
    {
        $attributeClass = HttpStatusCode::class;
        $constantReflection = new ReflectionClassConstant(static::class, $attributeKey);
        $attribute = $constantReflection->getAttributes($attributeClass)[0];
        return $attribute->newInstance();
    }
}
