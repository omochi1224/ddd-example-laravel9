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
     * 永続化からの復帰
     *
     * @param object $ormObject
     *
     * @return Domain
     */
    public static function makeFromRecord(object $ormObject): Domain;
}
