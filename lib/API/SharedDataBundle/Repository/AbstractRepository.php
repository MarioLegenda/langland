<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\Entity\Entity;
use BlueDot\Entity\PromiseInterface;

abstract class AbstractRepository
{
    /**
     * @param PromiseInterface $promise
     * @return ResultResolver
     */
    protected function createResultResolver(PromiseInterface $promise) : ResultResolver
    {
        if ($promise->isSuccess()) {
            $result = $promise->getResult();

            if (is_array($result)) {
                if (empty($result)) {
                    return new ResultResolver(array());
                }

                return new ResultResolver($result);
            }

            if ($result instanceof Entity) {
                return new ResultResolver($result->normalizeIfOneExists()->toArray());
            }
        }

        return new ResultResolver(array());
    }
}