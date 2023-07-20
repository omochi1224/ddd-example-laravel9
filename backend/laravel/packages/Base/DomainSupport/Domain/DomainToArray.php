<?php

declare(strict_types=1);

namespace Base\DomainSupport\Domain;

use ReflectionClass;

/**
 * Trait DomainToArray
 *
 * @package Basic\DomainSupport\Domain
 */
trait DomainToArray
{
    /**
     * ドメインを配列['propertyName'=>value]に変換します
     *
     * @param array<string> $hiddenOption
     * @return array<string, mixed>
     */
    public function domainToArray(Domain $domain, array $hiddenOption = []): array
    {
        return $this->filterArray(
            call_user_func_array(
                'array_merge',
                array_map(
                    function ($property) use ($domain) {
                        $array = [];
                        $propertyName = $property->name;
                        if ($domain->$propertyName?->value() instanceof Domain) {
                            $array[$this->underscore($propertyName)] = $this->domainToArray(
                                $domain->$propertyName?->value()
                            );
                        } elseif ($domain->$propertyName?->value() instanceof \ArrayIterator) {
                            $iteratorArray = iterator_to_array($domain->$propertyName?->value());
                            foreach ($iteratorArray as $iterator) {
                                $array[$this->underscore($propertyName)][] = $this->domainToArray($iterator);
                            }
                        } else {
                            $array[$this->underscore($propertyName)] = $domain->$propertyName?->value();
                        }
                        return $array;
                    },
                    (new ReflectionClass($domain))->getProperties()
                )
            ),
            $hiddenOption
        );
    }

    /**
     * @param array<string> $hiddenOption
     * @return array<string, mixed>
     */
    protected function filterArray(array $array, array $hiddenOption = []): array
    {
        return $hiddenOption !== [] ? array_filter(
            $array,
            static function ($value, string $key) use ($hiddenOption) {
                foreach ($hiddenOption as $option) {
                    return $option !== $key;
                }
            },
            ARRAY_FILTER_USE_BOTH
        ) : $array;
    }

    /**
     * キャメルケースをスネークケースに変換します。
     *
     *
     * @return string
     */
    private function underscore(string $str)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }
}
