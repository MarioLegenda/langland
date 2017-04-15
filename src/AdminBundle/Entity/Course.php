<?php

namespace API\SharedDataBundle\Entity;

namespace AdminBundle\Entity;

class Course
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $language
     */
    private $language;
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    /**
     * @param $request
     * @return Course
     */
    public static function createFromRequest(ParameterBag $request) : Course
    {
        $course = new Course();
        $course->setName($request->get('name'));
        $course->setLanguage($request->get('language'));

        return $course;
    }
}