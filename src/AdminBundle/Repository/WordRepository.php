<?php

namespace AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class WordRepository extends EntityRepository
{
    /**
     * @param string $pattern
     * @return array
     */
    public function findWordsByPattern(string $pattern)
    {
        $result = $this
            ->createQueryBuilder('w')
            ->where('w.name = :name')
            ->setParameter('name', $pattern)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

        if (empty($result)) {
            $result = $this
                ->createQueryBuilder('w')
                ->where('w.name LIKE :pattern')
                ->setParameter('pattern', sprintf('%s%s%s', '%', $pattern, '%'))
                ->getQuery()
                ->getResult(Query::HYDRATE_ARRAY);
        }

        if (empty($result)) {
            return array();
        }

        $word = array();

        foreach ($result as $res) {
            $temp['id'] = $res['id'];
            $temp['name'] = $res['name'];

            $word[] = $temp;
        }

        return $word;
    }
    /**
     * @param array $ids
     * @return array
     */
    public function findMultipleById(array $ids)
    {
        $result = $this
            ->createQueryBuilder('w')
            ->where('w.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        return $result;
    }
}