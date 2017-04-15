<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\BlueDotInterface;

class CourseRepository extends AbstractRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * CourseRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $blueDot->useApi('course');

        $this->blueDot = $blueDot;
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function create(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('scenario.create_course', array(
            'create_course' => $data
        ));

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findCourseByName(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_course_by_name', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findAllByLanguage(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_all_courses_by_language', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function getInitialCourseInfo(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.initial_course_info', $data);

        return $this->createResultResolver($promise);
    }
}