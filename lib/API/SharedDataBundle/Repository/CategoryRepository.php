<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\BlueDotInterface;

class CategoryRepository extends AbstractRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * CategoryRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $blueDot->useApi('category');

        $this->blueDot = $blueDot;
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findCategory(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_category', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findAll(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_all_categories', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function create(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.insert.create_category', $data);

        return $this->createResultResolver($promise);
    }
}