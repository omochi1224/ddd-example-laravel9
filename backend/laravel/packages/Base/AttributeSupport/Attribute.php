<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

use ReflectionClass;
use ReflectionClassConstant;

/**
 * Attributeの内容を取得する
 */
final class Attribute
{
    final public function getAttribute(string $attributeClass): object
    {
        $reflection = new ReflectionClass(self::class);
        $constants = $reflection->getConstants();
        $key = array_search($this, $constants, false);
        $constantReflection = new ReflectionClassConstant(self::class, $key);
        $attribute = $constantReflection->getAttributes($attributeClass)[0];
        return $attribute->newInstance();
    }
}
