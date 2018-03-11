<?php

namespace Library\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\QueryBuilder;

class CommonRepository implements RepositoryInterface
{
    /**
     * @var EntityManagerInterface $em
     */
    protected $em;
    /**
     * @var string $class
     */
    private $class;
    /**
     * CommonRepository constructor.
     * @param EntityManagerInterface $em
     * @param string $class
     */
    public function __construct (
        EntityManagerInterface $em,
        string $class
    ) {
        $this->em = $em;
        $this->class = $class;
    }
    /**
     * @param int $id
     * @return object
     */
    public function find(int $id)
    {
        return $this->em->find($this->class, $id);
    }
    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null|int $limit
     * @param null|int $offset
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null)
    {
        $persister = $this->em->getUnitOfWork()->getEntityPersister($this->class);

        return $persister->loadAll($criteria, $orderBy, $limit, $offset);
    }
    /**
     * @inheritdoc
     */
    public function findOneBy(array $criteria, $orderBy = null)
    {
        $persister = $this->em->getUnitOfWork()->getEntityPersister($this->class);

        return $persister->load($criteria, null, null, array(), null, 1, $orderBy);
    }
    /**
     * @param Uuid $uuid
     * @return null|object
     */
    public function findByUuid(Uuid $uuid)
    {
        return $this->findOneBy([
            'uuid' => $uuid->toString(),
        ]);
    }
    /**
     * @void
     * @return array
     */
    public function findAll()
    {
        return $this->findBy(array());
    }
    /**
     * @param string $alias
     * @param null $indexBy
     * @return QueryBuilder
     */
    public function createQueryBuilderFromClass(string $alias, $indexBy = null): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select($alias)
            ->from($this->class, $alias, $indexBy);
    }
    /**
     * @param string $alias
     * @return QueryBuilder
     */
    public function createQueryBuilder(string $alias): QueryBuilder
    {
        return $this->em->createQueryBuilder()->select($alias);
    }
}