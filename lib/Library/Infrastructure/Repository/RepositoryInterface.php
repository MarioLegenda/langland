<?php

namespace Library\Infrastructure\Repository;

use Ramsey\Uuid\Uuid;

interface RepositoryInterface
{
    /**
     * @param int $id
     * @return object
     */
    public function find(int $id);
    /**
     * @param Uuid $uuid
     * @return object
     */
    public function findByUuid(Uuid $uuid);
    /**
     * @param array $criteria
     * @return array
     */
    public function findBy(array $criteria);
    /**
     * @param array $criteria
     * @return object
     */
    public function findOneBy(array $criteria);
}