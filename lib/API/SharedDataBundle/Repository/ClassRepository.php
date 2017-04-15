<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\BlueDotInterface;

class ClassRepository extends AbstractRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * ClassRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $blueDot->useApi('class');

        $this->blueDot = $blueDot;
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function create(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.insert.create_class', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function update(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.update.rename_class', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findClassesByCourse(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_classes_by_course', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findClassByName(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_class_by_name', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findClassById(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_class_by_id', $data);

        return $this->createResultResolver($promise);
    }
}