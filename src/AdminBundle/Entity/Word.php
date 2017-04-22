<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Word
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
     * @var string $type
     */
    private $type;
    /**
     * @var int $language
     */
    private $language;
    /**
     * @var ArrayCollection $categories
     */
    private $categories;
    /**
     * @var WordImage $wordImage
     */
    private $wordImage;
    /**
     * @var WordImage $viewImage
     */
    private $viewImage;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return Word
     */
    public function setName($name) : Word
    {
        $this->name = $name;

        return $this;
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
     * @return Word
     */
    public function setLanguage($language) : Word
    {
        $this->language = $language;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Word
     */
    public function setType($type) : Word
    {
        $this->type = $type;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param mixed $createdAt
     * @return Word
     */
    public function setCreatedAt($createdAt) : Word
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * @param mixed $categories
     * @return Word
     */
    public function setCategories($categories) : Word
    {
        $this->categories = $categories;

        return $this;
    }
    /**
     * @return bool
     */
    public function hasWordImage() : bool
    {
        return $this->wordImage->getImageFile() instanceof UploadedFile;
    }
    /**
     * @return mixed
     */
    public function getWordImage()
    {
        return $this->wordImage;
    }
    /**
     * @param mixed $wordImage
     */
    public function setWordImage($wordImage)
    {
        $this->wordImage = $wordImage;
    }
    /**
     * @return WordImage
     */
    public function getViewImage(): WordImage
    {
        return $this->viewImage;
    }
    /**
     * @param WordImage $viewImage
     */
    public function setViewImage(WordImage $viewImage = null)
    {
        $this->viewImage = $viewImage;
    }
    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (count($this->getTranslations()) > 25) {
            $context->buildViolation('There can be only up to 25 translations for a word')
                ->atPath('translations')
                ->addViolation();
        }
    }
}