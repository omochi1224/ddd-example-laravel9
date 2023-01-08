<?php

declare(strict_types=1);

namespace Base\LaravelProviderSupport;

/**
 *
 */
interface Provider
{
    /**
     * @return BaseProvider
     */
    public function provider(): BaseProvider;

}
