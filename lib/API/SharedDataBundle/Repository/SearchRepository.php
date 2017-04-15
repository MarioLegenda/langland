<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\BlueDotInterface;

class SearchRepository extends AbstractRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * SearchRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $blueDot->useApi('words');

        $this->blueDot = $blueDot;
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function searchWords(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('callable.search_callable', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findLastWords(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('callable.last_words', $data);

        return $this->createResultResolver($promise);
    }
}