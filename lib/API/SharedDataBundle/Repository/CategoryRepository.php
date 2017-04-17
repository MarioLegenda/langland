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
    public function create(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('scenario.create_category', array(
            'create_category' => $data,
        ));

        return $this->createResultResolver($promise);
    }
    /**
     * @return ResultResolver
     */
    public function findAllForWorkingLanguage() : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_all_in_working_language');

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findByNameForWorkingLanguage(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_by_name_in_working_language', $data);

        return $this->createResultResolver($promise);
    }

    /**
     * @param array $data
     * @return ResultResolver
     */
    public function updateCategoryName(array $data)
    {
        $promise = $this->blueDot->execute('simple.update.update_category_name', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findCategoryById(array $data)
    {
        $promise = $this->blueDot->execute('simple.select.find_category_by_id', $data);

        return $this->createResultResolver($promise);
    }
}