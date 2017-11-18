<?php

namespace Library\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

class CommonRepository implements RepositoryInterface
{
    /**
     * @var EntityManagerInterface $em
     */
    protected $em;
    /**
     * @var EntityRepository $repository
     */
    protected $repository;
    /**
     * @param EntityManagerInterface $em
     * @param string $entity
     */
    public function build(
        EntityManagerInterface $em,
        string $entity
    ) {
        $this->repository = $em->getRepository($entity);
        $this->em = $em;
    }
    /**
     * @inheritdoc
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }
    /**
     * @inheritdoc
     */
    public function findBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }
    /**
     * @inheritdoc
     */
    public function findOneBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
    /**
     * @inheritdoc
     */
    public function findByUuid(Uuid $uuid)
    {
        return $this->repository->findOneBy([
            'uuid' => $uuid->toString(),
        ]);
    }
}