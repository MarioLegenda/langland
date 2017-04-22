<?php

namespace AdminBundle\Entity;

class Translation
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $translation
     */
    private $translation;
    /**
     * Translation constructor.
     * @param $translation
     */
    public function __construct($translation)
    {
        $this->translation = $translation;
    }
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
    public function getTranslation()
    {
        return $this->translation;
    }
    /**
     * @param mixed $translation
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;
    }
}