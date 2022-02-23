<?php

declare(strict_types=1);

namespace Base\DomainSupport\Domain;

/**
 * ドメイン根底抽象クラス
 */
abstract class Domain implements IDomain
{
    /**
     * Entity同士の比較
     *
     * @param Domain $domain
     *
     * @return bool
     */
    abstract public function equals(Domain $domain): bool;
}
