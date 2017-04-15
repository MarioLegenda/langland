<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\ParameterBag;

class Category
{
    private $id;
    /**
     * @var string $category
     */
    private $category;
    /**
     * @var int $languageId
     */
    private $languageId;
    /**
     * @var $language
     */
    private $language;
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
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
    /**
     * @return mixed
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }
    /**
     * @param mixed $languageId
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;
    }
    /**
     * @param ParameterBag $request
     * @return Category
     */
    public static function createFromRequest(ParameterBag $request) : Category
    {
        $category = new Category();
        $category->setCategory($request->get('category'));
        $category->setLanguageId($request->get('language'));
        $category->setId($request->get('category_id'));

        return $category;
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
}