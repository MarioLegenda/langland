<?php

namespace API\SharedDataBundle\Repository;

use BlueDot\BlueDotInterface;
use BlueDot\Entity\PromiseInterface;

class LessonRepository extends AbstractRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * LessonRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $blueDot->useApi('lesson');

        $this->blueDot = $blueDot;
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function create(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.insert.create_lesson', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function renameLesson(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.update.rename_lesson', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param $classId
     * @return PromiseInterface
     */
    public function findAllByClass($classId)
    {
        return $this->blueDot->execute('simple.select.find_all_lessons_by_class', array(
            'class_id' => $classId,
        ));
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findLessonById(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_lesson_by_id', $data);

        return $this->createResultResolver($promise);
    }
    /**
     * @param array $data
     * @return ResultResolver
     */
    public function findLessonByClass(array $data) : ResultResolver
    {
        $promise = $this->blueDot->execute('simple.select.find_lesson_by_class', $data);

        return $this->createResultResolver($promise);
    }
/*
    public function findLessonComplete(Lesson $lesson)
    {
        return $this->blueDot->execute('simple.select.find_lesson_complete', array(
            'class_id' => $lesson->getClass(),
            'name' => $lesson->getName(),
            'lesson_id' => $lesson->getId(),
        ))->getResult();
    }*/
}