<?php

namespace LearningSystem\Infrastructure\Sort\Type;

use LearningSystem\Infrastructure\ParameterBagInterface;

trait ParameterBagSort
{
    /**
     * @param string $key
     * @param iterable $values
     * @return mixed
     */
    public function sortParameterBag(string $key, iterable $values)
    {
        $order = [];
        $sorted = [];

        /** @var ParameterBagInterface $value */
        foreach ($values as $value) {
            $order[] = $value->get($key)['parameter'];
        }

        asort($order);

        foreach ($order as $i => $orderKey) {
            foreach ($values as $value) {
                if ($value->get($key)['parameter'] === $orderKey) {
                    $sorted[] = $value;

                    break;
                }
            }
        }

        return $sorted;
    }
}