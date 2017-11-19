<?php

namespace Library\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;

class RepositoryFactory
{
    /**
     * @param EntityManagerInterface $em
     * @param string $entity
     * @param string $repository
     * @return RepositoryInterface
     */
    public static function getRepository
    (
        EntityManagerInterface $em,
        string $entity,
        string $repository
    ): RepositoryInterface {
        if (!class_exists($repository)) {
            $message = sprintf('Repository class \'%s\' does not exist', $repository);
            throw new \InvalidArgumentException($message);
        }

        if (!class_exists($entity)) {
            $message = sprintf('Entity class \'%s\' does not exist', $entity);
            throw new \InvalidArgumentException($message);
        }

        return new $repository($em, $entity);
    }
}