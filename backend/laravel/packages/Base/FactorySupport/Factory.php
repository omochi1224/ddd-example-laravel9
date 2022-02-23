<?php

declare(strict_types=1);

namespace Base\FactorySupport;

use Base\DomainSupport\Domain\Domain;

/**
 * Factory根底抽象クラス
 */
interface Factory
{
    /**
     * @param object $ormObject
     *
     * @return Domain
     */
    public function makeFromRecord(object $ormObject): Domain;
}
