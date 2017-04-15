<?php

namespace AdminBundle\Entity;

use BlueDot\Entity\Entity;
use Symfony\Component\HttpFoundation\ParameterBag;

class Lesson
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
     * @var int $class
     */
    private $class;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     * @return Lesson
     */
    public function setId($id) : Lesson
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
     * @return Lesson
     */
    public function setName($name) : Lesson
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }
    /**
     * @param mixed $class
     * @return Lesson
     */
    public function setClass($class) : Lesson
    {
        $this->class = $class;

        return $this;
    }
    /**
     * @param ParameterBag $request
     * @return Lesson
     */
    public static function createFromRequest(ParameterBag $request)
    {
        $lesson = new Lesson();

        if ($request->has('lesson_id')) {
            $lesson->setId($request->get('lesson_id'));
        }

        $lesson->setName($request->get('name'));
        $lesson->setClass($request->get('class'));

        return $lesson;
    }
    /**
     * @param Entity $entity
     * @return Lesson
     */
    public static function createFromEntity(Entity $entity)
    {
        $entity = $entity[0];
        $lesson = new Lesson();

        return $lesson
            ->setId($entity['id'])
            ->setName($entity['name'])
            ->setClass('class_id');
    }
}