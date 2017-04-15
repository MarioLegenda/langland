<?php

namespace AdminBundle\Entity;

class Theory
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
     * @var int $lessonId
     */
    private $lessonId;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     * @return Theory
     */
    public function setId($id) : Theory
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
     * @return Theory
     */
    public function setName($name) : Theory
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getLessonId()
    {
        return $this->lessonId;
    }
    /**
     * @param mixed $lessonId
     * @return Theory
     */
    public function setLessonId($lessonId) : Theory
    {
        $this->lessonId = $lessonId;

        return $this;
    }
}