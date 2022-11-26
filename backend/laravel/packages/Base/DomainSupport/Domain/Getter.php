<?php

declare(strict_types=1);

namespace Base\DomainSupport\Domain;

trait Getter
{
    /**
     * Readonlyプロパティ
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        if (! property_exists($this, $name)) {
            return null;
        }
        return $this->$name;
    }
}
