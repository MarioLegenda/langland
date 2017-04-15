<?php

namespace AdminBundle\Entity;

use BlueDot\Entity\Entity;
use Symfony\Component\HttpFoundation\ParameterBag;

class CourseClass
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var int $course
     */
    private $course;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @param int $id
     * @return CourseClass
     */
    public function setId(int $id) : CourseClass
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     * @return CourseClass
     */
    public function setName($name) : CourseClass
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCourse()
    {
        return $this->course;
    }
    /**
     * @param mixed $course
     * @return CourseClass
     */
    public function setCourse($course) : CourseClass
    {
        $this->course = $course;

        return $this;
    }
    /**
     * @param ParameterBag $request
     * @return CourseClass
     */
    public static function createFromRequest(ParameterBag $request) : CourseClass
    {
        $class = new CourseClass();

        if ($request->has('class_id')) {
            $class->setId($request->get('class_id'));
        }

        $class->setName($request->get('name'));
        $class->setCourse($request->get('course_id'));

        return $class;
    }
    /**
     * @param Entity $entity
     * @return CourseClass
     */
    public static function createFromEntity(Entity $entity) : CourseClass
    {
        $class = new CourseClass();
        $entity = $entity[0];

        return $class
            ->setName($entity['name'])
            ->setId($entity['id'])
            ->setCourse($entity['course_id']);
    }
}