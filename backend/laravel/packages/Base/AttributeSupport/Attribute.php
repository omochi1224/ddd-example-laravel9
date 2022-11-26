<?php

declare(strict_types=1);

namespace Base\AttributeSupport;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;

/**
 * Attributeの内容を取得する
 */
final class Attribute
{
    /**
     * @param string $attributeClass
     *
     * @return object
     *
     * @throws ReflectionException
     */
    final public function getAttribute(string $attributeClass): object
    {
        $reflection = new ReflectionClass($this::class);
        $constants = $reflection->getConstants();
        $key = array_search($this, $constants, false);
        $constantReflection = new ReflectionClassConstant($this::class, $key);
        $attribute = $constantReflection->getAttributes($attributeClass)[0];
        return $attribute->newInstance();
    }
}
