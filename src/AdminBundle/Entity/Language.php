<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\ParameterBag;

class Language
{
    private $id;
    /**
     * @var string $language
     */
    private $language;
    /**
     * @var int $workingLanguage
     */
    private $workingLanguage = 0;
    /**
     * @var Category $category
     */
    private $category;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @param string $language
     * @return Language
     */
    public function setLanguage($language) : Language
    {
        $this->language = $language;

        return $this;
    }
    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * @return int
     */
    public function getWorkingLanguage()
    {
        return $this->workingLanguage;
    }
    /**
     * @param int $workingLanguage
     * @return Language
     */
    public function setWorkingLanguage($workingLanguage) : Language
    {
        $this->workingLanguage = $workingLanguage;

        return $this;
    }
    /**
     * @param $request
     * @return Language
     */
    public static function createFromRequest(ParameterBag $request) : Language
    {
        $language = new Language();
        $language->setLanguage($request->get('language'));

        if ($request->has('language_id')) {
            $language->setId($request->get('language_id'));
        }

        if ($request->has('working_language')) {
            $language->setWorkingLanguage($request->get('working_language'));
        }

        return $language;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }
}